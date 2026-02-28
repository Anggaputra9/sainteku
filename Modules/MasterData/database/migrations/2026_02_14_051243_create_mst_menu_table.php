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
            $table->id();
            $table->string('menu_name', 50);
            $table->string('menu_link', 100)->nullable();
            $table->string('menu_icon', 100)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order_no')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
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
