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
        Schema::table('trx_user_role', function (Blueprint $table) {
            // Drop unique constraint on user_id to allow multiple roles per user
            try {
                $table->dropUnique('trx_user_role_user_id_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }

            // Also drop unique constraint on role_id since it doesn't make sense
            try {
                $table->dropUnique('trx_user_role_role_id_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_user_role', function (Blueprint $table) {
            $table->unique('user_id');
            $table->unique('role_id');
        });
    }
};
