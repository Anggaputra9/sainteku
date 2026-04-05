<?php

namespace Modules\MonevAkademik\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\MonevAkademik\App\Models\ExamProposal;
use Modules\MonevAkademik\App\Models\ExamQuestion;
use Modules\MonevAkademik\App\Models\Question;
use App\Models\MstCourse;
use App\Models\MstCpmk;

class ExamProposalController extends Controller
{
    private $moduleId = 3;
    public function index()
    {
        $user = Auth::user();

        // Ambil matkul sesuai unit dosen
        $myCourses = MstCourse::where('unit_id', $user->unit_id)->get();

        // Pilihan CPMK untuk form
        $cpmkList = MstCpmk::where('is_active', '1')->get();

        // Load semua relasi biar JSON-nya komplit buat Alpine SPA
        $myProposals = ExamProposal::with(['course', 'creator', 'examQuestions.question', 'reviews'])
            ->where('created_by', $user->id)
            ->latest()
            ->get();

        // CEK MULTI-ROLE DINAMIS TANPA HARDCODE
        // Pastikan 'role_name' disesuaikan kalau di tabelmu namanya 'name' atau pakai 'role_id'
        $isReviewer = $user->roles()->whereIn('role_name', [
            'Kaprodi',
            'Reviewer Internal',
            'Reviewer External'
        ])->exists();

        $reviewQueue = collect();

        if ($isReviewer) {
            // FILTER SCOPE PRODI: Ambil antrean soal khusus di Prodi Kaprodi tersebut
            $reviewQueue = ExamProposal::with(['course', 'creator', 'examQuestions.question', 'reviews'])
                ->where('status', 'SUBMITTED')
                ->whereHas('course', function ($query) use ($user) {
                    $query->where('unit_id', $user->unit_id);
                })
                ->latest()
                ->get();
        }

        return view('monevakademik::tashih.index', compact('myCourses', 'myProposals', 'isReviewer', 'reviewQueue', 'cpmkList'));
    }

    public function create($course_id)
    {
        return redirect()->route('monevakademik.tashih.index');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'C')) {
            abort(403, 'Anda tidak memiliki akses untuk membuat pengajuan.');
        }

        $request->validate([
            'course_id' => 'required',
            'exam_type' => 'required|in:UTS,UAS',
            'period_id' => 'required',
            'questions' => 'required|array',
        ]);

        if (collect($request->questions)->sum('weight') != 100) {
            return back()->with('error', 'Total bobot soal harus tepat 100!');
        }

        DB::beginTransaction();
        try {
            $proposal = ExamProposal::create([
                'course_id' => $request->course_id,
                'exam_type' => $request->exam_type,
                'period_id' => $request->period_id,
                'status' => 'SUBMITTED',
                'created_by' => Auth::id(),
            ]);

            $orderCounter = 1;

            foreach ($request->questions as $q) {
                $question = Question::create([
                    'course_id' => $request->course_id,
                    'cpmk_id' => $q['cpmk_id'],
                    'question_text' => $q['question_text'],
                    'created_by' => Auth::id(),
                ]);

                ExamQuestion::create([
                    'proposal_id' => $proposal->id,
                    'question_id' => $question->id,
                    'order_no' => $orderCounter++,
                    'weight' => $q['weight'],
                ]);
            }

            DB::commit();
            return redirect()->route('monevakademik.tashih.index')->with('success', 'Pengajuan ujian berhasil dibuat dan dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pengajuan: ' . $e->getMessage());
        }
    }

    public function show($uuid)
    {
        return redirect()->route('monevakademik.tashih.index');
    }

    public function edit($uuid)
    {
        return redirect()->route('monevakademik.tashih.index');
    }

    public function update(Request $request, $uuid)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'U')) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengajuan.');
        }

        $request->validate([
            'exam_type' => 'required|in:UTS,UAS',
            'period_id' => 'required',
            'questions' => 'required|array',
        ]);

        if (collect($request->questions)->sum('weight') != 100) {
            return back()->with('error', 'Total bobot soal harus tepat 100!');
        }

        DB::beginTransaction();
        try {
            $proposal = ExamProposal::where('uuid', $uuid)->firstOrFail();

            if (Auth::id() != $proposal->created_by) {
                return redirect()->route('monevakademik.tashih.index')->with('error', 'Anda tidak memiliki hak akses untuk mengedit pengajuan ini.');
            }

            $proposal->update([
                'exam_type' => $request->exam_type,
                'period_id' => $request->period_id,
                'status' => 'SUBMITTED',
            ]);

            ExamQuestion::where('proposal_id', $proposal->id)->delete();

            $orderCounter = 1;

            foreach ($request->questions as $q) {
                $question = Question::create([
                    'course_id' => $proposal->course_id,
                    'cpmk_id' => $q['cpmk_id'],
                    'question_text' => $q['question_text'],
                    'created_by' => Auth::id(),
                ]);

                ExamQuestion::create([
                    'proposal_id' => $proposal->id,
                    'question_id' => $question->id,
                    'order_no' => $orderCounter++,
                    'weight' => $q['weight'],
                ]);
            }

            DB::commit();
            return redirect()->route('monevakademik.tashih.index')->with('success', 'Pengajuan ujian berhasil diperbarui dan dikirim ulang!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui pengajuan: ' . $e->getMessage());
        }
    }

    public function destroy($uuid)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'D')) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pengajuan.');
        }

        $proposal = ExamProposal::where('uuid', $uuid)->firstOrFail();

        if (Auth::id() != $proposal->created_by || !in_array($proposal->status, ['SUBMITTED', 'REVISED'])) {
            return redirect()->route('monevakademik.tashih.index')->with('error', 'Pengajuan ini tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            \Modules\MonevAkademik\App\Models\ExamReview::where('proposal_id', $proposal->id)->delete();
            ExamQuestion::where('proposal_id', $proposal->id)->delete();
            $proposal->delete();

            DB::commit();
            return redirect()->route('monevakademik.tashih.index')->with('success', 'Pengajuan berhasil dibatalkan dan dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }

    public function print($uuid)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'E')) {
            abort(403, 'Anda tidak memiliki izin untuk mencetak dokumen ini.');
        }

        $proposal = ExamProposal::with(['course', 'creator', 'examQuestions.question'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($proposal->status !== 'APPROVED') {
            return back()->with('error', 'Soal belum disetujui, tidak dapat dicetak!');
        }

        // AMBIL REVIEW TERAKHIR (Tanpa ngecek kolom status di tabel review)
        $approval = \Modules\MonevAkademik\App\Models\ExamReview::where('proposal_id', $proposal->id)
            ->latest()
            ->first();

        // Cari User Kaprodi berdasarkan ID yang ada di $approval
        $kaprodi = $approval ? \App\Models\User::find($approval->reviewer_id) : null;

        // Load Logo Base64
        $logoPath = public_path('assets/images/uin.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('monevakademik::tashih.print', compact('proposal', 'kaprodi', 'logoBase64'));
        $pdf->setPaper('a4', 'portrait');

        $safeCourseName = str_replace(['/', '\\'], '_', $proposal->course->course_name);

        return $pdf->stream('Soal_Ujian_' . $safeCourseName . '.pdf');
    }
}