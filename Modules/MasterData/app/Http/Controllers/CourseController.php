<?php

namespace Modules\MasterData\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\MasterData\Support\BulkCourseImportService;
use Modules\MasterData\Support\CourseCodeGenerator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $faculties = DB::table('mst_unit')
            ->where('is_active', '1')
            ->where('unit_type_id', 2)
            ->orderBy('unit_name')
            ->get();

        return view('masterdata::courses.index', compact('faculties'))->with('title', 'Daftar Mata Kuliah');
    }

    public function getProdi(Request $request)
    {
        $fakultasId = $request->query('fakultas_id');

        if (! $fakultasId) {
            return response()->json([]);
        }

        $prodis = DB::table('mst_unit')
            ->where('is_active', '1')
            ->where('unit_type_id', 3)
            ->where('unit_parent', $fakultasId)
            ->orderBy('unit_name')
            ->get(['id', 'unit_name']);

        return response()->json($prodis)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function getCoursesData(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $proposalCounts = DB::table('trx_exam_proposals')
            ->select('course_id', DB::raw('count(*) as cnt'))
            ->groupBy('course_id')
            ->pluck('cnt', 'course_id');

        $questionCounts = DB::table('trx_questions')
            ->select('course_id', DB::raw('count(*) as cnt'))
            ->groupBy('course_id')
            ->pluck('cnt', 'course_id');

        $cpmkCounts = DB::table('mst_cpmk')
            ->select('course_id', DB::raw('count(*) as cnt'))
            ->groupBy('course_id')
            ->pluck('cnt', 'course_id');

        $mappingCounts = DB::table('trx_cpl_cpmk_mapping')
            ->select('course_id', DB::raw('count(*) as cnt'))
            ->groupBy('course_id')
            ->pluck('cnt', 'course_id');

        $courses = $this->buildCoursesQuery($request)
            ->paginate($perPage)
            ->through(fn (object $course): array => $this->formatCourseForApi(
                $course,
                $proposalCounts,
                $questionCounts,
                $cpmkCounts,
                $mappingCounts,
            ));

        return response()->json($courses)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function buildCoursesQuery(Request $request)
    {
        $query = DB::table('mst_course')
            ->leftJoin('mst_unit as prodi', 'mst_course.unit_id', '=', 'prodi.id')
            ->leftJoin('mst_unit as fakultas', 'prodi.unit_parent', '=', 'fakultas.id')
            ->select(
                'mst_course.*',
                'prodi.unit_name as prodi_name',
                'fakultas.id as fakultas_id',
                'fakultas.unit_name as fakultas_name',
            );

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('mst_course.course_name', 'like', '%' . $search . '%')
                    ->orWhere('mst_course.id', 'like', '%' . $search . '%')
                    ->orWhere('prodi.unit_name', 'like', '%' . $search . '%')
                    ->orWhere('fakultas.unit_name', 'like', '%' . $search . '%');
            });
        }

        $status = $request->query('status');
        if (in_array($status, ['1', '0'], true)) {
            $query->where('mst_course.is_active', $status);
        }

        $fakultasId = $request->query('fakultas_id');
        if ($fakultasId !== null && $fakultasId !== '') {
            $query->where('fakultas.id', $fakultasId);
        }

        $prodiId = $request->query('prodi_id');
        if ($prodiId !== null && $prodiId !== '') {
            $query->where('mst_course.unit_id', $prodiId);
        }

        $sort = (string) $request->query('sort', 'name_asc');
        match ($sort) {
            'newest' => $query->orderByDesc('mst_course.created_at')->orderByDesc('mst_course.id'),
            'oldest' => $query->orderBy('mst_course.created_at')->orderBy('mst_course.id'),
            'name_desc' => $query->orderByDesc('mst_course.course_name'),
            'code_asc' => $query->orderBy('mst_course.id'),
            'code_desc' => $query->orderByDesc('mst_course.id'),
            default => $query->orderBy('mst_course.course_name'),
        };

        return $query;
    }

    private function formatCourseForApi(
        object $course,
        $proposalCounts,
        $questionCounts,
        $cpmkCounts,
        $mappingCounts,
    ): array {
        $proposalCount = (int) ($proposalCounts[$course->id] ?? 0);
        $questionCount = (int) ($questionCounts[$course->id] ?? 0);
        $cpmkCount = (int) ($cpmkCounts[$course->id] ?? 0);
        $mappingCount = (int) ($mappingCounts[$course->id] ?? 0);

        return [
            'id' => $course->id,
            'course_name' => $course->course_name,
            'unit_id' => $course->unit_id,
            'prodi_name' => $course->prodi_name,
            'fakultas_id' => $course->fakultas_id,
            'fakultas_name' => $course->fakultas_name,
            'is_active' => $course->is_active,
            'initial' => mb_strtoupper(mb_substr($course->course_name, 0, 1)),
            'proposal_count' => $proposalCount,
            'question_count' => $questionCount,
            'cpmk_count' => $cpmkCount,
            'mapping_count' => $mappingCount,
            'cpmk_api_url' => route('masterdata.courses.cpmk.api.data', $course->id),
            'cpmk_store_url' => route('masterdata.courses.cpmk.store', $course->id),
            'cpmk_bulk_destroy_url' => route('masterdata.courses.cpmk.bulk.destroy', $course->id),
            'mapping_api_url' => route('masterdata.courses.mapping.api.data', $course->id),
            'mapping_sync_url' => route('masterdata.courses.mapping.sync', $course->id),
            'update_url' => route('masterdata.courses.update', $course->id),
            'delete_url' => route('masterdata.courses.destroy', $course->id),
            'can_delete' => $proposalCount === 0 && $questionCount === 0,
        ];
    }

    public function bulkStore(Request $request, BulkCourseImportService $importService)
    {
        $data = $request->validate([
            'unit_id' => 'required|string|exists:mst_unit,id',
            'is_active' => 'required|in:0,1',
            'bulk_text' => 'required|string',
        ]);

        $prodi = DB::table('mst_unit')
            ->where('id', $data['unit_id'])
            ->where('unit_type_id', 3)
            ->first();

        if (! $prodi) {
            return response()->json([
                'message' => 'Unit yang dipilih bukan program studi.',
            ], 422);
        }

        $result = $importService->import(
            $data['unit_id'],
            $data['is_active'],
            $data['bulk_text'],
        );

        $message = $result['success_count'] . ' mata kuliah berhasil ditambahkan';
        if ($result['failed_count'] > 0) {
            $message .= ', ' . $result['failed_count'] . ' gagal';
        }

        return response()->json([
            ...$result,
            'message' => $message,
        ]);
    }

    public function downloadBulkTemplate(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['nama']);
            fputcsv($handle, ['Pemrograman Web']);
            fputcsv($handle, ['Basis Data']);
            fputcsv($handle, ['Jaringan Komputer']);
            fclose($handle);
        }, 'template-bulk-mata-kuliah.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:100',
            'unit_id' => 'required|string|exists:mst_unit,id',
            'is_active' => 'required|in:0,1',
        ]);

        $newId = app(CourseCodeGenerator::class)->generateNext($request->unit_id);

        DB::table('mst_course')->insert([
            'id' => $newId,
            'course_name' => $request->course_name,
            'unit_id' => $request->unit_id,
            'is_active' => $request->is_active,
            'created_at' => now(),
        ]);

        return redirect()->route('masterdata.courses.index')
            ->with('success', 'Data Mata Kuliah berhasil ditambahkan dengan Kode ' . $newId);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_name' => 'required|string|max:100',
            'unit_id' => 'required|string|exists:mst_unit,id',
            'is_active' => 'required|in:0,1',
        ]);

        DB::table('mst_course')->where('id', $id)->update([
            'course_name' => $request->course_name,
            'unit_id' => $request->unit_id,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('masterdata.courses.index')
            ->with('success', 'Data Mata Kuliah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $proposalCount = DB::table('trx_exam_proposals')->where('course_id', $id)->count();
        $questionCount = DB::table('trx_questions')->where('course_id', $id)->count();

        if ($proposalCount > 0 || $questionCount > 0) {
            return redirect()->route('masterdata.courses.index')
                ->with('error', 'Mata kuliah tidak dapat dihapus karena masih terhubung dengan pengajuan soal atau bank soal.');
        }

        try {
            DB::table('mst_course')->where('id', $id)->delete();

            return redirect()->route('masterdata.courses.index')
                ->with('success', 'Data Mata Kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.courses.index')
                ->with('error', 'Gagal menghapus! Mata kuliah ini sedang terhubung dengan relasi lain.');
        }
    }
}