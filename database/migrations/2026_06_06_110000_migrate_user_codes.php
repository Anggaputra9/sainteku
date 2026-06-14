<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\MasterData\Support\UserCodeGenerator;
use Modules\MasterData\Support\UserIdMigrator;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('mst_user')) {
            return;
        }

        $hasLegacyIds = DB::table('mst_user')
            ->pluck('id')
            ->contains(fn (string $id): bool => ! app(UserCodeGenerator::class)->isNewFormat($id));

        if (! $hasLegacyIds) {
            return;
        }

        app(UserIdMigrator::class)->migrate();
    }

    public function down(): void
    {
        // Tidak reversible — backup DB sebelum migrasi jika perlu rollback manual.
    }
};