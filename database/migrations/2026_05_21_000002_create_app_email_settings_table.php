<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tabel konfigurasi multi-provider email untuk admin.
     * Mendukung SMTP (PHPMailer-style), Brevo, SendGrid, Resend, Mailgun, Mailtrap, dll.
     * Tiap baris = 1 konfigurasi (boleh punya banyak API key, dipakai sebagai pool/failover).
     */
    public function up(): void
    {
        Schema::create('app_email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Label, contoh: Brevo Utama');
            $table->string('provider', 30)->comment('smtp, brevo, sendgrid, resend, mailgun, mailtrap, postmark');

            // Identitas pengirim
            $table->string('from_email', 150)->nullable();
            $table->string('from_name', 150)->nullable();

            // Konfigurasi SMTP (untuk provider=smtp atau yang punya endpoint SMTP)
            $table->string('host', 150)->nullable();
            $table->integer('port')->nullable();
            $table->string('username', 200)->nullable();
            $table->text('password')->nullable()->comment('Encrypted');
            $table->string('encryption', 10)->nullable()->comment('tls / ssl / null');

            // Konfigurasi API (untuk provider berbasis API)
            $table->text('api_key')->nullable()->comment('Encrypted');
            $table->string('api_domain', 150)->nullable()->comment('Mailgun domain, dll');
            $table->text('api_secret')->nullable()->comment('Encrypted, opsional');

            // Limit harian & tracking
            $table->unsignedInteger('daily_limit')->default(0)->comment('0 = unlimited');
            $table->unsignedInteger('daily_sent')->default(0);
            $table->date('last_reset_date')->nullable();
            $table->timestamp('last_used_at')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('priority')->default(0)->comment('Urutan pakai, kecil = duluan');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'is_default']);
            $table->index('provider');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_email_settings');
    }
};
