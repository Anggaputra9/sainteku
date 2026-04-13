<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenTingkatSeeder extends Seeder
{
    public function run()
    {
        $tingkat = [
            ['nama' => 'Internasional', 'slug' => 'internasional', 'is_active' => 1],
            ['nama' => 'Nasional', 'slug' => 'nasional', 'is_active' => 1],
            ['nama' => 'Regional', 'slug' => 'regional', 'is_active' => 1],
            ['nama' => 'Lokal', 'slug' => 'lokal', 'is_active' => 1],
        ];

        foreach ($tingkat as $item) {
            DB::table('dosen_tingkat')->updateOrInsert(
                ['slug' => $item['slug']],
                $item
            );
        }

        $this->command->info('Data tingkat dosen berhasil di-seed!');
    }
}
