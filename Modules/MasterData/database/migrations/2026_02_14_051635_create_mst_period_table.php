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
        Schema::create('mst_period', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('name', 15);
            $table->string('semester', 5);
            $table->enum('is_active', ['0', '1']);
            $table->dateTime('created_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_period');
    }
};
