<?php

namespace Modules\MonevAkademik\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ExamProposal extends Model
{
    use HasUuids;

    protected $table = 'trx_exam_proposals';

    protected $fillable = [
        'period_id',
        'course_id',
        'exam_type',
        'status',
        'created_by',
        'approved_by',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    // Relasi ke Mata Kuliah (Asumsi model MstCourse ada di modul MasterData atau App\Models)
    public function course()
    {
        return $this->belongsTo(\App\Models\MstCourse::class, 'course_id', 'id');
    }

    /**
     * Relasi ke tabel Periode (mst_period)
     */
    public function period()
    {
        // Pastikan \App\Models\Period adalah lokasi model Period yang baru kita buat tadi
        return $this->belongsTo(\App\Models\Period::class, 'period_id');
    }
    // Relasi ke Dosen Pembuat
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by', 'id');
    }
    // Relasi ke Pivot Soal-Soal Ujian
    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class, 'proposal_id', 'id')->orderBy('order_no');
    }

    // Relasi ke Histori Review
    public function reviews()
    {
        return $this->hasMany(ExamReview::class, 'proposal_id', 'id')->orderBy('created_at', 'desc');
    }

    public function logs()
    {
        // Arahkan ke namespace Modul yang bener cuy
        return $this->hasMany(\Modules\MonevAkademik\App\Models\ExamQuestionLog::class, 'proposal_id');
    }
}