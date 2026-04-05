<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trx_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('question_id');
            $table->integer('order_no'); // Nomor urut soal di ujian ini
            $table->integer('weight'); // Bobot persentase nilai (total harus 100)
            $table->timestamps();

            // Foreign Keys
            $table->foreign('proposal_id')->references('id')->on('trx_exam_proposals')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('trx_questions')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_exam_questions');
    }
};