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
            $table->foreignId('user_id'); // Siapa yang ngasih komen/ngubah (bisa ditarik ke mst_user)
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
        Schema::dropIfExists('exam_question_logs');
    }
};
