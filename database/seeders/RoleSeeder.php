<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'role_code' => 'SA', 'role_name' => 'super-admin', 'is_active' => '1'],
            ['id' => 2, 'role_code' => 'AD', 'role_name' => 'admin', 'is_active' => '1'],
            ['id' => 3, 'role_code' => 'US', 'role_name' => 'user', 'is_active' => '1'],
        ];

        foreach ($roles as $r) {
            DB::table('mst_role')->updateOrInsert(['id' => $r['id']], $r);
        }
    }
}
