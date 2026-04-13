<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
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

        foreach ($types as $type) {
            DB::table('mst_achievement_type')->updateOrInsert(
                ['id' => $type['id']],
                $type
            );
        }

        $this->command->info('Data jenis prestasi mahasiswa berhasil di-seed!');
    }
}
