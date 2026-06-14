<?php

namespace Modules\Ujian\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\MonevAkademik\app\Models\ExamProposal;

class ExamRoom extends Model
{
    public const JOIN_GRACE_MINUTES = 15;

    protected $table = 'trx_exam_rooms';

    protected $fillable = [
        'uuid',
        'room_code',
        'proposal_id',
        'title',
        'description',
        'created_by',
        'start_at',
        'started_at',
        'end_at',
        'duration_minutes',
        'tab_switch_policy',
        'tab_switch_limit',
        'shuffle_questions',
        'show_remaining_time',
        'auto_grading_enabled',
        'status',
        'is_active',
    ];

    protected $casts = [
        'start_at'            => 'datetime',
        'started_at'          => 'datetime',
        'end_at'              => 'datetime',
        'shuffle_questions'   => 'boolean',
        'show_remaining_time' => 'boolean',
        'auto_grading_enabled' => 'boolean',
        'is_active'           => 'boolean',
        'duration_minutes'    => 'integer',
        'tab_switch_limit'    => 'integer',
    ];

    /**
     * Auto-generate UUID + kode room (6 char) saat creating.
     * - uuid dipakai untuk URL admin (show/edit/delete) — tidak mudah ditebak.
     * - room_code (6 char) tetap dipakai untuk join mahasiswa (dipendek + QR friendly).
     */
    protected static function booted(): void
    {
        static::creating(function (ExamRoom $room) {
            if (empty($room->uuid)) {
                $room->uuid = (string) Str::uuid();
            }
            if (empty($room->room_code)) {
                $room->room_code = self::generateUniqueCode();
            }
        });
    }

    /**
     * Pakai uuid sebagai route key — kalau controller pakai
     * Route::resource dengan model binding, otomatis match by uuid.
     * Untuk endpoint yang manual (find), tetap bisa pakai resolveBy.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public static function generateUniqueCode(int $length = 6): string
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (self::where('room_code', $code)->exists());

        return $code;
    }

    /* =========== Relasi =========== */

    public function proposal()
    {
        return $this->belongsTo(ExamProposal::class, 'proposal_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class, 'room_id', 'id');
    }

    /* =========== Helper =========== */

    public function isOpenNow(): bool
    {
        $now = now();
        $joinOpens = $this->joinOpensAt();

        return $this->status === 'PUBLISHED'
            && $this->is_active
            && $joinOpens
            && $now->gte($joinOpens)
            && $now->lte($this->end_at);
    }

    /**
     * Mahasiswa boleh masuk mulai 15 menit sebelum waktu mulai ujian.
     */
    public function joinOpensAt(): ?\Carbon\Carbon
    {
        if ($this->started_at && $this->start_at && $this->started_at->lt($this->start_at)) {
            return $this->started_at->copy();
        }

        $examStart = $this->started_at ?? $this->start_at;

        return $examStart?->copy()->subMinutes(self::JOIN_GRACE_MINUTES);
    }

    /**
     * Batas akhir mahasiswa baru boleh masuk (15 menit setelah ujian dimulai).
     */
    public function joinDeadline(): ?\Carbon\Carbon
    {
        $examStart = $this->started_at ?? $this->start_at;

        return $examStart?->copy()->addMinutes(self::JOIN_GRACE_MINUTES);
    }

    public static function computeEndAt(\Carbon\Carbon $examStart, int $durationMinutes): \Carbon\Carbon
    {
        return $examStart->copy()->addMinutes($durationMinutes);
    }

    /**
     * Mulai otomatis saat waktu terjadwal tiba (status DRAFT → PUBLISHED).
     */
    public function autoStartIfScheduled(): bool
    {
        if ($this->status !== 'DRAFT' || ! $this->start_at) {
            return false;
        }

        $joinOpens = $this->start_at->copy()->subMinutes(self::JOIN_GRACE_MINUTES);
        if (now()->lt($joinOpens)) {
            return false;
        }

        $this->forceFill([
            'started_at' => $this->start_at->copy(),
            'status'     => 'PUBLISHED',
            'is_active'  => true,
            'end_at'     => self::computeEndAt($this->start_at, (int) $this->duration_minutes),
        ])->save();

        return true;
    }

    public static function autoStartScheduled(): int
    {
        $count = 0;

        self::where('status', 'DRAFT')
            ->where('start_at', '<=', now()->addMinutes(self::JOIN_GRACE_MINUTES))
            ->orderBy('id')
            ->each(function (self $room) use (&$count) {
                if ($room->autoStartIfScheduled()) {
                    $count++;
                }
            });

        return $count;
    }

    /**
     * Apakah waktu room sudah lewat? (end_at < sekarang)
     */
    public function isExpired(): bool
    {
        return $this->end_at && $this->end_at->isPast();
    }

    /**
     * Auto-close room ini bila masih PUBLISHED tapi end_at sudah lewat.
     * Mengembalikan true bila berhasil di-close, false bila tidak ada perubahan.
     *
     * Tujuan: status di UI dosen / mahasiswa otomatis menjadi "CLOSED"
     * begitu waktu ujian berakhir, tanpa perlu cron job. Dosen tetap
     * bisa membuka kembali via endpoint reopen (mengganti end_at baru).
     */
    public function autoCloseIfExpired(): bool
    {
        if ($this->status === 'PUBLISHED' && $this->isExpired()) {
            $this->forceFill([
                'status'    => 'CLOSED',
                'is_active' => false,
            ])->save();

            return true;
        }

        return false;
    }

    /**
     * Bulk auto-close: dipakai sekali di awal request dosen agar daftar
     * room di list / monitor langsung up-to-date tanpa harus loop per-row.
     */
    public static function autoCloseExpired(): int
    {
        return self::where('status', 'PUBLISHED')
            ->where('end_at', '<', now())
            ->update([
                'status'    => 'CLOSED',
                'is_active' => false,
            ]);
    }

    public function tabSwitchLabel(): string
    {
        return match ($this->tab_switch_policy) {
            'unlimited' => 'Tanpa Batas',
            'strict'    => 'Tanpa Toleransi',
            'limited'   => "Maks {$this->tab_switch_limit}x",
        };
    }
}
