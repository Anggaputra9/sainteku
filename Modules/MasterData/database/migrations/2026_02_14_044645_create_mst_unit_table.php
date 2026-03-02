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
        Schema::create('mst_unit', function (Blueprint $table) {
            $table->string('id', 4)->primary();
            $table->string('unit_name', 100);
            $table->string('unit_parent', 4)->nullable();
            $table->integer('unit_type_id');
            $table->enum('is_active', ['0', '1']);
            $table->dateTime('created_at')->nullable();

            $table->foreign('unit_type_id')
                ->references('id')->on('ref_unit_type');

            $table->foreign('unit_parent')
                ->references('id')->on('mst_unit');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_unit');
    }
};
