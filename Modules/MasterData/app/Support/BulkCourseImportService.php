<?php

namespace Modules\MasterData\Support;

use Illuminate\Support\Facades\DB;

class BulkCourseImportService
{
    public function __construct(
        private readonly BulkCourseParser $parser,
        private readonly CourseCodeGenerator $courseCodeGenerator,
    ) {}

    /**
     * @return array{
     *     success_count: int,
     *     failed_count: int,
     *     failed_text: string,
     *     log: list<array{name: string, reason: string}>
     * }
     */
    public function import(string $unitId, string $isActive, string $bulkText): array
    {
        $parsed = $this->parser->parse($bulkText);
        $entries = $parsed['entries'];

        $successCount = 0;
        $log = [];
        $failedRaws = [];
        $seenNames = [];

        $existingNames = DB::table('mst_course')
            ->where('unit_id', $unitId)
            ->pluck('course_name')
            ->map(fn (string $name): string => $this->normalizeName($name))
            ->flip()
            ->all();

        foreach ($entries as $entry) {
            if (! $entry['valid']) {
                $log[] = $this->logRow($entry, $entry['reason'] ?? 'Format tidak valid');
                $failedRaws[] = $entry['raw'];

                continue;
            }

            $nameKey = $this->normalizeName($entry['name']);

            if (isset($seenNames[$nameKey])) {
                $log[] = $this->logRow($entry, 'Nama duplikat dalam batch');
                $failedRaws[] = $entry['raw'];

                continue;
            }

            if (isset($existingNames[$nameKey])) {
                $log[] = $this->logRow($entry, 'Nama sudah terdaftar di prodi ini');
                $failedRaws[] = $entry['raw'];

                continue;
            }

            try {
                DB::transaction(function () use ($entry, $unitId, $isActive): void {
                    $newId = $this->courseCodeGenerator->generateNext($unitId);

                    DB::table('mst_course')->insert([
                        'id' => $newId,
                        'course_name' => $entry['name'],
                        'unit_id' => $unitId,
                        'is_active' => $isActive,
                        'created_at' => now(),
                    ]);
                });

                $seenNames[$nameKey] = true;
                $existingNames[$nameKey] = true;
                $successCount++;
            } catch (\Throwable $e) {
                $log[] = $this->logRow($entry, 'Gagal menyimpan: ' . $e->getMessage());
                $failedRaws[] = $entry['raw'];
            }
        }

        return [
            'success_count' => $successCount,
            'failed_count' => count($failedRaws),
            'failed_text' => implode("\n", $failedRaws),
            'log' => $log,
        ];
    }

    private function normalizeName(string $name): string
    {
        return mb_strtolower(trim($name));
    }

    /**
     * @param  array{name: string, raw: string, valid: bool, reason: string|null}  $entry
     * @return array{name: string, reason: string}
     */
    private function logRow(array $entry, string $reason): array
    {
        return [
            'name' => $entry['name'] !== '' ? $entry['name'] : ($entry['raw'] ?: '-'),
            'reason' => $reason,
        ];
    }
}