<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan.
     */
    public function up(): void
    {
        Schema::table('mst_inventory_type', function (Blueprint $table) {
            // Memperbesar kapasitas kolom 'code' menjadi 10 karakter
            $table->string('code', 10)->change();
        });
    }

    /**
     * Kembalikan perubahan jika di-rollback.
     */
    public function down(): void
    {
        Schema::table('mst_inventory_type', function (Blueprint $table) {
            $table->string('code', 2)->change();
        });
    }
};