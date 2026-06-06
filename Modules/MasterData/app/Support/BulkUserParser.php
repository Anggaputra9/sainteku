<?php

namespace Modules\MasterData\Support;

use Modules\MasterData\Http\Controllers\AdminController;

class BulkUserParser
{
    public const STUDENT_EMAIL_DOMAIN = 'mhs.uinsaizu.ac.id';

    /**
     * @return array{entries: list<array{name: string, identity_id: string, email: string, raw: string, valid: bool, reason: string|null}>}
     */
    public function parse(string $rawInput, string $userType): array
    {
        $rawInput = trim($rawInput);
        if ($rawInput === '') {
            return ['entries' => []];
        }

        $isStudent = $userType === AdminController::STUDENT_USER_TYPE;

        if ($isStudent) {
            return ['entries' => $this->parseStudentText($rawInput)];
        }

        return ['entries' => $this->parseStaffText($rawInput)];
    }

    /**
     * @return list<array{name: string, identity_id: string, email: string, raw: string, valid: bool, reason: string|null}>
     */
    private function parseStudentText(string $text): array
    {
        $normalized = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
        $entries = [];

        if (! preg_match_all('/\b(\d{5,20})\b/', $normalized, $matches, PREG_OFFSET_CAPTURE)) {
            return [[
                'name' => '',
                'identity_id' => '',
                'email' => '',
                'raw' => $normalized,
                'valid' => false,
                'reason' => 'NIM tidak ditemukan',
            ]];
        }

        $lastEnd = 0;
        foreach ($matches[1] as $match) {
            $identityId = $match[0];
            $identityPos = (int) $match[1];
            $name = trim(substr($normalized, $lastEnd, $identityPos - $lastEnd));
            $lastEnd = $identityPos + strlen($identityId);
            $raw = trim($name . ' ' . $identityId);

            if ($name === '') {
                $entries[] = [
                    'name' => '',
                    'identity_id' => $identityId,
                    'email' => '',
                    'raw' => $raw,
                    'valid' => false,
                    'reason' => 'Nama tidak ditemukan',
                ];

                continue;
            }

            $entries[] = [
                'name' => $name,
                'identity_id' => $identityId,
                'email' => $this->studentEmail($identityId),
                'raw' => $raw,
                'valid' => true,
                'reason' => null,
            ];
        }

        $trailing = trim(substr($normalized, $lastEnd));
        if ($trailing !== '') {
            $entries[] = [
                'name' => $trailing,
                'identity_id' => '',
                'email' => '',
                'raw' => $trailing,
                'valid' => false,
                'reason' => 'NIM tidak ditemukan',
            ];
        }

        return $entries;
    }

    /**
     * @return list<array{name: string, identity_id: string, email: string, raw: string, valid: bool, reason: string|null}>
     */
    private function parseStaffText(string $text): array
    {
        $lines = preg_split('/\R+/u', $text) ?: [];
        $entries = [];

        foreach ($lines as $line) {
            $line = trim(preg_replace('/\s+/u', ' ', $line) ?? '');
            if ($line === '') {
                continue;
            }

            $entries[] = $this->parseStaffLine($line);
        }

        return $entries;
    }

    /**
     * @return array{name: string, identity_id: string, email: string, raw: string, valid: bool, reason: string|null}
     */
    private function parseStaffLine(string $line): array
    {
        if (! preg_match('/\S+@\S+\.\S+/u', $line, $emailMatch, PREG_OFFSET_CAPTURE)) {
            return [
                'name' => '',
                'identity_id' => '',
                'email' => '',
                'raw' => $line,
                'valid' => false,
                'reason' => 'Email tidak ditemukan',
            ];
        }

        $email = strtolower($emailMatch[0][0]);
        $emailPos = (int) $emailMatch[0][1];
        $beforeEmail = trim(substr($line, 0, $emailPos));

        if (! preg_match('/\b(\d{5,20})\b/u', $beforeEmail, $nipMatch, PREG_OFFSET_CAPTURE)) {
            return [
                'name' => '',
                'identity_id' => '',
                'email' => $email,
                'raw' => $line,
                'valid' => false,
                'reason' => 'NIP/NIK tidak ditemukan',
            ];
        }

        $identityId = $nipMatch[1][0];
        $nipPos = (int) $nipMatch[1][1];
        $name = trim(substr($beforeEmail, 0, $nipPos));

        if ($name === '') {
            return [
                'name' => '',
                'identity_id' => $identityId,
                'email' => $email,
                'raw' => $line,
                'valid' => false,
                'reason' => 'Nama tidak ditemukan',
            ];
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'name' => $name,
                'identity_id' => $identityId,
                'email' => $email,
                'raw' => $line,
                'valid' => false,
                'reason' => 'Format email tidak valid',
            ];
        }

        return [
            'name' => $name,
            'identity_id' => $identityId,
            'email' => $email,
            'raw' => $line,
            'valid' => true,
            'reason' => null,
        ];
    }

    public function studentEmail(string $identityId): string
    {
        return strtolower($identityId) . '@' . self::STUDENT_EMAIL_DOMAIN;
    }
}