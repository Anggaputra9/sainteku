<?php

namespace Modules\MasterData\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\MasterData\app\Models\User;
use Modules\MasterData\Http\Controllers\AdminController;

class BulkUserImportService
{
    public function __construct(
        private readonly BulkUserParser $parser,
        private readonly UserIdService $userIdService,
    ) {}

    /**
     * @param  list<int>  $roleIds
     * @return array{
     *     success_count: int,
     *     failed_count: int,
     *     failed_text: string,
     *     log: list<array{name: string, identity_id: string, email: string, reason: string}>
     * }
     */
    public function import(
        string $userType,
        string $unitId,
        array $roleIds,
        bool $isActive,
        string $bulkText,
    ): array {
        $unitTambahan = [];
        $this->applyMahasiswaRules($userType, $roleIds, $unitTambahan);

        $parsed = $this->parser->parse($bulkText, $userType);
        $entries = $parsed['entries'];

        $successCount = 0;
        $log = [];
        $failedRaws = [];
        $seenEmails = [];
        $seenIdentities = [];

        $existingEmails = [];
        $existingIdentities = [];

        $candidateEmails = [];
        $candidateIdentities = [];
        foreach ($entries as $entry) {
            if (! $entry['valid']) {
                continue;
            }
            $candidateEmails[] = strtolower($entry['email']);
            if ($entry['identity_id'] !== '') {
                $candidateIdentities[] = $entry['identity_id'];
            }
        }

        if ($candidateEmails !== []) {
            $existingEmails = User::query()
                ->whereIn('email', array_unique($candidateEmails))
                ->pluck('email')
                ->map(fn ($email) => strtolower((string) $email))
                ->flip()
                ->all();
        }

        if ($candidateIdentities !== []) {
            $existingIdentities = User::query()
                ->whereIn('identity_id', array_unique($candidateIdentities))
                ->pluck('identity_id')
                ->flip()
                ->all();
        }

        foreach ($entries as $entry) {
            if (! $entry['valid']) {
                $log[] = $this->logRow($entry, $entry['reason'] ?? 'Format tidak valid');
                $failedRaws[] = $entry['raw'];

                continue;
            }

            $emailKey = strtolower($entry['email']);
            $identityKey = $entry['identity_id'];

            if (isset($seenEmails[$emailKey])) {
                $log[] = $this->logRow($entry, 'Email duplikat dalam batch');
                $failedRaws[] = $entry['raw'];

                continue;
            }

            if ($identityKey !== '' && isset($seenIdentities[$identityKey])) {
                $log[] = $this->logRow($entry, 'NIM/NIP duplikat dalam batch');
                $failedRaws[] = $entry['raw'];

                continue;
            }

            if (isset($existingEmails[$emailKey])) {
                $log[] = $this->logRow($entry, 'Email sudah terdaftar');
                $failedRaws[] = $entry['raw'];

                continue;
            }

            if ($identityKey !== '' && isset($existingIdentities[$identityKey])) {
                $log[] = $this->logRow($entry, 'NIM/NIP sudah terdaftar');
                $failedRaws[] = $entry['raw'];

                continue;
            }

            try {
                DB::transaction(function () use ($entry, $userType, $unitId, $roleIds, $isActive): void {
                    $newId = $this->userIdService->assignIdForNewUser($roleIds, $unitId);

                    $user = User::create([
                        'id' => $newId,
                        'name' => $entry['name'],
                        'email' => strtolower($entry['email']),
                        'password' => Hash::make($entry['identity_id']),
                        'identity_id' => $entry['identity_id'],
                        'user_type' => $userType,
                        'unit_id' => $unitId,
                        'is_active' => $isActive ? '1' : '0',
                    ]);

                    $user->roles()->sync($roleIds);
                });

                $seenEmails[$emailKey] = true;
                if ($identityKey !== '') {
                    $seenIdentities[$identityKey] = true;
                    $existingIdentities[$identityKey] = true;
                }
                $existingEmails[$emailKey] = true;
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

    /**
     * @param  list<int>  $roleIds
     * @param  list<string>  $unitTambahan
     */
    private function applyMahasiswaRules(string $userType, array &$roleIds, array &$unitTambahan): void
    {
        if ($userType !== AdminController::STUDENT_USER_TYPE) {
            return;
        }

        $mahasiswaRoleId = DB::table('mst_role')->where('role_code', 'MHS')->value('id');
        if ($mahasiswaRoleId) {
            $roleIds = [(int) $mahasiswaRoleId];
        }

        $unitTambahan = [];
    }

    /**
     * @param  array{name: string, identity_id: string, email: string, raw: string, valid: bool, reason: string|null}  $entry
     * @return array{name: string, identity_id: string, email: string, reason: string}
     */
    private function logRow(array $entry, string $reason): array
    {
        return [
            'name' => $entry['name'] !== '' ? $entry['name'] : ($entry['raw'] ?: '-'),
            'identity_id' => $entry['identity_id'] !== '' ? $entry['identity_id'] : '-',
            'email' => $entry['email'] !== '' ? $entry['email'] : '-',
            'reason' => $reason,
        ];
    }
}