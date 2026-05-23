<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tambah kolom auth_mode untuk membedakan mode autentikasi:
     * - 'smtp' : pakai host/port/username/password (PHPMailer-style)
     * - 'api'  : pakai API key vendor (Brevo, SendGrid, Resend, dll)
     *
     * Dengan kolom ini, form di UI bisa nampilin field yang relevan saja
     * tergantung mode yang dipilih, jadi tidak mubazir.
     */
    public function up(): void
    {
        Schema::table('app_email_settings', function (Blueprint $table) {
            $table->string('auth_mode', 10)
                ->default('smtp')
                ->after('provider')
                ->comment('smtp | api');
        });
    }

    public function down(): void
    {
        Schema::table('app_email_settings', function (Blueprint $table) {
            $table->dropColumn('auth_mode');
        });
    }
};
