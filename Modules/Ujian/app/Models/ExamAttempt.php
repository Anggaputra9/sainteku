<?php

namespace Modules\Ujian\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExamAttempt extends Model
{
    protected $table = 'trx_exam_attempts';

    protected $fillable = [
        'uuid',
        'room_id',
        'user_id',
        'started_at',
        'expires_at',
        'submitted_at',
        'status',
        'tab_switch_count',
        'last_activity_at',
        'ip_address',
        'user_agent',
        'question_order',
        'score',
        'grader_note',
    ];

    protected $casts = [
        'started_at'       => 'datetime',
        'expires_at'       => 'datetime',
        'submitted_at'     => 'datetime',
        'last_activity_at' => 'datetime',
        'tab_switch_count' => 'integer',
        'question_order'   => 'array',
        'score'            => 'decimal:2',
    ];

    /**
     * Auto-generate UUID setiap kali bikin attempt baru.
     * UUID dipakai di route hasil ujian biar tidak gampang ditebak
     * (mencegah enumerasi /attempts/1, /attempts/2, ...).
     */
    protected static function booted(): void
    {
        static::creating(function (ExamAttempt $attempt) {
            if (empty($attempt->uuid)) {
                $attempt->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /* =========== Relasi =========== */

    public function room()
    {
        return $this->belongsTo(ExamRoom::class, 'room_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(ExamAttemptAnswer::class, 'attempt_id', 'id');
    }

    public function events()
    {
        return $this->hasMany(ExamAttemptEvent::class, 'attempt_id', 'id');
    }

    /* =========== Helper =========== */

    public function isOngoing(): bool
    {
        return $this->status === 'ONGOING';
    }

    public function isFinished(): bool
    {
        return in_array($this->status, [
            'SUBMITTED',
            'AUTO_SUBMITTED_TIME',
            'AUTO_SUBMITTED_VIOLATION',
        ], true);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'NOT_STARTED'              => 'Belum Mulai',
            'ONGOING'                  => 'Sedang Mengerjakan',
            'SUBMITTED'                => 'Selesai',
            'AUTO_SUBMITTED_TIME'      => 'Auto-Submit (Waktu Habis)',
            'AUTO_SUBMITTED_VIOLATION' => 'Auto-Submit (Pelanggaran)',
        };
    }

    /**
     * Hitung ulang total skor attempt berdasarkan bobot tiap soal.
     * Rumus: sum(nilai_soal × bobot_soal / 100)
     */
    public function recalculateScore(): float
    {
        $this->loadMissing(['room.proposal.examQuestions', 'answers']);

        $proposal = $this->room?->proposal;
        if (!$proposal) {
            return (float) ($this->score ?? 0);
        }

        $answersByQuestion = $this->answers->keyBy('question_id');
        $totalScore = 0;
        $totalWeight = 0;

        foreach ($proposal->examQuestions as $examQuestion) {
            $answer = $answersByQuestion->get($examQuestion->question_id);
            if ($answer && $answer->score !== null) {
                $weight = (float) ($examQuestion->weight ?? 0);
                $totalScore += ((float) $answer->score * $weight / 100);
                $totalWeight += $weight;
            }
        }

        $finalScore = $totalWeight > 0 ? $totalScore : 0;
        $this->update(['score' => $finalScore]);

        return (float) $finalScore;
    }
}
