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
        Schema::create('mst_inventory', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('description', 50);
            $table->integer('inventory_type');
            $table->integer('quantity');
            $table->dateTime('created_at')->nullable();

            $table->foreign('inventory_type')
                ->references('id')->on('mst_inventory_type');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_inventory');
    }
};
