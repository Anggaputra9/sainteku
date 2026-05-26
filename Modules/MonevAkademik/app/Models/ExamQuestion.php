<?php

namespace Modules\MonevAkademik\app\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $table = 'trx_exam_questions';

    protected $fillable = [
        'proposal_id',
        'question_id',
        'order_no',
        'weight',
    ];

    public function proposal()
    {
        return $this->belongsTo(ExamProposal::class, 'proposal_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}