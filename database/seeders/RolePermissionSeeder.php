<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Role-Module-Permission mapping
        $mappings = [
            // Administrator (1) - DOCUMENT_REPOSITORY (1) - C, R, U, D, A, V, E (1,2,3,4,5,6,7)
            ['role_id' => 1, 'modul_id' => 1, 'permission_id' => 1, 'allowed' => true],
            ['role_id' => 1, 'modul_id' => 1, 'permission_id' => 2, 'allowed' => true],
            ['role_id' => 1, 'modul_id' => 1, 'permission_id' => 3, 'allowed' => true],
            ['role_id' => 1, 'modul_id' => 1, 'permission_id' => 4, 'allowed' => true],
            ['role_id' => 1, 'modul_id' => 1, 'permission_id' => 5, 'allowed' => true],
            ['role_id' => 1, 'modul_id' => 1, 'permission_id' => 6, 'allowed' => true],
            ['role_id' => 1, 'modul_id' => 1, 'permission_id' => 7, 'allowed' => true],

            // Dekanat (2) - DOCUMENT_REPOSITORY (1) - R, A, E (2,5,7)
            ['role_id' => 2, 'modul_id' => 1, 'permission_id' => 2, 'allowed' => true],
            ['role_id' => 2, 'modul_id' => 1, 'permission_id' => 5, 'allowed' => true],
            ['role_id' => 2, 'modul_id' => 1, 'permission_id' => 7, 'allowed' => true],

            // Dosen (5) - REVIEW_SOAL (2) - C, R, U (1,2,3)
            ['role_id' => 5, 'modul_id' => 2, 'permission_id' => 1, 'allowed' => true],
            ['role_id' => 5, 'modul_id' => 2, 'permission_id' => 2, 'allowed' => true],
            ['role_id' => 5, 'modul_id' => 2, 'permission_id' => 3, 'allowed' => true],

            // Reviewer Internal (6) - REVIEW_SOAL (2) - R, V (2,6)
            ['role_id' => 6, 'modul_id' => 2, 'permission_id' => 2, 'allowed' => true],
            ['role_id' => 6, 'modul_id' => 2, 'permission_id' => 6, 'allowed' => true],

            // Reviewer Eksternal (7) - REVIEW_SOAL (2) - R, V (2,6)
            ['role_id' => 7, 'modul_id' => 2, 'permission_id' => 2, 'allowed' => true],
            ['role_id' => 7, 'modul_id' => 2, 'permission_id' => 6, 'allowed' => true],

            // Kaprodi (4) - MONEV_PERKULIAHAN (3) - R, A, E (2,5,7)
            ['role_id' => 4, 'modul_id' => 3, 'permission_id' => 2, 'allowed' => true],
            ['role_id' => 4, 'modul_id' => 3, 'permission_id' => 5, 'allowed' => true],
            ['role_id' => 4, 'modul_id' => 3, 'permission_id' => 7, 'allowed' => true],

            // GKM (3) - AMI (4) - C, R, U, A, E (1,2,3,5,7)
            ['role_id' => 3, 'modul_id' => 4, 'permission_id' => 1, 'allowed' => true],
            ['role_id' => 3, 'modul_id' => 4, 'permission_id' => 2, 'allowed' => true],
            ['role_id' => 3, 'modul_id' => 4, 'permission_id' => 3, 'allowed' => true],
            ['role_id' => 3, 'modul_id' => 4, 'permission_id' => 5, 'allowed' => true],
            ['role_id' => 3, 'modul_id' => 4, 'permission_id' => 7, 'allowed' => true],

            // Mahasiswa (9) - PRESTASI (5) - C, R, U (1,2,3)
            ['role_id' => 9, 'modul_id' => 5, 'permission_id' => 1, 'allowed' => true],
            ['role_id' => 9, 'modul_id' => 5, 'permission_id' => 2, 'allowed' => true],
            ['role_id' => 9, 'modul_id' => 5, 'permission_id' => 3, 'allowed' => true],

            // Operator / Admin Unit (8) - PPEPP (6) - C, R, U, D, E (1,2,3,4,7)
            ['role_id' => 8, 'modul_id' => 6, 'permission_id' => 1, 'allowed' => true],
            ['role_id' => 8, 'modul_id' => 6, 'permission_id' => 2, 'allowed' => true],
            ['role_id' => 8, 'modul_id' => 6, 'permission_id' => 3, 'allowed' => true],
            ['role_id' => 8, 'modul_id' => 6, 'permission_id' => 4, 'allowed' => true],
            ['role_id' => 8, 'modul_id' => 6, 'permission_id' => 7, 'allowed' => true],
        ];

        foreach ($mappings as $mapping) {
            DB::table('trx_role_permission')->updateOrInsert(
                ['role_id' => $mapping['role_id'], 'modul_id' => $mapping['modul_id'], 'permission_id' => $mapping['permission_id']],
                $mapping
            );
        }
    }
}
