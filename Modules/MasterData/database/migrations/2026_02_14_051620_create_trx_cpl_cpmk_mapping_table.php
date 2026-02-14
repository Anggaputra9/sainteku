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
        Schema::create('trx_cpl_cpmk_mapping', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('course_id', 5);
            $table->string('cpmk_id', 5);
            $table->string('cpl_id', 5);
            $table->dateTime('created_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_cpl_cpmk_mapping');
    }
};
