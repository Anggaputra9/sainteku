<?php

namespace Modules\MonevAkademik\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\MonevAkademik\App\Models\Question;

class BankSoalController extends Controller
{
    // =========================================================================
    // 1. HALAMAN UTAMA BANK SOAL
    // =========================================================================
    public function index()
    {
        $courses = DB::table('mst_course')
            ->leftJoin('mst_unit', 'mst_course.unit_id', '=', 'mst_unit.id')
            ->select('mst_course.*', 'mst_unit.unit_name')
            ->get();

        $units = DB::table('mst_unit')
            ->select('id', 'unit_name')
            ->orderBy('unit_name', 'asc')
            ->get();

        return view('monevakademik::bank-soal.index', compact('courses', 'units'));
    }

    // =========================================================================
    // 2. KEMBALIKAN: API Ambil List Fakultas & Prodi (Dipakai di Modal Tashih)
    // =========================================================================
    public function getUnits(Request $request)
    {
        if ($request->has('faculty_id') && $request->faculty_id != '') {
            $prodis = DB::table('mst_unit')
                ->where('unit_type_id', 3)
                ->where('unit_parent', $request->faculty_id)
                ->where('is_active', '1')
                ->get(['id', 'unit_name']);
            return response()->json($prodis);
        }

        $faculties = DB::table('mst_unit')
            ->where('unit_type_id', 2)
            ->where('is_active', '1')
            ->get(['id', 'unit_name']);

        return response()->json($faculties);
    }

    // =========================================================================
    // 3. KEMBALIKAN: API Ambil Matkul (Dipakai di Modal Tashih) + PAGINATION
    // =========================================================================
    public function getApprovedCourses(Request $request)
    {
        $query = DB::table('mst_course')
            ->leftJoin('mst_unit', 'mst_course.unit_id', '=', 'mst_unit.id')
            ->select('mst_course.id', 'mst_course.course_name', 'mst_unit.unit_name')
            ->where('mst_course.is_active', '1');

        $query->whereExists(function ($q) {
            $q->select(DB::raw(1))
                ->from('trx_questions')
                ->join('trx_exam_questions', 'trx_questions.id', '=', 'trx_exam_questions.question_id')
                ->join('trx_exam_proposals', 'trx_exam_questions.proposal_id', '=', 'trx_exam_proposals.id')
                ->whereColumn('trx_questions.course_id', 'mst_course.id')
                ->where('trx_exam_proposals.status', 'APPROVED');
        });

        if ($request->search) {
            $query->where('mst_course.course_name', 'like', '%' . $request->search . '%');
        }

        if ($request->prodi_id) {
            $query->where('mst_course.unit_id', $request->prodi_id);
        } elseif ($request->faculty_id) {
            $prodiIds = DB::table('mst_unit')->where('unit_parent', $request->faculty_id)->pluck('id');
            $query->whereIn('mst_course.unit_id', $prodiIds);
        }

        // Ambil param per_page, defaultnya 12
        $perPage = $request->input('per_page', 12);
        $courses = $query->orderBy('mst_course.course_name', 'asc')->paginate($perPage);

        return response()->json($courses);
    }

    // =========================================================================
    // 4. API Ambil Soalnya (Udah anti-badai pakai Subquery)
    // =========================================================================
    public function getApiQuestions($course_id)
    {
        try {
            $questions = Question::where('course_id', $course_id)
                ->whereIn('id', function ($query) {
                    $query->select('trx_exam_questions.question_id')
                        ->from('trx_exam_questions')
                        ->join('trx_exam_proposals', 'trx_exam_questions.proposal_id', '=', 'trx_exam_proposals.id')
                        ->where('trx_exam_proposals.status', 'APPROVED');
                })
                ->latest()
                ->get();

            $questions->each->append('cpmk_details');

            return response()->json($questions);

        } catch (\Exception $e) {
            return response()->json([
                'pesan_error' => $e->getMessage(),
                'file' => $e->getFile(),
                'baris' => $e->getLine()
            ], 500);
        }
    }

    // =========================================================================
    // API Ambil List Periode Akademik (Untuk Dropdown Filter di Modal)
    // =========================================================================
    public function getPeriods()
    {
        // Sesuaikan nama tabel 'mst_period' dengan database lu kalau beda ya cuy
        $periods = DB::table('mst_period')
            ->where('is_active', '1')
            ->orderBy('id', 'desc')
            ->get(['id', 'name']); // Ambil id dan nama periode

        return response()->json($periods);
    }

    // =========================================================================
    // 5. API Ambil Paket Soal (Proposal) + UPDATE: DUKUNGAN FILTER
    // =========================================================================
    // Tambahin Request $request di parameternya
    public function getApprovedProposals(Request $request, $course_id)
    {
        try {
            $query = \Modules\MonevAkademik\App\Models\ExamProposal::with(['creator', 'period'])
                ->where('course_id', $course_id)
                ->where('status', 'APPROVED');

            // Tangkap Filter Jenis Ujian (contoh: UTS, UAS)
            if ($request->exam_type) {
                $query->where('exam_type', $request->exam_type);
            }

            // Tangkap Filter Periode
            if ($request->period_id) {
                $query->where('period_id', $request->period_id);
            }

            $proposals = $query->latest()->get();

            return response()->json($proposals);
        } catch (\Exception $e) {
            return response()->json([
                'pesan_error' => $e->getMessage()
            ], 500);
        }
    }
}