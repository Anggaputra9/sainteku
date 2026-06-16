<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('mst_cpmk')) {
            return;
        }

        if (Schema::hasTable('trx_cpl_cpmk_mapping') && $this->foreignKeyExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_cpmk_id_foreign')) {
            Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                $table->dropForeign(['cpmk_id']);
            });
        }

        if (! Schema::hasColumn('mst_cpmk', 'course_id')) {
            Schema::table('mst_cpmk', function (Blueprint $table) {
                $table->string('course_id', 8)->nullable();
            });
        } else {
            DB::statement('ALTER TABLE mst_cpmk MODIFY course_id VARCHAR(8) NULL');
        }

        DB::table('mst_cpmk')->whereNull('course_id')->update(['course_id' => 'INF002']);
        DB::statement('ALTER TABLE mst_cpmk MODIFY course_id VARCHAR(8) NOT NULL');

        if ($this->primaryKeyIsSingleColumn('mst_cpmk', 'id')) {
            Schema::table('mst_cpmk', function (Blueprint $table) {
                $table->dropPrimary(['id']);
                $table->primary(['course_id', 'id']);
            });
        }

        if (! $this->foreignKeyExists('mst_cpmk', 'mst_cpmk_course_id_foreign')) {
            Schema::table('mst_cpmk', function (Blueprint $table) {
                $table->foreign('course_id')
                    ->references('id')
                    ->on('mst_course')
                    ->onDelete('cascade');
            });
        }

        if (
            Schema::hasTable('trx_cpl_cpmk_mapping')
            && ! $this->foreignKeyExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_course_id_cpmk_id_foreign')
        ) {
            Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                $table->foreign(['course_id', 'cpmk_id'])
                    ->references(['course_id', 'id'])
                    ->on('mst_cpmk')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('mst_cpmk') || ! Schema::hasColumn('mst_cpmk', 'course_id')) {
            return;
        }

        if (Schema::hasTable('trx_cpl_cpmk_mapping') && $this->foreignKeyExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_course_id_cpmk_id_foreign')) {
            Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                $table->dropForeign(['course_id', 'cpmk_id']);
            });
        }

        if ($this->foreignKeyExists('mst_cpmk', 'mst_cpmk_course_id_foreign')) {
            Schema::table('mst_cpmk', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
            });
        }

        if (! $this->primaryKeyIsSingleColumn('mst_cpmk', 'id')) {
            Schema::table('mst_cpmk', function (Blueprint $table) {
                $table->dropPrimary(['course_id', 'id']);
                $table->primary(['id']);
            });
        }

        Schema::table('mst_cpmk', function (Blueprint $table) {
            $table->dropColumn('course_id');
        });

        if (Schema::hasTable('trx_cpl_cpmk_mapping') && ! $this->foreignKeyExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_cpmk_id_foreign')) {
            Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                $table->foreign('cpmk_id')
                    ->references('id')
                    ->on('mst_cpmk');
            });
        }
    }

    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        $database = DB::getDatabaseName();

        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $constraintName)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }

    private function primaryKeyIsSingleColumn(string $table, string $column): bool
    {
        $database = DB::getDatabaseName();

        $keys = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', 'PRIMARY')
            ->orderBy('ORDINAL_POSITION')
            ->pluck('COLUMN_NAME')
            ->all();

        return $keys === [$column];
    }
};