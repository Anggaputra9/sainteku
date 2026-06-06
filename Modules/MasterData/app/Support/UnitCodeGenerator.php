<?php

namespace Modules\MasterData\Support;

use Illuminate\Support\Facades\DB;

class UnitCodeGenerator
{
    private const STOPWORDS = ['dan', 'ke', 'di', 'the', 'of', 'untuk', 'pada', 'yang', 'atau'];

    public function generate(string $unitName, int $unitTypeId): string
    {
        return match ($unitTypeId) {
            1 => $this->generateUnivCode($unitName),
            2 => $this->generateFakultasCode($unitName),
            3 => $this->generateProdiCode($unitName),
            4 => $this->generateLembagaCode($unitName),
            default => throw new \InvalidArgumentException("Tipe unit tidak dikenal: {$unitTypeId}"),
        };
    }

    public function generateUnique(string $unitName, int $unitTypeId): string
    {
        $base = mb_strtoupper(mb_substr($this->generate($unitName, $unitTypeId), 0, 4));

        if (! $this->exists($base)) {
            return $base;
        }

        for ($suffix = 2; $suffix <= 99; $suffix++) {
            $suffixStr = (string) $suffix;
            $candidate = mb_strtoupper(mb_substr($base, 0, 4 - mb_strlen($suffixStr)) . $suffixStr);

            if (! $this->exists($candidate)) {
                return $candidate;
            }
        }

        throw new \RuntimeException("Tidak dapat menghasilkan kode unik untuk unit: {$unitName}");
    }

    private function generateUnivCode(string $unitName): string
    {
        if (preg_match('/\bUIN\b/iu', $unitName)) {
            return 'UIN';
        }

        $words = $this->extractSignificantWords($unitName);

        return mb_strtoupper(mb_substr($words[0] ?? 'UNV', 0, 3));
    }

    private function generateFakultasCode(string $unitName): string
    {
        $words = $this->extractSignificantWords($unitName, ['fakultas']);
        $initials = $this->wordInitials($words);

        return 'F' . mb_substr($initials, 0, 3);
    }

    private function generateProdiCode(string $unitName): string
    {
        $words = $this->extractSignificantWords($unitName);

        if (count($words) === 1) {
            return mb_strtoupper(mb_substr($words[0], 0, 3));
        }

        $code = $this->wordInitials($words);

        if (mb_strlen($code) < 3) {
            $lastWord = mb_strtoupper($words[array_key_last($words)]);
            foreach (mb_str_split($lastWord) as $char) {
                if (! str_contains($code, $char)) {
                    $code .= $char;
                }
                if (mb_strlen($code) >= 3) {
                    break;
                }
            }
        }

        return mb_strtoupper(mb_substr(str_pad($code, 3, 'X'), 0, 3));
    }

    private function generateLembagaCode(string $unitName): string
    {
        $words = $this->extractSignificantWords($unitName, ['lembaga', 'upt']);
        $initials = $this->wordInitials($words);

        return 'L' . mb_substr($initials, 0, 3);
    }

    private function extractSignificantWords(string $name, array $stripPrefixes = []): array
    {
        $normalized = mb_strtolower(trim($name));

        foreach ($stripPrefixes as $prefix) {
            $normalized = preg_replace('/^' . preg_quote($prefix, '/') . '\s+/iu', '', $normalized) ?? $normalized;
        }

        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $normalized) ?? $normalized;
        $words = preg_split('/\s+/u', trim($normalized)) ?: [];

        return array_values(array_filter($words, function (string $word): bool {
            return $word !== '' && ! in_array($word, self::STOPWORDS, true);
        }));
    }

    private function wordInitials(array $words): string
    {
        return implode('', array_map(
            fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)),
            $words
        ));
    }

    private function exists(string $id): bool
    {
        return DB::table('mst_unit')->where('id', $id)->exists();
    }
}