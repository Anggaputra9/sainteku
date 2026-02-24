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
        Schema::table('mst_user', function (Blueprint $table) {
            // Make identity_id nullable with default null
            $table->string('identity_id', 20)->nullable()->change();
            // Make user_type nullable with default null
            $table->string('user_type', 3)->nullable()->change();
            // Make unit_id nullable with default null
            $table->string('unit_id', 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_user', function (Blueprint $table) {
            $table->string('identity_id', 20)->nullable(false)->change();
            $table->string('user_type', 3)->nullable(false)->change();
            $table->string('unit_id', 4)->nullable(false)->change();
        });
    }
};
