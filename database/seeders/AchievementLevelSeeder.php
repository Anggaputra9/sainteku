<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementLevelSeeder extends Seeder
{
    public function run()
    {
        $levels = [
            ['id' => 1, 'description' => 'Internasional', 'is_active' => '1'],
            ['id' => 2, 'description' => 'Nasional', 'is_active' => '1'],
            ['id' => 3, 'description' => 'Provinsi', 'is_active' => '1'],
            ['id' => 4, 'description' => 'Kabupaten/Kota', 'is_active' => '1'],
            ['id' => 5, 'description' => 'Universitas', 'is_active' => '1'],
            ['id' => 6, 'description' => 'Fakultas', 'is_active' => '1'],
        ];

        foreach ($levels as $level) {
            DB::table('mst_achievement_level')->updateOrInsert(
                ['id' => $level['id']],
                $level
            );
        }

        $this->command->info('Data tingkat prestasi mahasiswa berhasil di-seed!');
    }
}
