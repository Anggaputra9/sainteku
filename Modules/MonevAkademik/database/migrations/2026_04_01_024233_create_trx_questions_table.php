<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trx_questions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('course_id', 5);
            $table->json('cpmk_id');
            $table->text('question_text');
            $table->string('image_path')->nullable();
            $table->string('created_by', 50);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('course_id')->references('id')->on('mst_course')->onDelete('restrict');
            $table->foreign('cpmk_id')->references('id')->on('mst_cpmk')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('mst_user')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_questions');
    }
};