<?php

namespace Modules\MasterData\Support;

use Illuminate\Support\Facades\DB;

class LegacyCodeMigrator
{
    public function __construct(
        private readonly UnitCodeGenerator $unitCodeGenerator,
    ) {}

    public function migrate(): array
    {
        $unitMap = $this->buildUnitMap();
        $courseMap = $this->buildCourseMap($unitMap);

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $this->extendCourseIdColumns();
            $this->applyUnitMap($unitMap);
            $this->applyCourseMap($courseMap);
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        return [
            'units' => $unitMap,
            'courses' => $courseMap,
        ];
    }

    private function buildUnitMap(): array
    {
        $map = [];

        $units = DB::table('mst_unit')
            ->orderBy('unit_type_id')
            ->orderBy('id')
            ->get(['id', 'unit_name', 'unit_type_id']);

        $reserved = [];

        foreach ($units as $unit) {
            $candidate = $this->unitCodeGenerator->generate($unit->unit_name, (int) $unit->unit_type_id);
            $candidate = mb_strtoupper(mb_substr($candidate, 0, 4));

            while (in_array($candidate, $reserved, true)) {
                $candidate = $this->nextCollisionCode($candidate, $reserved);
            }

            $reserved[] = $candidate;
            $map[$unit->id] = $candidate;
        }

        return $map;
    }

    private function nextCollisionCode(string $base, array $reserved): string
    {
        for ($suffix = 2; $suffix <= 99; $suffix++) {
            $suffixStr = (string) $suffix;
            $candidate = mb_strtoupper(mb_substr($base, 0, 4 - mb_strlen($suffixStr)) . $suffixStr);

            if (! in_array($candidate, $reserved, true)) {
                return $candidate;
            }
        }

        throw new \RuntimeException("Gagal resolve tabrakan kode unit untuk {$base}");
    }

    private function buildCourseMap(array $unitMap): array
    {
        $map = [];

        $courses = DB::table('mst_course')
            ->orderBy('unit_id')
            ->orderBy('id')
            ->get(['id', 'unit_id']);

        $counters = [];

        foreach ($courses as $course) {
            $newUnitId = $unitMap[$course->unit_id] ?? $course->unit_id;
            $counters[$newUnitId] = ($counters[$newUnitId] ?? 0) + 1;
            $map[$course->id] = $newUnitId . str_pad((string) $counters[$newUnitId], 3, '0', STR_PAD_LEFT);
        }

        return $map;
    }

    private function extendCourseIdColumns(): void
    {
        $dropped = [];

        foreach ($this->courseForeignKeys() as $fk) {
            DB::statement("ALTER TABLE `{$fk->TABLE_NAME}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            $dropped[] = $fk;
        }

        foreach ([
            'mst_course' => 'id',
            'trx_exam_proposals' => 'course_id',
            'trx_questions' => 'course_id',
            'trx_cpl_cpmk_mapping' => 'course_id',
        ] as $table => $column) {
            if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` VARCHAR(8) NOT NULL");
            }
        }

        foreach ($dropped as $fk) {
            DB::statement(
                "ALTER TABLE `{$fk->TABLE_NAME}` ADD CONSTRAINT `{$fk->CONSTRAINT_NAME}` "
                . "FOREIGN KEY (`{$fk->COLUMN_NAME}`) REFERENCES `{$fk->REFERENCED_TABLE_NAME}` (`{$fk->REFERENCED_COLUMN_NAME}`)"
            );
        }
    }

    private function courseForeignKeys(): array
    {
        $database = DB::getDatabaseName();

        return DB::select(
            'SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE REFERENCED_TABLE_SCHEMA = ?
               AND REFERENCED_TABLE_NAME = ?
               AND REFERENCED_COLUMN_NAME = ?',
            [$database, 'mst_course', 'id']
        );
    }

    private function applyUnitMap(array $unitMap): void
    {
        foreach ($unitMap as $oldId => $newId) {
            if ($oldId === $newId) {
                continue;
            }

            $this->replaceUnitReferences($oldId, $newId);
        }

        foreach ($unitMap as $oldId => $newId) {
            if ($oldId === $newId) {
                continue;
            }

            DB::table('mst_unit')->where('id', $oldId)->update(['id' => $newId]);
        }
    }

    private function replaceUnitReferences(string $oldId, string $newId): void
    {
        foreach ([
            'mst_user',
            'trx_user_role',
            'mst_user_unit',
            'mst_cpl',
            'mst_inventory',
            'trx_document',
            'trx_achievements',
            'mst_course',
        ] as $table) {
            DB::table($table)->where('unit_id', $oldId)->update(['unit_id' => $newId]);
        }

        DB::table('mst_unit')->where('unit_parent', $oldId)->update(['unit_parent' => $newId]);

        if (\Illuminate\Support\Facades\Schema::hasTable('mst_cpl')) {
            DB::table('mst_cpl')->where('unit_id', $oldId)->update(['unit_id' => $newId]);
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('trx_cpl_cpmk_mapping')) {
            DB::table('trx_cpl_cpmk_mapping')->where('unit_id', $oldId)->update(['unit_id' => $newId]);
        }
    }

    private function applyCourseMap(array $courseMap): void
    {
        foreach ($courseMap as $oldId => $newId) {
            if ($oldId === $newId) {
                continue;
            }

            DB::table('trx_exam_proposals')->where('course_id', $oldId)->update(['course_id' => $newId]);
            DB::table('trx_questions')->where('course_id', $oldId)->update(['course_id' => $newId]);
            DB::table('trx_cpl_cpmk_mapping')->where('course_id', $oldId)->update(['course_id' => $newId]);
            if (\Illuminate\Support\Facades\Schema::hasTable('mst_cpmk')) {
                DB::table('mst_cpmk')->where('course_id', $oldId)->update(['course_id' => $newId]);
            }
        }

        foreach ($courseMap as $oldId => $newId) {
            if ($oldId === $newId) {
                continue;
            }

            DB::table('mst_course')->where('id', $oldId)->update(['id' => $newId]);
        }
    }
}