<?php

namespace Modules\MonevAkademik\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\MonevAkademik\App\Models\Question;

class BankSoalController extends Controller
{
    // API 1: Ambil List Fakultas & Prodi AMAN PAKE DB::table
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

    // API 2: Ambil Matkul AMAN PAKE DB::table
    public function getApprovedCourses(Request $request)
    {
        $query = DB::table('mst_course')
            ->leftJoin('mst_unit', 'mst_course.unit_id', '=', 'mst_unit.id')
            ->select('mst_course.id', 'mst_course.course_name', 'mst_unit.unit_name')
            ->where('mst_course.is_active', '1');

        // CATATAN: Ini ngecek apa matkul tsb punya soal yang udah di-Approve.
        // Kalo lu mau ngetes dan di DB belum ada yang Approve, 
        // MATIKAN DULU blok whereExists ini pake comment (//)
        $query->whereExists(function ($q) {
            $q->select(DB::raw(1))
                ->from('trx_questions')
                ->join('trx_exam_questions', 'trx_questions.id', '=', 'trx_exam_questions.question_id')
                ->join('trx_exam_proposals', 'trx_exam_questions.proposal_id', '=', 'trx_exam_proposals.id')
                ->whereColumn('trx_questions.course_id', 'mst_course.id')
                ->where('trx_exam_proposals.status', 'APPROVED'); // <- WAJIB APPROVED
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

        $courses = $query->orderBy('mst_course.course_name', 'asc')->take(20)->get();

        return response()->json($courses);
    }

    // API 3: Ambil Soalnya
    public function getApiQuestions($course_id)
    {
        $questions = Question::with('cpmk')
            ->where('course_id', $course_id)
            ->whereHas('examQuestions.proposal', function ($query) {
                $query->where('status', 'APPROVED');
            })
            ->latest()
            ->get();

        return response()->json($questions);
    }
}