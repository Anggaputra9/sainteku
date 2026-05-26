<?php

namespace Modules\Ujian\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\MonevAkademik\app\Models\Question;

class ExamAttemptAnswer extends Model
{
    protected $table = 'trx_exam_attempt_answers';

    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer_text',
        'is_answered',
        'score',
        'grader_note',
        'grading_method',
        'ai_feedback',
        'graded_by',
        'graded_at',
    ];

    protected $casts = [
        'is_answered' => 'boolean',
        'score'       => 'decimal:2',
        'graded_at'   => 'datetime',
    ];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function grader()
    {
        return $this->belongsTo(\App\Models\User::class, 'graded_by', 'id');
    }
}
