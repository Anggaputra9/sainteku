<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['id' => 1, 'permission_code' => 'C', 'permission_name' => 'Create', 'description' => 'Input / Upload data'],
            ['id' => 2, 'permission_code' => 'R', 'permission_name' => 'Read', 'description' => 'View data'],
            ['id' => 3, 'permission_code' => 'U', 'permission_name' => 'Update', 'description' => 'Edit data'],
            ['id' => 4, 'permission_code' => 'D', 'permission_name' => 'Delete', 'description' => 'Delete data'],
            ['id' => 5, 'permission_code' => 'A', 'permission_name' => 'Approve', 'description' => 'Final approval'],
            ['id' => 6, 'permission_code' => 'V', 'permission_name' => 'Validate', 'description' => 'Review / komentar'],
            ['id' => 7, 'permission_code' => 'E', 'permission_name' => 'Export', 'description' => 'Export / generate report'],
        ];

        foreach ($permissions as $p) {
            DB::table('ref_permission')->updateOrInsert(['id' => $p['id']], $p);
        }
    }
}
