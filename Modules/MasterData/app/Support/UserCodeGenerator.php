<?php

namespace Modules\MasterData\Support;

use Illuminate\Support\Facades\DB;

class UserCodeGenerator
{
    public const ROLE_RANK = [
        'ADM' => 1,
        'DKN' => 2,
        'GKM' => 3,
        'KPD' => 4,
        'OPS' => 5,
        'DSN' => 6,
        'RVI' => 7,
        'RVE' => 7,
        'MHS' => 8,
    ];

    public const ROLE_CODES = ['ADM', 'DKN', 'GKM', 'KPD', 'OPS', 'DSN', 'RVI', 'RVE', 'MHS'];

    public function resolvePrimaryRoleCode(array $roleCodes): string
    {
        if ($roleCodes === []) {
            throw new \InvalidArgumentException('User harus memiliki minimal satu role.');
        }

        $best = null;
        $bestRank = PHP_INT_MAX;

        foreach ($roleCodes as $roleCode) {
            $roleCode = strtoupper($roleCode);
            $rank = self::ROLE_RANK[$roleCode] ?? 99;

            if ($rank < $bestRank || ($rank === $bestRank && ($best === null || $roleCode < $best))) {
                $bestRank = $rank;
                $best = $roleCode;
            }
        }

        return $best;
    }

    public function resolvePrimaryRoleCodeFromRoleIds(array $roleIds): string
    {
        $roleCodes = DB::table('mst_role')
            ->whereIn('id', $roleIds)
            ->pluck('role_code')
            ->all();

        return $this->resolvePrimaryRoleCode($roleCodes);
    }

    public function formatId(string $roleCode, string $unitId, int $sequence): string
    {
        return strtoupper($roleCode) . '-' . strtoupper($unitId) . '-' . str_pad((string) $sequence, 7, '0', STR_PAD_LEFT);
    }

    public function generateNext(string $roleCode, string $unitId): string
    {
        $roleCode = strtoupper($roleCode);
        $unitId = strtoupper($unitId);
        $pattern = '/^' . preg_quote($roleCode, '/') . '-' . preg_quote($unitId, '/') . '-(\d{7})$/';

        $maxNumber = DB::table('mst_user')
            ->pluck('id')
            ->filter(fn (string $id): bool => preg_match($pattern, $id) === 1)
            ->map(fn (string $id): int => (int) mb_substr($id, -7))
            ->max() ?? 0;

        $nextNumber = $maxNumber + 1;

        do {
            $newId = $this->formatId($roleCode, $unitId, $nextNumber);
            $nextNumber++;
        } while (DB::table('mst_user')->where('id', $newId)->exists());

        return $newId;
    }

    public function isNewFormat(string $id): bool
    {
        $roles = implode('|', self::ROLE_CODES);

        return preg_match('/^(' . $roles . ')-[A-Z0-9]{2,4}-\d{7}$/', $id) === 1;
    }

    public function matchesBucket(string $id, string $roleCode, string $unitId): bool
    {
        $prefix = strtoupper($roleCode) . '-' . strtoupper($unitId) . '-';

        return $this->isNewFormat($id) && str_starts_with($id, $prefix);
    }
}