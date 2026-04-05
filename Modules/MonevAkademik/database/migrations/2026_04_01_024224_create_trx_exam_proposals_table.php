<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trx_exam_proposals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Untuk URL publik
            $table->bigInteger('period_id');
            $table->string('course_id', 5);
            $table->enum('exam_type', ['UTS', 'UAS']);
            $table->enum('status', ['SUBMITTED', 'REVISED', 'APPROVED'])->default('SUBMITTED');
            $table->string('created_by', 50); // Relasi ke mst_user (Dosen)
            $table->string('approved_by', 50)->nullable(); // Relasi ke mst_user (Kaprodi)
            $table->timestamps();

            // Foreign Keys
            $table->foreign('course_id')->references('id')->on('mst_course')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('mst_user')->onDelete('restrict');
            $table->foreign('approved_by')->references('id')->on('mst_user')->onDelete('set null');
            // Catatan: Pastikan tipe data period_id di mst_period adalah bigInteger
        });
    }

    public function down()
    {
        Schema::dropIfExists('trx_exam_proposals');
    }
};