<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MstCpl;
use App\Models\MstCpmk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CplCpmkMappingController extends Controller
{
    public function getMappingData(string $courseId): JsonResponse
    {
        $course = $this->findCourseOrFail($courseId);

        $cpmks = MstCpmk::query()
            ->where('course_id', $courseId)
            ->orderBy('id')
            ->get(['id', 'name', 'is_active']);

        $cpls = MstCpl::forUnit($course->unit_id, true)
            ->get(['id', 'name', 'is_active']);

        $mapped = DB::table('trx_cpl_cpmk_mapping')
            ->where('course_id', $courseId)
            ->get()
            ->groupBy('cpmk_id')
            ->map(fn ($rows) => $rows->pluck('cpl_id')->values()->all());

        return response()->json([
            'course_id' => $courseId,
            'unit_id' => $course->unit_id,
            'mapping_count' => DB::table('trx_cpl_cpmk_mapping')->where('course_id', $courseId)->count(),
            'cpmks' => $cpmks->map(fn ($cpmk) => [
                'id' => $cpmk->id,
                'name' => $cpmk->name,
                'is_active' => $cpmk->is_active,
                'cpl_ids' => $mapped[$cpmk->id] ?? [],
            ]),
            'cpls' => $cpls,
        ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function sync(Request $request, string $courseId): JsonResponse
    {
        $course = $this->findCourseOrFail($courseId);

        $validated = $request->validate([
            'mappings' => 'required|array',
            'mappings.*.cpmk_id' => 'required|string|max:5',
            'mappings.*.cpl_ids' => 'array',
            'mappings.*.cpl_ids.*' => 'string|max:5',
        ]);

        $validCpmkIds = MstCpmk::query()
            ->where('course_id', $courseId)
            ->pluck('id')
            ->all();

        DB::beginTransaction();

        try {
            DB::table('trx_cpl_cpmk_mapping')->where('course_id', $courseId)->delete();

            $insertRows = [];

            foreach ($validated['mappings'] as $mapping) {
                $cpmkId = $mapping['cpmk_id'];
                $cplIds = array_values(array_unique($mapping['cpl_ids'] ?? []));

                if (! in_array($cpmkId, $validCpmkIds, true)) {
                    DB::rollBack();

                    return response()->json([
                        'message' => "CPMK {$cpmkId} tidak valid untuk mata kuliah ini.",
                    ], 422);
                }

                if (! MstCpl::validateIdsForUnit($course->unit_id, $cplIds)) {
                    DB::rollBack();

                    return response()->json([
                        'message' => 'Salah satu CPL tidak valid untuk prodi pengampu.',
                    ], 422);
                }

                foreach ($cplIds as $cplId) {
                    $insertRows[] = [
                        'course_id' => $courseId,
                        'unit_id' => $course->unit_id,
                        'cpmk_id' => $cpmkId,
                        'cpl_id' => $cplId,
                        'created_at' => now(),
                    ];
                }
            }

            if ($insertRows !== []) {
                DB::table('trx_cpl_cpmk_mapping')->insert($insertRows);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pemetaan CPL–CPMK berhasil disimpan.',
                'mapping_count' => count($insertRows),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal menyimpan pemetaan: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function findCourseOrFail(string $courseId): object
    {
        $course = DB::table('mst_course')->where('id', $courseId)->first();

        abort_unless($course, 404, 'Mata kuliah tidak ditemukan.');

        return $course;
    }
}