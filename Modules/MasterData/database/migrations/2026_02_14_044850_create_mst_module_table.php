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
        Schema::create('mst_module', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('module_code', 10);
            $table->string('module_name', 4);
            $table->string('description', 50)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_module');
    }
};
