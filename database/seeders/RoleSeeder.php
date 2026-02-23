<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'role_code' => 'ADM', 'role_name' => 'Administrator', 'is_active' => '1'],
            ['id' => 2, 'role_code' => 'DKN', 'role_name' => 'Dekanat', 'is_active' => '1'],
            ['id' => 3, 'role_code' => 'GKM', 'role_name' => 'Gugus Kendali Mutu (GKM)', 'is_active' => '1'],
            ['id' => 4, 'role_code' => 'KPD', 'role_name' => 'Kaprodi', 'is_active' => '1'],
            ['id' => 5, 'role_code' => 'DSN', 'role_name' => 'Dosen', 'is_active' => '1'],
            ['id' => 6, 'role_code' => 'RVI', 'role_name' => 'Reviewer Internal', 'is_active' => '1'],
            ['id' => 7, 'role_code' => 'RVE', 'role_name' => 'Reviewer Eksternal', 'is_active' => '1'],
            ['id' => 8, 'role_code' => 'OPS', 'role_name' => 'Operator / Admin Unit', 'is_active' => '1'],
            ['id' => 9, 'role_code' => 'MHS', 'role_name' => 'Mahasiswa', 'is_active' => '1'],
        ];

        foreach ($roles as $r) {
            DB::table('mst_role')->updateOrInsert(['id' => $r['id']], $r);
        }
    }
}
