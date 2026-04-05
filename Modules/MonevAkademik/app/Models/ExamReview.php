<?php

namespace Modules\MonevAkademik\App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamReview extends Model
{
    protected $table = 'trx_exam_reviews';

    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'comment',
    ];

    public function proposal()
    {
        return $this->belongsTo(ExamProposal::class, 'proposal_id', 'id');
    }

    public function reviewer()
    {
        return $this->belongsTo(\App\Models\User::class, 'reviewer_id', 'id');
    }
}