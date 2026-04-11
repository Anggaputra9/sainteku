<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trx_exam_question_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id'); // Nyambung ke pengajuan
            $table->integer('order_no'); // Nyambung ke nomor soal ke-berapa

            // UBAH JADI STRING BIAR 'u0002' BISA MASUK CUY!
            $table->string('user_id');

            $table->string('type'); // Jenis log: 'Komentar', 'Ubah Bobot', 'Ubah CPMK', dll
            $table->text('message'); // Isi pesannya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // BENERIN NAMA TABELNYA JUGA BIAR GAK NYANGKUT PAS DI-ROLLBACK
        Schema::dropIfExists('trx_exam_question_logs');
    }
};