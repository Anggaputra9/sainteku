<?php

namespace Modules\MasterData\Support;

use Illuminate\Support\Facades\DB;

class CplCodeGenerator
{
    public function generateNext(string $unitId): string
    {
        $existingIds = DB::table('mst_cpl')
            ->where('unit_id', $unitId)
            ->pluck('id');

        $maxNumber = $existingIds
            ->filter(fn (string $id): bool => preg_match('/^CL-\d{2}$/', $id) === 1)
            ->map(fn (string $id): int => (int) mb_substr($id, 3))
            ->max() ?? 0;

        $nextNumber = $maxNumber + 1;

        do {
            $newId = 'CL-' . str_pad((string) $nextNumber, 2, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (
            DB::table('mst_cpl')
                ->where('unit_id', $unitId)
                ->where('id', $newId)
                ->exists()
        );

        return $newId;
    }
}