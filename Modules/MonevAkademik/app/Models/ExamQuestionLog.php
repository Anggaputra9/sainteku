<?php

namespace App\Models; // (Atau sesuaikan namespace modulmu: Modules\MonevAkademik\App\Models)

use Illuminate\Database\Eloquent\Model;

class ExamQuestionLog extends Model
{
    // Deklarasi eksplisit pakai standar trx_
    protected $table = 'trx_exam_question_logs';
    protected $guarded = [];

    public function user()
    {
        // Relasi ke tabel mst_user
        return $this->belongsTo(User::class, 'user_id');
    }
}