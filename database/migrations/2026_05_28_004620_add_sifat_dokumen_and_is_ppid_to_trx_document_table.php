<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trx_document', function (Blueprint $table) {
            // Sifat Dokumen: Publik atau Private
            $table->enum('sifat_dokumen', ['Publik', 'Private'])
                  ->default('Private')
                  ->after('status')
                  ->comment('Menentukan apakah dokumen dapat dilihat publik atau terbatas');
            
            // Ceklist PPID: Apakah dokumen masuk kategori informasi publik PPID
            $table->boolean('is_ppid')
                  ->default(false)
                  ->after('sifat_dokumen')
                  ->comment('Menandai apakah dokumen masuk kategori informasi publik PPID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_document', function (Blueprint $table) {
            $table->dropColumn(['sifat_dokumen', 'is_ppid']);
        });
    }
};
