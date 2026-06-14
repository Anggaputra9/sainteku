<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->string('user_id', 50)->nullable()->change();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->index('user_id');
        });
    }
};