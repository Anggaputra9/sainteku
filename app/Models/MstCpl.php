<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MstCpl extends Model
{
    protected $table = 'mst_cpl';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'unit_id',
        'id',
        'name',
        'is_active',
        'created_at',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function scopeForUnit(Builder $query, string $unitId, bool $activeOnly = true): Builder
    {
        $query->where('unit_id', $unitId);

        if ($activeOnly) {
            $query->where('is_active', '1');
        }

        return $query->orderBy('id');
    }

    public static function validateIdsForUnit(string $unitId, array $cplIds): bool
    {
        $cplIds = array_values(array_unique(array_filter($cplIds)));

        if ($cplIds === []) {
            return true;
        }

        $validCount = self::query()
            ->forUnit($unitId, true)
            ->whereIn('id', $cplIds)
            ->count();

        return $validCount === count($cplIds);
    }
}