<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenKategoriSeeder extends Seeder
{
    public function run()
    {
        $kategori = [
            ['nama' => 'Jurnal Ilmiah', 'slug' => 'jurnal-ilmiah', 'is_active' => 1],
            ['nama' => 'Prosiding', 'slug' => 'prosiding', 'is_active' => 1],
            ['nama' => 'HKI/Paten', 'slug' => 'hki-paten', 'is_active' => 1],
            ['nama' => 'Buku', 'slug' => 'buku', 'is_active' => 1],
            ['nama' => 'Pembicara', 'slug' => 'pembicara', 'is_active' => 1],
            ['nama' => 'Produk TTG', 'slug' => 'produk-ttg', 'is_active' => 1],
            ['nama' => 'Visiting Scientist', 'slug' => 'visiting-scientist', 'is_active' => 1],
            ['nama' => 'Penelitian', 'slug' => 'penelitian', 'is_active' => 1],
            ['nama' => 'Pengabdian', 'slug' => 'pengabdian', 'is_active' => 1],
            ['nama' => 'Penghargaan', 'slug' => 'penghargaan', 'is_active' => 1],
        ];

        foreach ($kategori as $item) {
            DB::table('dosen_kategori')->updateOrInsert(
                ['slug' => $item['slug']],
                $item
            );
        }

        $this->command->info('Data kategori dosen berhasil di-seed!');
    }
}
