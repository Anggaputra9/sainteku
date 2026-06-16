<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    // Sesuai dengan query DB::table('mst_period') di Controller kamu
    protected $table = 'mst_period';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $appends = ['display_label'];

    public static function displayLabel(?string $name, ?string $semester = null): ?string
    {
        $name = trim((string) $name);
        $semester = trim((string) $semester);

        if ($name === '') {
            return $semester !== '' ? $semester : null;
        }

        return $semester !== '' ? "{$name} {$semester}" : $name;
    }

    public function getDisplayLabelAttribute(): string
    {
        return self::displayLabel($this->name, $this->semester) ?? '—';
    }

    /**
     * Relasi: Satu Periode bisa memiliki banyak Pengajuan Ujian
     */
    public function examProposals()
    {
        return $this->hasMany(\Modules\MonevAkademik\app\Models\ExamProposal::class, 'period_id');
    }
}