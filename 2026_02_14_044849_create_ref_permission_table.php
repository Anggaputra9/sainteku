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
        Schema::create('ref_permission', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('permission_code', 10);
            $table->string('permission_name', 50);
            $table->string('description', 55)->nullable();

            $table->unique('permission_code');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_permission');
    }
};
