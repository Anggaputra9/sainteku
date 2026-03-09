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
        Schema::create('trx_document', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document_id', 10);
            $table->string('document_title', 100);
            $table->string('document_type_id', 4);
            $table->string('unit_id', 4);
            $table->integer('version');
            $table->string('file_path', 255);
            $table->integer('status');
            $table->date('effective_date');
            $table->date('expired_date');
            $table->string('created_by', 20);
            $table->dateTime('created_at')->nullable();

            $table->foreign('document_type_id')
                ->references('id')->on('ref_document_type');

            $table->foreign('unit_id')
                ->references('id')->on('mst_unit');

            $table->foreign('status')
                ->references('id')->on('mst_workflow_status');

            $table->foreign('created_by')
                ->references('id')->on('mst_user');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_document');
    }
};
