<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Perluas kolom deskripsi CPMK agar tidak dibatasi 100 karakter.
     * Perubahan ini aman: hanya memperbesar kapasitas, data existing tidak diubah.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE mst_cpmk MODIFY name TEXT NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE mst_cpmk MODIFY name VARCHAR(100) NOT NULL');
    }
};