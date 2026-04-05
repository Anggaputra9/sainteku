<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trx_exam_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->string('reviewer_id', 50); // Kaprodi
            $table->text('comment');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('proposal_id')->references('id')->on('trx_exam_proposals')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('mst_user')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_exam_reviews');
    }
};