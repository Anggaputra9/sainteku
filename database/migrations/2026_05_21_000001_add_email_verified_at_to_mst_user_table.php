<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tambah kolom email_verified_at pada tabel mst_user.
     * Migration ini dibuat terpisah agar data lama (existing records)
     * tidak rusak. Default null sehingga user yang sudah ada tetap valid.
     */
    public function up(): void
    {
        Schema::table('mst_user', function (Blueprint $table) {
            if (!Schema::hasColumn('mst_user', 'email_verified_at')) {
                $table->timestamp('email_verified_at')
                    ->nullable()
                    ->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mst_user', function (Blueprint $table) {
            if (Schema::hasColumn('mst_user', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
};
