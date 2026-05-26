<?php

namespace Modules\MonevAkademik\app\Models; // <-- Pastikan Alamatnya Modul!

use Illuminate\Database\Eloquent\Model;

class ExamQuestionLog extends Model
{
    // Kasih tahu nama tabel aslinya
    protected $table = 'trx_exam_question_logs';
    // Di ExamQuestionLog.php
    protected $keyType = 'string';
    public $incrementing = false; // Karena ID lu bukan angka 1, 2, 3 murni

    // Buka gembok keamanan biar bisa simpan data (Mass Assignment)
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}