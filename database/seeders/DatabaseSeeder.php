<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default test user matching mst_user schema
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'u0001'],
            [
                'id' => 'u0001',
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'identity_id' => 'u0001',
                'user_type' => '001',
                'unit_id' => '0001',
                'is_active' => '1',
                'created_at' => now(),
            ]
        );

        // MasterData roles and default assignment
        $this->call(\Database\Seeders\RoleSeeder::class);
        $this->call(\Database\Seeders\AssignRoleToFirstUserSeeder::class);
    }
}
