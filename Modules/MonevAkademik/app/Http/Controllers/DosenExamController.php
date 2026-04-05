<?php

namespace Modules\MonevAkademik\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MonevAkademik\App\Models\ExamProposal;
use Modules\MonevAkademik\App\Models\Question;
use Modules\MonevAkademik\App\Models\ExamQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenExamController extends Controller
{
    // Menampilkan Grid Card Mata Kuliah sesuai prodi Dosen
    public function index()
    {
        // Asumsi logic: Ambil data course berdasarkan unit_id (Prodi) dari Dosen yang login
        $userUnitId = Auth::user()->unit_id;
        $courses = \App\Models\MstCourse::where('unit_id', $userUnitId)->get();

        return view('monevakademik::dosen.exam.index', compact('courses'));
    }

    // Menampilkan halaman form pengajuan soal (Tampilan Card per nomor)
    public function create($course_id)
    {
        $course = \App\Models\MstCourse::findOrFail($course_id);
        $cpmkList = \App\Models\MstCpmk::where('is_active', '1')->get();

        return view('monevakademik::dosen.exam.create', compact('course', 'cpmkList'));
    }

    // Menyimpan pengajuan ke database (diekseskusi setelah bobot 100 terpenuhi di local storage frontend)
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'period_id' => 'required',
            'exam_type' => 'required|in:UTS,UAS',
            'questions' => 'required|array', // Data soal dari local storage frontend
            'questions.*.weight' => 'required|numeric',
        ]);

        // Validasi backend: pastikan total bobot wajib 100
        $totalWeight = collect($request->questions)->sum('weight');
        if ($totalWeight != 100) {
            return back()->with('error', 'Total bobot soal harus tepat 100!');
        }

        DB::beginTransaction();
        try {
            // 1. Buat Header Pengajuan
            $proposal = ExamProposal::create([
                'period_id' => $request->period_id,
                'course_id' => $request->course_id,
                'exam_type' => $request->exam_type,
                'status' => 'SUBMITTED',
                'created_by' => Auth::id(),
            ]);

            // 2. Looping data soal dan simpan ke Bank Soal lalu ke Pivot
            foreach ($request->questions as $index => $q) {
                // Simpan/Ambil dari Bank Soal (trx_questions)
                $question = Question::create([
                    'course_id' => $request->course_id,
                    'cpmk_id' => $q['cpmk_id'],
                    'question_text' => $q['question_text'],
                    'created_by' => Auth::id(),
                ]);

                // Simpan ke Pivot (trx_exam_questions) beserta bobotnya
                ExamQuestion::create([
                    'proposal_id' => $proposal->id,
                    'question_id' => $question->id,
                    'order_no' => $index + 1,
                    'weight' => $q['weight'],
                ]);
            }

            DB::commit();
            return redirect()->route('monevakademik.dosen.exam.index')->with('success', 'Soal berhasil diajukan ke Kaprodi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}