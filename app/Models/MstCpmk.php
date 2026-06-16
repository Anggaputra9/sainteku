<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MstCpmk extends Model
{
    protected $table = 'mst_cpmk';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'id',
        'name',
        'is_active',
        'created_at',
    ];

    public function course()
    {
        return $this->belongsTo(MstCourse::class, 'course_id');
    }

    public function scopeForCourse(Builder $query, string $courseId, bool $activeOnly = true): Builder
    {
        $query->where('course_id', $courseId);

        if ($activeOnly) {
            $query->where('is_active', '1');
        }

        return $query->orderBy('id');
    }

    public static function validateIdsForCourse(string $courseId, array $cpmkIds): bool
    {
        $cpmkIds = array_values(array_unique(array_filter($cpmkIds)));

        if ($cpmkIds === []) {
            return false;
        }

        $validCount = self::query()
            ->forCourse($courseId, true)
            ->whereIn('id', $cpmkIds)
            ->count();

        return $validCount === count($cpmkIds);
    }
}