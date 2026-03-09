<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Isi Tabel Referensi (Level 0 - Tidak punya FK)
        DB::table('ref_unit_type')->updateOrInsert(['id' => 1], ['description' => 'FAKULTAS']);
        DB::table('ref_user_type')->updateOrInsert(['id' => '001'], ['description' => 'DOSEN']);
        DB::table('ref_user_type')->updateOrInsert(['id' => '002'], ['description' => 'STAFF']);
        DB::table('ref_user_type')->updateOrInsert(['id' => '003'], ['description' => 'MAHASISWA']);
        DB::table('ref_user_type')->updateOrInsert(['id' => '004'], ['description' => 'EXTERNAL']);

        // 2. Panggil Seeder Master Dasar
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class, // Pastikan isinya sudah disingkat (max 4 char)
            ModuleSeeder::class,
        ]);

        // 3. Buat Unit (Level 1)
        // unit_parent diisi sama dengan ID-nya sendiri jika dia adalah root/puncak
        DB::table('mst_unit')->updateOrInsert(
            ['id' => '0001'],
            [
                'id' => '0001',
                'unit_name' => 'Fakultas Teknologi Informasi',
                'unit_parent' => '0001',
                'unit_type_id' => 1,
                'is_active' => '1',
                'created_at' => now(),
                ]
                );
                
        Schema::disableForeignKeyConstraints();
        // 4. Buat User (Level 2 - Butuh unit_id & user_type)
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'u0001'],
            [
                'id' => 'U0001',
                'name' => 'Admin Sainteku',
                'email' => 'admin@sainteku.ac.id',
                'password' => Hash::make('password'),
                'identity_id' => '12345678',
                'user_type' => '001', // Merujuk ke ref_user_type
                'unit_id' => '0001',   // Merujuk ke mst_unit
                'is_active' => '1',
                'created_at' => now(),
            ]
        );

        DB::table('trx_user_role')->where('user_id', 'u0001')->update([
            'user_id' => 'U0001'
        ]);

        // 5. Panggil Seeder Relasi (Level 3)
        $this->call([
            RolePermissionSeeder::class,
            AssignRoleToFirstUserSeeder::class,
            MenuSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
