<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('trx_cpl_cpmk_mapping') && $this->foreignKeyExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_cpl_id_foreign')) {
            Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                $table->dropForeign(['cpl_id']);
            });
        }

        if (! Schema::hasTable('mst_cpl')) {
            return;
        }

        if ($this->foreignKeyExists('mst_cpl', 'mst_cpl_unit_id_foreign')) {
            Schema::table('mst_cpl', function (Blueprint $table) {
                $table->dropForeign(['unit_id']);
            });
        }

        if ($this->primaryKeyIsSingleColumn('mst_cpl', 'id')) {
            Schema::table('mst_cpl', function (Blueprint $table) {
                $table->dropPrimary(['id']);
                $table->primary(['unit_id', 'id']);
            });
        }

        if (! $this->foreignKeyExists('mst_cpl', 'mst_cpl_unit_id_foreign')) {
            Schema::table('mst_cpl', function (Blueprint $table) {
                $table->foreign('unit_id')
                    ->references('id')
                    ->on('mst_unit')
                    ->onDelete('cascade');
            });
        }

        if (! Schema::hasTable('trx_cpl_cpmk_mapping')) {
            return;
        }

        if (! Schema::hasColumn('trx_cpl_cpmk_mapping', 'unit_id')) {
            Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                $table->string('unit_id', 4)->nullable()->after('course_id');
            });
        }

        DB::table('trx_cpl_cpmk_mapping as mapping')
            ->join('mst_course as course', 'mapping.course_id', '=', 'course.id')
            ->whereNull('mapping.unit_id')
            ->update(['mapping.unit_id' => DB::raw('course.unit_id')]);

        DB::statement('ALTER TABLE trx_cpl_cpmk_mapping MODIFY unit_id VARCHAR(4) NOT NULL');

        if (! $this->foreignKeyExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_unit_id_cpl_id_foreign')) {
            Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                $table->foreign(['unit_id', 'cpl_id'])
                    ->references(['unit_id', 'id'])
                    ->on('mst_cpl')
                    ->onDelete('cascade');
            });
        }

        if (! $this->indexExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_unique')) {
            Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                $table->unique(['course_id', 'cpmk_id', 'cpl_id'], 'trx_cpl_cpmk_mapping_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('trx_cpl_cpmk_mapping')) {
            if ($this->indexExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_unique')) {
                Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                    $table->dropUnique('trx_cpl_cpmk_mapping_unique');
                });
            }

            if ($this->foreignKeyExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_unit_id_cpl_id_foreign')) {
                Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                    $table->dropForeign(['unit_id', 'cpl_id']);
                });
            }

            if (Schema::hasColumn('trx_cpl_cpmk_mapping', 'unit_id')) {
                Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                    $table->dropColumn('unit_id');
                });
            }

            if (! $this->foreignKeyExists('trx_cpl_cpmk_mapping', 'trx_cpl_cpmk_mapping_cpl_id_foreign')) {
                Schema::table('trx_cpl_cpmk_mapping', function (Blueprint $table) {
                    $table->foreign('cpl_id')
                        ->references('id')
                        ->on('mst_cpl');
                });
            }
        }

        if (! Schema::hasTable('mst_cpl')) {
            return;
        }

        if ($this->foreignKeyExists('mst_cpl', 'mst_cpl_unit_id_foreign')) {
            Schema::table('mst_cpl', function (Blueprint $table) {
                $table->dropForeign(['unit_id']);
            });
        }

        if (! $this->primaryKeyIsSingleColumn('mst_cpl', 'id')) {
            Schema::table('mst_cpl', function (Blueprint $table) {
                $table->dropPrimary(['unit_id', 'id']);
                $table->primary(['id']);
            });
        }

        Schema::table('mst_cpl', function (Blueprint $table) {
            $table->foreign('unit_id')
                ->references('id')
                ->on('mst_unit');
        });
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

    private function indexExists(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();

        return DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $indexName)
            ->exists();
    }
};