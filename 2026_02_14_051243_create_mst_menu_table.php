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
        Schema::create('mst_menu', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('menu_name', 50);
            $table->string('menu_link', 50);
            $table->string('menu_icon', 50);
            $table->integer('is_main_menu');
            $table->enum('is_active', ['0', '1']);
            $table->dateTime('created_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_menu');
    }
};
