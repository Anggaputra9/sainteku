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
        Schema::create('trx_user_role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id', 20)->unique();
            $table->integer('role_id')->unique();
            $table->string('unit_id', 4)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_user_role');
    }
};
