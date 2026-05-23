<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * trx_exam_attempt_answers = jawaban mahasiswa per soal (essay).
     * Disimpan dengan auto-save tiap kali user berhenti ngetik.
     */
    public function up(): void
    {
        Schema::create('trx_exam_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('question_id');

            $table->longText('answer_text')->nullable();
            $table->boolean('is_answered')->default(false)
                ->comment('true kalau answer_text tidak kosong (untuk progress cepat)');

            $table->decimal('score', 5, 2)->nullable()->comment('Nilai per soal (manual grading)');
            $table->text('grader_note')->nullable();

            $table->timestamps();

            $table->foreign('attempt_id')->references('id')->on('trx_exam_attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('trx_questions')->onDelete('restrict');

            $table->unique(['attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_exam_attempt_answers');
    }
};
