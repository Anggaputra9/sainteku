<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel token verifikasi email khusus aplikasi.
 *
 * Dibuat terpisah dari password_reset_tokens supaya:
 *  - Bisa di-prune sendiri
 *  - Skema bisa disesuaikan kebutuhan (mis. simpan user_id user yang minta)
 *
 * Token disimpan dalam bentuk hash (sha256) untuk keamanan: token mentah
 * cuma muncul di email user, di DB hanya hash-nya.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('app_email_verifications')) return;

        Schema::create('app_email_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('user_id')->nullable()->index();
            $table->string('token', 128);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_email_verifications');
    }
};
