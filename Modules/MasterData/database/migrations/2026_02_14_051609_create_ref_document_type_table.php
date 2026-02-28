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
        Schema::create('ref_document_type', function (Blueprint $table) {
            $table->string('id', 4)->primary();
            $table->string('description', 100);
            $table->string('category', 4);

            $table->foreign('category')
                ->references('id')->on('ref_document_category');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_document_type');
    }
};
