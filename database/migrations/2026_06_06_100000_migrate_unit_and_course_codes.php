<?php

use Illuminate\Database\Migrations\Migration;
use Modules\MasterData\Support\LegacyCodeMigrator;
use Modules\MasterData\Support\UnitCodeGenerator;

return new class extends Migration
{
    public function up(): void
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('mst_unit')) {
            return;
        }

        if (! \Illuminate\Support\Facades\DB::table('mst_unit')->where('id', 'U001')->exists()) {
            return;
        }

        app(LegacyCodeMigrator::class)->migrate();
    }

    public function down(): void
    {
        // Tidak reversible — backup DB sebelum migrasi jika perlu rollback manual.
    }
};