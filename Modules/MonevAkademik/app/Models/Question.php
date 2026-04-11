<?php

namespace Modules\MonevAkademik\App\Models;

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

    public function cpmk()
    {
        return $this->belongsTo(\App\Models\MstCpmk::class, 'cpmk_id', 'id');
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