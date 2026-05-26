<?php

namespace Modules\MonevAkademik\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Question extends Model
{
    use HasUuids;

    protected $table = 'trx_questions';

    protected $fillable = [
        'course_id',
        'cpmk_id',
        'question_text',
        'image_path',
        'created_by',
    ];

    protected $casts = [
        'cpmk_id' => 'array', // Otomatis jadi array pas dipanggil di PHP
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\MstCourse::class, 'course_id', 'id');
    }

    // ❌ KITA HAPUS/COMMENT RELASI INI KARENA UDAH JADI ARRAY
    // public function cpmk()
    // {
    //     return $this->belongsTo(\App\Models\MstCpmk::class, 'cpmk_id', 'id');
    // }

    // ✅ KITA GANTI PAKE ACCESSOR INI BUAT NARIK DATA MULTI-CPMK
    public function getCpmkDetailsAttribute()
    {
        // Kalau cpmk_id kosong atau bukan array, balikin array kosong
        if (empty($this->cpmk_id) || !is_array($this->cpmk_id)) {
            return [];
        }

        // Tarik semua data MstCpmk yang ID-nya ada di dalam array cpmk_id
        return \App\Models\MstCpmk::whereIn('id', $this->cpmk_id)
            ->select('id', 'name') // Sesuaikan 'name' dengan nama kolom di tabel mst_cpmk lu
            ->get();
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class, 'question_id', 'id');
    }
}