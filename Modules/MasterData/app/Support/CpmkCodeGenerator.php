<?php

namespace Modules\MasterData\Support;

use Illuminate\Support\Facades\DB;

class CpmkCodeGenerator
{
    public function generateNext(string $courseId): string
    {
        $existingIds = DB::table('mst_cpmk')
            ->where('course_id', $courseId)
            ->pluck('id');

        $maxNumber = $existingIds
            ->filter(fn (string $id): bool => preg_match('/^CP-\d{2}$/', $id) === 1)
            ->map(fn (string $id): int => (int) mb_substr($id, 3))
            ->max() ?? 0;

        $nextNumber = $maxNumber + 1;

        do {
            $newId = 'CP-' . str_pad((string) $nextNumber, 2, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (
            DB::table('mst_cpmk')
                ->where('course_id', $courseId)
                ->where('id', $newId)
                ->exists()
        );

        return $newId;
    }
}