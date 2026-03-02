<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitTypeSeeder extends Seeder
{
    public function run(): void
    {
        $unitTypes = [
            ['id' => 1, 'description' => 'Universitas'],
            ['id' => 2, 'description' => 'Fakultas'],
            ['id' => 3, 'description' => 'Program Studi'], // Sekarang aman pakai nama panjang!
            ['id' => 4, 'description' => 'Lembaga / UPT'],
        ];

        foreach ($unitTypes as $type) {
            DB::table('ref_unit_type')->updateOrInsert(
                ['id' => $type['id']],
                $type
            );
        }
    }
}