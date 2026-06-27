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
            ['id' => 'UIN'],
            [
                'unit_name' => 'UIN Prof. K.H. Saifuddin Zuhri',
                'unit_parent' => 'UIN',
                'unit_type_id' => 1,
                'is_active' => '1',
                'created_at' => now(),
            ]
        );

        // 4. LEVEL 2 - Fakultas
        DB::table('mst_unit')->updateOrInsert(
            ['id' => 'FST'],
            [
                'unit_name' => 'Fakultas Sains dan Teknologi',
                'unit_parent' => 'UIN',
                'unit_type_id' => 2,
                'is_active' => '1',
                'created_at' => now(),
            ]
        );

        // 5. LEVEL 3 - Program Studi
        $prodies = [
            ['id' => 'INF', 'name' => 'Informatika'],
            ['id' => 'ARS', 'name' => 'Arsitektur'],
            ['id' => 'ILK', 'name' => 'Ilmu Lingkungan'], // ILK: Ilmu + Lingkungan
            ['id' => 'PSI', 'name' => 'Perpustakaan dan Sains Informasi'],
        ];

        foreach ($prodies as $prodi) {
            DB::table('mst_unit')->updateOrInsert(
                ['id' => $prodi['id']],
                [
                    'unit_name' => $prodi['name'],
                    'unit_parent' => 'FST',
                    'unit_type_id' => 3,
                    'is_active' => '1',
                    'created_at' => now(),
                ]
            );
        }

        // 6. PANGGIL SEEDER RELASI (Setelah unit & role ada)
        $this->call([
            RolePermissionSeeder::class,
            //MasterDataDummySeeder::class,
            AssignRoleToFirstUserSeeder::class,
        ]);

        // 7. SETUP USER (Admin, Arifian, Anas)

        // Ambil ID Role otomatis dari database
        $roleAdminId   = DB::table('mst_role')->where('role_code', 'ADM')->value('id');
        $roleDosenId   = DB::table('mst_role')->where('role_code', 'DSN')->value('id');
        $roleKaprodiId = DB::table('mst_role')->where('role_code', 'KPD')->value('id');
        $roleMhsId     = DB::table('mst_role')->where('role_code', 'MHS')->value('id');

        // --- USER 1: ADMIN ---
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'ADM-UIN-0000001'],
            [
                'name' => 'Admin Sainteku',
                'email' => 'admin@sainteku.ac.id',
                'password' => Hash::make('password'),
                'identity_id' => '19900101001',
                'user_type' => 'STF',
                'unit_id' => 'UIN',
                'is_active' => '1',
                'created_at' => now(),
            ]
        );
        if ($roleAdminId) {
            DB::table('trx_user_role')->updateOrInsert(
                ['user_id' => 'ADM-UIN-0000001', 'role_id' => $roleAdminId],
                ['user_id' => 'ADM-UIN-0000001', 'role_id' => $roleAdminId]
            );
        }

        // --- USER 2: ARIFIAN (Dosen) ---
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'DSN-INF-0000001'],
            [
                'name' => 'Arifian Ilham Nurriandana',
                'email' => 'arifianilhamnurriandana@gmail.com',
                'password' => Hash::make('Argtgbgt'),
                'identity_id' => '19950505002',
                'user_type' => 'DSN',
                'unit_id' => 'INF',
                'is_active' => '1',
                'created_at' => now(),
            ]
        );
        if ($roleDosenId) {
            DB::table('trx_user_role')->updateOrInsert(
                ['user_id' => 'DSN-INF-0000001', 'role_id' => $roleDosenId],
                ['user_id' => 'DSN-INF-0000001', 'role_id' => $roleDosenId]
            );
        }

        // --- USER 3: ANAS AZIMI (Kaprodi) ---
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'KPD-INF-0000001'],
            [
                'name' => 'Anas Azimi Qalban',
                'email' => 'anas@uinsaizu.ac.id',
                'password' => Hash::make('kaprodi'),
                'identity_id' => '19880808003',
                'user_type' => 'DSN',
                'unit_id' => 'INF',
                'is_active' => '1',
                'created_at' => now(),
            ]
        );
        if ($roleKaprodiId) {
            DB::table('trx_user_role')->updateOrInsert(
                ['user_id' => 'KPD-INF-0000001', 'role_id' => $roleKaprodiId],
                ['user_id' => 'KPD-INF-0000001', 'role_id' => $roleKaprodiId]
            );
        }

        // --- USER 4: NIAMILAH (Mahasiswa) ---
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'MHS-INF-0000001'],
            [
                'name' => 'Niamilah Nabil Syahputra',
                'email' => 'niamilah@uinsaizu.ac.id',
                'password' => Hash::make('password'),
                'identity_id' => '234110601087',
                'user_type' => 'MHS',
                'unit_id' => 'INF',
                'is_active' => '1',
                'created_at' => now(),
            ]
        );
        if ($roleMhsId) {
            DB::table('trx_user_role')->updateOrInsert(
                ['user_id' => 'MHS-INF-0000001', 'role_id' => $roleMhsId],
                ['user_id' => 'MHS-INF-0000001', 'role_id' => $roleMhsId]
            );
        }

        // --- USER 4: ANGGA PUTRA PRATAMA (Mahasiswa) ---
        DB::table('mst_user')->updateOrInsert(
            ['id' => 'U0005'],
            [
                'name' => 'Angga Putra Pratama',
                'email' => 'anggapratama@uinsaizu.ac.id',
                'password' => Hash::make('password'),
                'identity_id' => '234110601087',                
                'user_type' => 'MHS',                
                'unit_id' => 'U003',
                'is_active' => '1',
                'created_at' => now(),
            ]
        );
        if ($roleMhsId) {
            DB::table('trx_user_role')->updateOrInsert(
                ['user_id' => 'U0005', 'role_id' => $roleMhsId],
                ['user_id' => 'U0005', 'role_id' => $roleMhsId]
            );
        }
        
        // ==================================================
        // 8. MASTER DATA PRESTASI MAHASISWA (TAMBAHAN)
        // ==================================================

        // Jenis Prestasi Mahasiswa
        $achievementTypes = [
            ['id' => 1, 'description' => 'Olahraga', 'is_active' => '1'],
            ['id' => 2, 'description' => 'Seni dan Budaya', 'is_active' => '1'],
            ['id' => 3, 'description' => 'Sains dan Teknologi', 'is_active' => '1'],
            ['id' => 4, 'description' => 'Sosial dan Kemanusiaan', 'is_active' => '1'],
            ['id' => 5, 'description' => 'Kewirausahaan', 'is_active' => '1'],
            ['id' => 6, 'description' => 'Debat dan Bahasa', 'is_active' => '1'],
            ['id' => 7, 'description' => 'Jurnalistik dan Media', 'is_active' => '1'],
            ['id' => 8, 'description' => 'Kepemimpinan dan Organisasi', 'is_active' => '1'],
            ['id' => 9, 'description' => 'Keagamaan dan Kerohanian', 'is_active' => '1'],
        ];

        foreach ($achievementTypes as $type) {
            DB::table('mst_achievement_type')->updateOrInsert(
                ['id' => $type['id']],
                $type
            );
        }

        // Tingkat Prestasi Mahasiswa
        $achievementLevels = [
            ['id' => 1, 'description' => 'Internasional', 'is_active' => '1'],
            ['id' => 2, 'description' => 'Nasional', 'is_active' => '1'],
            ['id' => 3, 'description' => 'Provinsi', 'is_active' => '1'],
            ['id' => 4, 'description' => 'Kabupaten/Kota', 'is_active' => '1'],
            ['id' => 5, 'description' => 'Universitas', 'is_active' => '1'],
            ['id' => 6, 'description' => 'Fakultas', 'is_active' => '1'],
        ];

        foreach ($achievementLevels as $level) {
            DB::table('mst_achievement_level')->updateOrInsert(
                ['id' => $level['id']],
                $level
            );
        }

        // ==================================================
        // 9. MASTER DATA PRESTASI DOSEN (TAMBAHAN)
        // ==================================================

        // Kategori Prestasi Dosen
        $dosenKategori = [
            ['nama' => 'Jurnal Ilmiah', 'slug' => 'jurnal-ilmiah', 'is_active' => 1],
            ['nama' => 'Prosiding', 'slug' => 'prosiding', 'is_active' => 1],
            ['nama' => 'HKI/Paten', 'slug' => 'hki-paten', 'is_active' => 1],
            ['nama' => 'Buku', 'slug' => 'buku', 'is_active' => 1],
            ['nama' => 'Pembicara', 'slug' => 'pembicara', 'is_active' => 1],
            ['nama' => 'Produk TTG', 'slug' => 'produk-ttg', 'is_active' => 1],
            ['nama' => 'Visiting Scientist', 'slug' => 'visiting-scientist', 'is_active' => 1],
            ['nama' => 'Penelitian', 'slug' => 'penelitian', 'is_active' => 1],
            ['nama' => 'Pengabdian', 'slug' => 'pengabdian', 'is_active' => 1],
            ['nama' => 'Penghargaan', 'slug' => 'penghargaan', 'is_active' => 1],
        ];

        foreach ($dosenKategori as $kat) {
            DB::table('dosen_kategori')->updateOrInsert(
                ['slug' => $kat['slug']],
                $kat
            );
        }

        // Tingkat Prestasi Dosen
        $dosenTingkat = [
            ['nama' => 'Internasional', 'slug' => 'internasional', 'is_active' => 1],
            ['nama' => 'Nasional', 'slug' => 'nasional', 'is_active' => 1],
            ['nama' => 'Regional', 'slug' => 'regional', 'is_active' => 1],
            ['nama' => 'Lokal', 'slug' => 'lokal', 'is_active' => 1],
        ];

        foreach ($dosenTingkat as $tkt) {
            DB::table('dosen_tingkat')->updateOrInsert(
                ['slug' => $tkt['slug']],
                $tkt
            );
        }

        $this->command->info('🔥 Selesai! Admin, Dosen, Kaprodi, Mahasiswa sudah siap tempur!');
        $this->command->info('📚 Master data prestasi (mahasiswa & dosen) juga sudah di-seed!');
    }
}
