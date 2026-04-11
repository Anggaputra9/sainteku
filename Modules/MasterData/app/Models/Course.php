<?php

namespace Modules\MasterData\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $table = 'mst_course';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false; // Karena tabelnya hanya punya created_at, tidak ada updated_at

    protected $fillable = [
        'id',
        'course_name',
        'unit_id',
        'is_active',
        'created_at'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}