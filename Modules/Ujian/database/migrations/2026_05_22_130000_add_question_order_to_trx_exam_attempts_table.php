<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tambah kolom `question_order` ke trx_exam_attempts.
     *
     * Tujuan: mengunci urutan soal (terutama saat shuffle aktif) sejak
     * attempt pertama kali dibuat. Tanpa kolom ini, setiap kali mahasiswa
     * me-refresh halaman pengerjaan, soal akan diacak ulang.
     *
     * Format: JSON array berisi question_id dengan urutan yang sudah
     * ditentukan, contoh: [12, 3, 27, 5, ...].
     */
    public function up(): void
    {
        Schema::table('trx_exam_attempts', function (Blueprint $table) {
            $table->json('question_order')
                ->nullable()
                ->after('user_agent')
                ->comment('Urutan question_id yang dikunci untuk attempt ini (mendukung shuffle stabil).');
        });
    }

    public function down(): void
    {
        Schema::table('trx_exam_attempts', function (Blueprint $table) {
            $table->dropColumn('question_order');
        });
    }
};
