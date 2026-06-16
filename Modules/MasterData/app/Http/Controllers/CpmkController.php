<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MstCpmk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\MasterData\Support\CpmkCodeGenerator;

class CpmkController extends Controller
{
    public function getCpmkData(Request $request, string $courseId): JsonResponse
    {
        $this->ensureCourseExists($courseId);

        $cpmks = MstCpmk::query()
            ->where('course_id', $courseId)
            ->orderBy('id')
            ->get();

        $cpmkIds = $cpmks->pluck('id')->all();
        $usedInMapping = array_flip($this->cpmkIdsUsedInMapping($courseId, $cpmkIds));
        $usedInQuestions = array_flip($this->cpmkIdsUsedInQuestions($courseId, $cpmkIds));

        $data = $cpmks->map(function (MstCpmk $cpmk) use ($usedInMapping, $usedInQuestions): array {
            $canDelete = ! isset($usedInMapping[$cpmk->id]) && ! isset($usedInQuestions[$cpmk->id]);

            return $this->formatCpmkForApi($cpmk, $canDelete);
        });

        return response()->json($data);
    }

    public function store(Request $request, string $courseId): JsonResponse
    {
        $this->ensureCourseExists($courseId);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'is_active' => 'required|in:0,1',
        ]);

        $id = app(CpmkCodeGenerator::class)->generateNext($courseId);

        MstCpmk::create([
            'course_id' => $courseId,
            'id' => $id,
            'name' => $validated['name'],
            'is_active' => $validated['is_active'],
            'created_at' => now(),
        ]);

        $cpmk = MstCpmk::query()
            ->where('course_id', $courseId)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'message' => 'CPMK berhasil ditambahkan.',
            'cpmk' => $this->formatCpmkForApi($cpmk),
        ], 201);
    }

    public function update(Request $request, string $courseId, string $cpmkId): JsonResponse
    {
        $cpmk = $this->findCpmkOrFail($courseId, $cpmkId);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'is_active' => 'required|in:0,1',
        ]);

        MstCpmk::query()
            ->where('course_id', $courseId)
            ->where('id', $cpmkId)
            ->update([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
            ]);

        $cpmk = $this->findCpmkOrFail($courseId, $cpmkId);

        return response()->json([
            'message' => 'CPMK berhasil diperbarui.',
            'cpmk' => $this->formatCpmkForApi($cpmk),
        ]);
    }

    public function destroy(string $courseId, string $cpmkId): JsonResponse
    {
        $cpmk = $this->findCpmkOrFail($courseId, $cpmkId);

        if ($this->isCpmkUsedInQuestions($courseId, $cpmkId) || $this->isCpmkUsedInMapping($courseId, $cpmkId)) {
            return response()->json([
                'message' => 'CPMK tidak dapat dihapus karena masih digunakan di bank soal, pengajuan, atau pemetaan CPL.',
            ], 422);
        }

        MstCpmk::query()
            ->where('course_id', $courseId)
            ->where('id', $cpmkId)
            ->delete();

        return response()->json([
            'message' => 'CPMK berhasil dihapus.',
        ]);
    }

    public function bulkDestroy(Request $request, string $courseId): JsonResponse
    {
        $this->ensureCourseExists($courseId);

        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|string|max:5',
        ]);

        $deleted = [];
        $skipped = [];

        foreach ($validated['ids'] as $cpmkId) {
            $exists = MstCpmk::query()
                ->where('course_id', $courseId)
                ->where('id', $cpmkId)
                ->exists();

            if (! $exists) {
                $skipped[] = ['id' => $cpmkId, 'reason' => 'CPMK tidak ditemukan.'];
                continue;
            }

            if ($this->isCpmkUsedInQuestions($courseId, $cpmkId) || $this->isCpmkUsedInMapping($courseId, $cpmkId)) {
                $skipped[] = ['id' => $cpmkId, 'reason' => 'Masih digunakan di soal atau pemetaan CPL.'];
                continue;
            }

            MstCpmk::query()
                ->where('course_id', $courseId)
                ->where('id', $cpmkId)
                ->delete();

            $deleted[] = $cpmkId;
        }

        if (count($deleted) === 0) {
            return response()->json([
                'message' => 'Tidak ada CPMK yang dapat dihapus.',
                'deleted_count' => 0,
                'skipped' => $skipped,
            ], 422);
        }

        $message = count($deleted) === 1
            ? 'CPMK berhasil dihapus.'
            : count($deleted).' CPMK berhasil dihapus.';

        if (count($skipped) > 0) {
            $message .= ' '.count($skipped).' CPMK dilewati.';
        }

        return response()->json([
            'message' => $message,
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
            'skipped' => $skipped,
        ]);
    }

    private function ensureCourseExists(string $courseId): void
    {
        abort_unless(
            DB::table('mst_course')->where('id', $courseId)->exists(),
            404,
            'Mata kuliah tidak ditemukan.',
        );
    }

    private function findCpmkOrFail(string $courseId, string $cpmkId): MstCpmk
    {
        $this->ensureCourseExists($courseId);

        return MstCpmk::query()
            ->where('course_id', $courseId)
            ->where('id', $cpmkId)
            ->firstOrFail();
    }

    private function isCpmkUsedInQuestions(string $courseId, string $cpmkId): bool
    {
        return DB::table('trx_questions')
            ->where('course_id', $courseId)
            ->whereRaw('JSON_CONTAINS(cpmk_id, ?)', [json_encode($cpmkId)])
            ->exists();
    }

    private function isCpmkUsedInMapping(string $courseId, string $cpmkId): bool
    {
        return DB::table('trx_cpl_cpmk_mapping')
            ->where('course_id', $courseId)
            ->where('cpmk_id', $cpmkId)
            ->exists();
    }

    private function cpmkIdsUsedInMapping(string $courseId, array $cpmkIds): array
    {
        if ($cpmkIds === []) {
            return [];
        }

        return DB::table('trx_cpl_cpmk_mapping')
            ->where('course_id', $courseId)
            ->whereIn('cpmk_id', $cpmkIds)
            ->distinct()
            ->pluck('cpmk_id')
            ->all();
    }

    private function cpmkIdsUsedInQuestions(string $courseId, array $cpmkIds): array
    {
        if ($cpmkIds === []) {
            return [];
        }

        $used = [];
        $cpmkIdSet = array_flip($cpmkIds);

        $query = DB::table('trx_questions')->where('course_id', $courseId);
        $query->where(function ($builder) use ($cpmkIds): void {
            foreach ($cpmkIds as $cpmkId) {
                $builder->orWhereRaw('JSON_CONTAINS(cpmk_id, ?)', [json_encode($cpmkId)]);
            }
        });

        foreach ($query->pluck('cpmk_id') as $cpmkJson) {
            $ids = json_decode($cpmkJson, true);

            if (! is_array($ids)) {
                continue;
            }

            foreach ($ids as $id) {
                if (isset($cpmkIdSet[$id])) {
                    $used[$id] = true;
                }
            }
        }

        return array_keys($used);
    }

    private function formatCpmkForApi(MstCpmk $cpmk, ?bool $canDelete = null): array
    {
        if ($canDelete === null) {
            $canDelete = ! $this->isCpmkUsedInQuestions($cpmk->course_id, $cpmk->id)
                && ! $this->isCpmkUsedInMapping($cpmk->course_id, $cpmk->id);
        }

        return [
            'id' => $cpmk->id,
            'course_id' => $cpmk->course_id,
            'name' => $cpmk->name,
            'is_active' => $cpmk->is_active,
            'update_url' => route('masterdata.courses.cpmk.update', [$cpmk->course_id, $cpmk->id], false),
            'delete_url' => route('masterdata.courses.cpmk.delete', [$cpmk->course_id, $cpmk->id], false),
            'bulk_destroy_url' => route('masterdata.courses.cpmk.bulk.destroy', $cpmk->course_id, false),
            'can_delete' => $canDelete,
        ];
    }
}