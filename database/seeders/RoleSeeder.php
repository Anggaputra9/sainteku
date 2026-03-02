<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_code' => 'ADM', 'role_name' => 'Administrator', 'is_active' => '1'],
            ['role_code' => 'DKN', 'role_name' => 'Dekanat', 'is_active' => '1'],
            ['role_code' => 'GKM', 'role_name' => 'Gugus Kendali Mutu (GKM)', 'is_active' => '1'],
            ['role_code' => 'KPD', 'role_name' => 'Kaprodi', 'is_active' => '1'],
            ['role_code' => 'DSN', 'role_name' => 'Dosen', 'is_active' => '1'],
            ['role_code' => 'RVI', 'role_name' => 'Reviewer Internal', 'is_active' => '1'],
            ['role_code' => 'RVE', 'role_name' => 'Reviewer Eksternal', 'is_active' => '1'],
            ['role_code' => 'OPS', 'role_name' => 'Operator / Admin Unit', 'is_active' => '1'],
            ['role_code' => 'MHS', 'role_name' => 'Mahasiswa', 'is_active' => '1'],
        ];

        foreach ($roles as $r) {
            // Gunakan role_code sebagai patokan pencarian (karena unik)
            DB::table('mst_role')->updateOrInsert(
                ['role_code' => $r['role_code']], // Cari berdasarkan ini
                $r // Jika ketemu diupdate, jika tidak maka diinsert
            );
        }
    }
}
