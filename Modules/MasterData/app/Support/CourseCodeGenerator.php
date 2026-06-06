<?php

namespace Modules\MasterData\Support;

use Illuminate\Support\Facades\DB;

class CourseCodeGenerator
{
    public function generateNext(string $prodiUnitId): string
    {
        $prodi = DB::table('mst_unit')
            ->where('id', $prodiUnitId)
            ->where('unit_type_id', 3)
            ->first();

        if (! $prodi) {
            throw new \InvalidArgumentException('Unit prodi tidak ditemukan atau bukan tipe prodi.');
        }

        $prefix = $prodi->id;
        $existingIds = DB::table('mst_course')
            ->where('unit_id', $prodiUnitId)
            ->pluck('id');

        $maxNumber = $existingIds
            ->filter(fn (string $id): bool => preg_match('/^' . preg_quote($prefix, '/') . '\d{3}$/', $id) === 1)
            ->map(fn (string $id): int => (int) mb_substr($id, mb_strlen($prefix)))
            ->max() ?? 0;

        $nextNumber = $maxNumber + 1;

        do {
            $newId = $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (DB::table('mst_course')->where('id', $newId)->exists());

        return $newId;
    }
}