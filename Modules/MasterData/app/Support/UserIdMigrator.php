<?php

namespace Modules\MasterData\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserIdMigrator
{
    public function __construct(
        private readonly UserCodeGenerator $userCodeGenerator,
    ) {}

    public function migrate(): array
    {
        if ($this->alreadyMigrated()) {
            return [];
        }

        $map = $this->buildUserMap();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $this->applyUserMap($map);
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        return $map;
    }

    public function changeUserId(string $oldId, string $newId): void
    {
        if ($oldId === $newId) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $this->replaceUserReferences($oldId, $newId);
            DB::table('mst_user')->where('id', $oldId)->update(['id' => $newId]);
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function alreadyMigrated(): bool
    {
        return DB::table('mst_user')
            ->pluck('id')
            ->every(fn (string $id): bool => $this->userCodeGenerator->isNewFormat($id));
    }

    private function buildUserMap(): array
    {
        $map = [];
        $counters = [];

        $users = DB::table('mst_user')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get(['id', 'unit_id']);

        foreach ($users as $user) {
            $roleCodes = DB::table('trx_user_role as ur')
                ->join('mst_role as r', 'ur.role_id', '=', 'r.id')
                ->where('ur.user_id', $user->id)
                ->pluck('r.role_code')
                ->all();

            if ($roleCodes === []) {
                $roleCodes = ['MHS'];
            }

            $primaryRole = $this->userCodeGenerator->resolvePrimaryRoleCode($roleCodes);
            $bucketKey = $primaryRole . '|' . $user->unit_id;
            $counters[$bucketKey] = ($counters[$bucketKey] ?? 0) + 1;

            $map[$user->id] = $this->userCodeGenerator->formatId(
                $primaryRole,
                $user->unit_id,
                $counters[$bucketKey]
            );
        }

        return $map;
    }

    private function applyUserMap(array $map): void
    {
        foreach ($map as $oldId => $newId) {
            if ($oldId === $newId) {
                continue;
            }

            $this->replaceUserReferences($oldId, $newId);
        }

        foreach ($map as $oldId => $newId) {
            if ($oldId === $newId) {
                continue;
            }

            DB::table('mst_user')->where('id', $oldId)->update(['id' => $newId]);
        }
    }

    private function replaceUserReferences(string $oldId, string $newId): void
    {
        foreach ($this->userReferenceColumns() as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                DB::table($table)->where($column, $oldId)->update([$column => $newId]);
            }
        }

        if (Schema::hasTable('notifications')) {
            DB::table('notifications')
                ->where('notifiable_type', 'App\\Models\\User')
                ->where('notifiable_id', $oldId)
                ->update(['notifiable_id' => $newId]);
        }
    }

    private function userReferenceColumns(): array
    {
        return [
            'trx_user_role' => ['user_id'],
            'mst_user_unit' => ['user_id'],
            'trx_document' => ['created_by'],
            'trx_document_version' => ['approved_by'],
            'trx_exam_proposals' => ['created_by', 'approved_by'],
            'trx_questions' => ['created_by'],
            'trx_exam_reviews' => ['reviewer_id'],
            'trx_exam_rooms' => ['created_by'],
            'trx_exam_attempts' => ['user_id'],
            'trx_exam_attempt_answers' => ['graded_by'],
            'trx_inventory_loans' => ['user_id', 'approved_by'],
            'dosen_achievements' => ['user_id', 'approved_by'],
            'trx_achievements' => ['user_id', 'approved_by'],
            'exam_question_logs' => ['user_id'],
            'app_email_verifications' => ['user_id'],
        ];
    }
}