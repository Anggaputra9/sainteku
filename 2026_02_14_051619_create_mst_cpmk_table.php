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
        Schema::create('mst_cpmk', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('name', 100);
            $table->enum('is_active', ['0', '1']);
            $table->dateTime('created_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_cpmk');
    }
};
