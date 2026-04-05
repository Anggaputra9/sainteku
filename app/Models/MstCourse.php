<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstCourse extends Model
{
    protected $table = 'mst_course';

    // Matikan auto-increment karena ID berupa string (contoh: MK001)
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'course_name',
        'unit_id',
        'is_active'
    ];
}