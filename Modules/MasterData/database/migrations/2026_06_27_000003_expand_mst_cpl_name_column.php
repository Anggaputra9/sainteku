<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perluas kolom deskripsi CPL agar tidak dibatasi 255 karakter.
     * Perubahan ini aman: hanya memperbesar kapasitas, data existing tidak diubah.
     */
    public function up(): void
    {
        if (! Schema::hasTable('mst_cpl')) {
            return;
        }

        DB::statement('ALTER TABLE mst_cpl MODIFY name TEXT NOT NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('mst_cpl')) {
            return;
        }

        DB::statement('ALTER TABLE mst_cpl MODIFY name VARCHAR(255) NOT NULL');
    }
};