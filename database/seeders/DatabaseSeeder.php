<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. PANGGIL REFERENSI DASAR (Wajib Pertama)
        $this->call([
            UnitTypeSeeder::class,
            RefUserTypeSeeder::class,
            DocumentTypeSeeder::class,
            InventoryTypeSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            ModuleSeeder::class,
            MenuSeeder::class,
        ]);

        // 2. ISI TIPE UNIT (description)
        DB::table('ref_unit_type')->updateOrInsert(['id' => 1], ['description' => 'Univ']);
        DB::table('ref_unit_type')->updateOrInsert(['id' => 2], ['description' => 'Fakultas']);
        DB::table('ref_unit_type')->updateOrInsert(['id' => 3], ['description' => 'Prodi']);

        // 3. LEVEL 1 - Universitas (Root)
        DB::table('mst_unit')->updateOrInsert(
            ['id' => 'U001'],
            [
                'unit_name' => 'UIN Prof. K.H. Saifuddin Zuhri',
                'unit_parent' => 'U001', 
                'unit_type_id' => 1,
                'is_active' => '1',
                'created_at' => now(),
            ]
        );

        // 4. LEVEL 2 - Fakultas
        DB::table('mst_unit')->updateOrInsert(
            ['id' => 'U002'],
            [
                'unit_name' => 'Fakultas Sains dan Teknologi',
                'unit_parent' => 'U001', 
                'unit_type_id' => 2,
                'is_active' => '1',
                'created_at' => now(),
            ]
        );

        // 5. LEVEL 3 - Program Studi
        $prodies = [
            ['id' => 'U003', 'name' => 'Informatika'],
            ['id' => 'U004', 'name' => 'Arsitektur'],
            ['id' => 'U005', 'name' => 'Ilmu Lingkungan'],
            ['id' => 'U006', 'name' => 'Perpustakaan dan Sains Informasi'],
        ];

        foreach ($prodies as $prodi) {
            DB::table('mst_unit')->updateOrInsert(
                ['id' => $prodi['id']],
                [
                    'unit_name' => $prodi['name'],
                    'unit_parent' => 'U002', 
                    'unit_type_id' => 3,
                    'is_active' => '1',
                    'created_at' => now(),
                ]
            );
        }

        // 6. PANGGIL SEEDER RELASI (Setelah unit & role ada)
        $this->call([
            RolePermissionSeeder::class,
            MasterDataDummySeeder::class, 
            AssignRoleToFirstUserSeeder::class,
        ]);

        // 7. SETUP USER (Admin, Arifian, Anas)
        
        // Ambil ID Role otomatis dari database
        $roleAdminId   = DB::table('mst_role')->where('role_code', 'ADM')->value('id');
        $roleDosenId   = DB::table('mst_role')->where('role_code', 'DSN')->value('id');
        $roleKaprodiId = DB::table('mst_role')->where('role_code', 'KPD')->value('id');

        // --- USER 1: ADMIN ---
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'U0001'],
            [
                'name' => 'Admin Sainteku',
                'email' => 'admin@sainteku.ac.id',
                'password' => Hash::make('password'),
                'identity_id' => '19900101001', // Tambahin identity_id biar gak error
                'user_type' => 'STF', 
                'unit_id' => 'U001', 
                'is_active' => '1',
                'created_at' => now(),
            ]
        );
        if($roleAdminId) {
            DB::table('trx_user_role')->updateOrInsert(['user_id' => 'U0001'], ['role_id' => $roleAdminId]);
        }

        // --- USER 2: ARIFIAN (Dosen) ---
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'U0002'],
            [
                'name' => 'Arifian Ilham Nurriandana',
                'email' => 'arifianilhamnurriandana@gmail.com',
                'password' => Hash::make('Argtgbgt'),
                'identity_id' => '19950505002', 
                'user_type' => 'DSN', 
                'unit_id' => 'U003',  
                'is_active' => '1',
                'created_at' => now(),
            ]
        );
        if($roleDosenId) {
            DB::table('trx_user_role')->updateOrInsert(['user_id' => 'U0002'], ['role_id' => $roleDosenId]);
        }

        // --- USER 3: ANAS AZIMI (Kaprodi) ---
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'U0003'],
            [
                'name' => 'Anas Azimi Qalban',
                'email' => 'anas@uinsaizu.ac.id',
                'password' => Hash::make('kaprodi'),
                'identity_id' => '19880808003', 
                'user_type' => 'DSN', 
                'unit_id' => 'U003', 
                'is_active' => '1',
                'created_at' => now(),
            ]
        );
        if($roleKaprodiId) {
            DB::table('trx_user_role')->updateOrInsert(['user_id' => 'U0003'], ['role_id' => $roleKaprodiId]);
        }

        $this->command->info('🔥 Selesai cuy! Admin, Dosen, dan Kaprodi sudah siap tempur!');
    }
}