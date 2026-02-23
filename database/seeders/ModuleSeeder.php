<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            ['id' => 1, 'module_code' => 'DOC_REP', 'module_name' => 'DREP', 'description' => 'Repository Dokumen'],
            ['id' => 2, 'module_code' => 'RVW_SL', 'module_name' => 'RVWS', 'description' => 'Review Soal'],
            ['id' => 3, 'module_code' => 'MON_PK', 'module_name' => 'MONP', 'description' => 'Monev Perkuliahan'],
            ['id' => 4, 'module_code' => 'AMI', 'module_name' => 'AMI', 'description' => 'Audit Mutu Internal'],
            ['id' => 5, 'module_code' => 'PRS', 'module_name' => 'PRES', 'description' => 'Prestasi Mahasiswa'],
            ['id' => 6, 'module_code' => 'PPEPP', 'module_name' => 'PPEP', 'description' => 'PPEPP'],
        ];

        foreach ($modules as $m) {
            DB::table('mst_module')->updateOrInsert(['id' => $m['id']], $m);
        }
    }
}
