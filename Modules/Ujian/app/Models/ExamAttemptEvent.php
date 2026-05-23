<?php

namespace Modules\Ujian\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttemptEvent extends Model
{
    protected $table = 'trx_exam_attempt_events';

    public $timestamps = false;

    protected $fillable = [
        'attempt_id',
        'event_type',
        'payload',
        'occurred_at',
    ];

    protected $casts = [
        'payload'     => 'array',
        'occurred_at' => 'datetime',
    ];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id', 'id');
    }
}
