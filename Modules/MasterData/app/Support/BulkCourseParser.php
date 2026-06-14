<?php

namespace Modules\MasterData\Support;

class BulkCourseParser
{
    public const MAX_LINES = 100;

    /**
     * @return array{entries: list<array{name: string, raw: string, valid: bool, reason: string|null}>}
     */
    public function parse(string $rawInput): array
    {
        $rawInput = trim($rawInput);
        if ($rawInput === '') {
            return ['entries' => []];
        }

        $lines = preg_split('/\R+/u', $rawInput) ?: [];
        $entries = [];
        $validCount = 0;

        foreach ($lines as $line) {
            $line = trim(preg_replace('/\s+/u', ' ', $line) ?? '');
            if ($line === '') {
                continue;
            }

            $name = $this->extractName($line);

            if ($name === '') {
                $entries[] = [
                    'name' => '',
                    'raw' => $line,
                    'valid' => false,
                    'reason' => 'Nama mata kuliah kosong',
                ];

                continue;
            }

            if (mb_strlen($name) > 100) {
                $entries[] = [
                    'name' => $name,
                    'raw' => $line,
                    'valid' => false,
                    'reason' => 'Nama maksimal 100 karakter',
                ];

                continue;
            }

            if ($validCount >= self::MAX_LINES) {
                $entries[] = [
                    'name' => $name,
                    'raw' => $line,
                    'valid' => false,
                    'reason' => 'Melebihi batas maksimal ' . self::MAX_LINES . ' baris',
                ];

                continue;
            }

            $validCount++;
            $entries[] = [
                'name' => $name,
                'raw' => $line,
                'valid' => true,
                'reason' => null,
            ];
        }

        return ['entries' => $entries];
    }

    private function extractName(string $line): string
    {
        if (str_contains($line, ',') || str_contains($line, ';')) {
            $parts = preg_split('/[,;]/', $line) ?: [];
            $line = trim((string) ($parts[0] ?? ''));
        }

        return trim($line);
    }
}