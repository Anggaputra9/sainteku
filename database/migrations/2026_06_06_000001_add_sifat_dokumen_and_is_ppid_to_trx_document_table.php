<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trx_document', function (Blueprint $table) {
            if (! Schema::hasColumn('trx_document', 'sifat_dokumen')) {
                $table->enum('sifat_dokumen', ['Publik', 'Private'])
                    ->default('Private')
                    ->after('status')
                    ->comment('Menentukan apakah dokumen dapat dilihat publik atau terbatas');
            }

            if (! Schema::hasColumn('trx_document', 'is_ppid')) {
                $table->boolean('is_ppid')
                    ->default(false)
                    ->after('sifat_dokumen')
                    ->comment('Menandai apakah dokumen masuk kategori informasi publik PPID');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trx_document', function (Blueprint $table) {
            if (Schema::hasColumn('trx_document', 'is_ppid')) {
                $table->dropColumn('is_ppid');
            }
            if (Schema::hasColumn('trx_document', 'sifat_dokumen')) {
                $table->dropColumn('sifat_dokumen');
            }
        });
    }
};