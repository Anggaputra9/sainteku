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
            ->get()
            ->map(fn (MstCpmk $cpmk): array => $this->formatCpmkForApi($cpmk));

        return response()->json($cpmks)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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

    private function formatCpmkForApi(MstCpmk $cpmk): array
    {
        return [
            'id' => $cpmk->id,
            'course_id' => $cpmk->course_id,
            'name' => $cpmk->name,
            'is_active' => $cpmk->is_active,
            'update_url' => route('masterdata.courses.cpmk.update', [$cpmk->course_id, $cpmk->id]),
            'delete_url' => route('masterdata.courses.cpmk.destroy', [$cpmk->course_id, $cpmk->id]),
            'can_delete' => ! $this->isCpmkUsedInQuestions($cpmk->course_id, $cpmk->id)
                && ! $this->isCpmkUsedInMapping($cpmk->course_id, $cpmk->id),
        ];
    }
}