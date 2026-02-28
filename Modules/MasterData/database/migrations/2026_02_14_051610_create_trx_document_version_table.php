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
        Schema::create('trx_document_version', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_id');
            $table->integer('version');
            $table->string('file_path', 50);
            $table->string('change_note', 200);
            $table->string('approved_by', 20);
            $table->dateTime('approved_date')->nullable();

            $table->foreign('document_id')
                ->references('id')
                ->on('trx_document')
                ->onDelete('cascade');

            $table->foreign('approved_by')
                ->references('id')->on('mst_user');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_document_version');
    }
};
