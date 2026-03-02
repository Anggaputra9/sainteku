<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefUserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTypes = [
            ['id' => 'DSN', 'description' => 'Dosen'],
            ['id' => 'STF', 'description' => 'Staff'],
            ['id' => 'MHS', 'description' => 'Mahasiswa'],
            ['id' => 'EKS', 'description' => 'Eksternal'],
        ];

        // Menggunakan updateOrInsert agar tidak error duplicate jika dijalankan berkali-kali
        foreach ($userTypes as $type) {
            DB::table('ref_user_type')->updateOrInsert(
                ['id' => $type['id']],
                ['description' => $type['description']]
            );
        }
    }
}