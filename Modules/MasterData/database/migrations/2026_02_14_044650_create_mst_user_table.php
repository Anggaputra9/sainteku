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
        Schema::create('mst_user', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('name', 30);
            $table->string('email', 30)->nullable();
            $table->string('password', 100);
            $table->string('identity_id', 20)->index();
            $table->string('user_type', 3);
            $table->string('unit_id', 4);
            $table->text('signature')->nullable();
            $table->enum('is_active', ['0', '1']);
            $table->dateTime('last_login_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('user_type')
                ->references('id')->on('ref_user_type');

            $table->foreign('unit_id')
                ->references('id')->on('mst_unit');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_user');
    }
};
