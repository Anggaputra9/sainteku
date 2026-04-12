<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterDataDummySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // 1. Insert Periode Akademik (mst_period)
        DB::table('mst_period')->insert([
            ['id' => 1, 'name' => '2024/2025', 'semester' => 'Gasal', 'is_active' => '1', 'created_at' => $now],
            ['id' => 2, 'name' => '2024/2025', 'semester' => 'Genap', 'is_active' => '0', 'created_at' => $now],
            ['id' => 3, 'name' => '2025/2026', 'semester' => 'Gasal', 'is_active' => '0', 'created_at' => $now],
            ['id' => 4, 'name' => '2025/2026', 'semester' => 'Genap', 'is_active' => '0', 'created_at' => $now],
            ['id' => 5, 'name' => '2026/2027', 'semester' => 'Gasal', 'is_active' => '0', 'created_at' => $now],
            ['id' => 6, 'name' => '2026/2027', 'semester' => 'Genap', 'is_active' => '0', 'created_at' => $now],
        ]);

        // 2. Insert Data CPMK (mst_cpmk)
        // Kita bikin beberapa CPMK umum untuk dipetakan ke soal
        DB::table('mst_cpmk')->insert([
            ['id' => 'CP-01', 'name' => 'Mampu memahami konsep dasar', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'CP-02', 'name' => 'Mampu mengaplikasikan teori ke dalam studi kasus', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'CP-03', 'name' => 'Mampu menganalisis masalah secara kritis', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'CP-04', 'name' => 'Mampu merancang solusi berbasis teknologi', 'is_active' => '1', 'created_at' => $now],
        ]);

        // 3. Insert Data Mata Kuliah (mst_course)
        // Catatan: unit_id '0001' merujuk pada Fakultas Teknologi Informasi yang sudah ada di databasemu
        // Data Mata Kuliah
        $courses = [
            ['id' => 'MK001', 'name' => 'Desain Antarmuka Pengguna (UI/UX)', 'unit' => 'U003'],
            ['id' => 'MK002', 'name' => 'Pemrograman Web Lanjut', 'unit' => 'U003'],
            ['id' => 'MK003', 'name' => 'Basis Data Relasional', 'unit' => 'U003'],
            ['id' => 'MK004', 'name' => 'Rekayasa Perangkat Lunak', 'unit' => 'U003'],
            ['id' => 'MK005', 'name' => 'Kecerdasan Buatan', 'unit' => 'U003'],
            ['id' => 'MK006', 'name' => 'Jaringan Komputer', 'unit' => 'U003'],
            ['id' => 'MK007', 'name' => 'Manajemen Proyek TI', 'unit' => 'U003'],
            ['id' => 'MK008', 'name' => 'Keamanan Siber', 'unit' => 'U003'],
        ];

        foreach ($courses as $c) {
            DB::table('mst_course')->updateOrInsert(
                ['id' => $c['id']], // Cari berdasarkan ID Mata Kuliah
                [
                    'course_name' => $c['name'],
                    'unit_id'     => $c['unit'], // PASTIKAN PAKAI 'U003'
                    'is_active'   => '1',
                    'created_at'  => now(),
                ]
            );
        }
    }
}