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
        $courses = [
            ['id' => 'MK001', 'course_name' => 'Desain Antarmuka Pengguna (UI/UX)', 'unit_id' => '0001', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'MK002', 'course_name' => 'Pemrograman Web Lanjut', 'unit_id' => '0001', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'MK003', 'course_name' => 'Basis Data Relasional', 'unit_id' => '0001', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'MK004', 'course_name' => 'Rekayasa Perangkat Lunak', 'unit_id' => '0001', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'MK005', 'course_name' => 'Kecerdasan Buatan', 'unit_id' => '0001', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'MK006', 'course_name' => 'Jaringan Komputer', 'unit_id' => '0001', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'MK007', 'course_name' => 'Manajemen Proyek TI', 'unit_id' => '0001', 'is_active' => '1', 'created_at' => $now],
            ['id' => 'MK008', 'course_name' => 'Keamanan Siber', 'unit_id' => '0001', 'is_active' => '1', 'created_at' => $now],
        ];

        DB::table('mst_course')->insert($courses);
    }
}