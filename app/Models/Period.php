<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    // Sesuai dengan query DB::table('mst_period') di Controller kamu
    protected $table = 'mst_period';

    protected $guarded = ['id'];

    /**
     * Relasi: Satu Periode bisa memiliki banyak Pengajuan Ujian
     */
    public function examProposals()
    {
        return $this->hasMany(\Modules\MonevAkademik\app\Models\ExamProposal::class, 'period_id');
    }
}