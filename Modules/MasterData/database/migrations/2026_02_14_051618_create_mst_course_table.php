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
        Schema::create('mst_course', function (Blueprint $table) {
            $table->string('id', 8)->primary();
            $table->string('course_name', 100);
            $table->string('unit_id', 4);
            $table->enum('is_active', ['0', '1']);
            $table->dateTime('created_at')->nullable();

            $table->foreign('unit_id')
                ->references('id')->on('mst_unit');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_course');
    }
};
