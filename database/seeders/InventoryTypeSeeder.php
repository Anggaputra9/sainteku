<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryTypeSeeder extends Seeder
{
    /**
     * Jalankan seeder khusus untuk tabel tipe inventaris.
     */
    public function run(): void
    {
        // Daftar kategori sesuai kebutuhan Sainteku
        $types = [
            ['id' => 1, 'code' => 'ATK', 'description' => 'ALAT TULIS KANTOR'],
            ['id' => 2, 'code' => 'ELM', 'description' => 'ELEKTRONIK DAN MULTIMEDIA'],
            ['id' => 3, 'code' => 'FUR', 'description' => 'FURNITURE DAN RUANGAN'],
            ['id' => 4, 'code' => 'KGT', 'description' => 'PERLENGKAPAN KEGIATAN'],
            ['id' => 5, 'code' => 'KND', 'description' => 'KENDARAAN'],
            ['id' => 6, 'code' => 'KBS', 'description' => 'ALAT KEBERSIHAN'],
            ['id' => 7, 'code' => 'GDG', 'description' => 'PRASARANA GEDUNG'],
        ];

        // Gunakan updateOrInsert agar aman dari bentrok Foreign Key
        foreach ($types as $type) {
            DB::table('mst_inventory_type')->updateOrInsert(
                ['id' => $type['id']], // Patokan pencarian data
                [
                    'code' => $type['code'],
                    'description' => $type['description']
                ] // Data yang di-update/insert
            );
        }
    }
}