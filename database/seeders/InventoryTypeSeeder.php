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
        // 1. Hapus data lama agar tidak terjadi penumpukan/duplikasi saat dijalankan ulang
        DB::table('mst_inventory_type')->delete();

        // 2. Insert data isian utamanya
        DB::table('mst_inventory_type')->insert([
            ['id' => 1, 'code' => 'R', 'description' => 'Ruangan'],
            ['id' => 2, 'code' => 'E', 'description' => 'Elektronik / Gadget'],
            ['id' => 3, 'code' => 'F', 'description' => 'Furnitur / Mebel'],
            ['id' => 4, 'code' => 'K', 'description' => 'Kendaraan'],
        ]);
    }
}