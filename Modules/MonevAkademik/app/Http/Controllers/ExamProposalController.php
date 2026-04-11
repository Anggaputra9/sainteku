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
use App\Models\Period;
use Modules\MonevAkademik\App\Models\ExamQuestionLog;
use Illuminate\Support\Facades\Storage;

class ExamProposalController extends Controller
{
    private $moduleId = 3;
    public function index()
    {
        $user = Auth::user();
        $periods = DB::table('mst_period')->orderBy('name', 'desc')->get();
        // Ambil matkul sesuai unit dosen
        $myCourses = MstCourse::where('unit_id', $user->unit_id)->get();

        // Pilihan CPMK untuk form
        $cpmkList = MstCpmk::where('is_active', '1')->get();

        // Load semua relasi biar JSON-nya komplit buat Alpine SPA
        $myProposals = ExamProposal::with(['course', 'creator', 'examQuestions.question', 'reviews.reviewer', 'logs.user'])
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
            $reviewQueue = ExamProposal::with(['course', 'creator', 'examQuestions.question', 'reviews.reviewer', 'logs.user'])
                ->where('status', 'SUBMITTED')
                ->whereHas('course', function ($query) use ($user) {
                    $query->where('unit_id', $user->unit_id);
                })
                ->latest()
                ->get();
        }

        return view('monevakademik::tashih.index', compact('periods', 'myCourses', 'myProposals', 'isReviewer', 'reviewQueue', 'cpmkList'));
    }

    public function create($course_id)
    {
        return redirect()->route('monevakademik.tashih.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'exam_type' => 'required|in:UTS,UAS',
            'period_id' => 'required',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required',
            'questions.*.cpmk_id' => 'required|array', // Validasi Array
            'questions.*.weight' => 'required|numeric',
        ]);

        if (collect($request->questions)->sum('weight') != 100) {
            return back()->with('error', 'Total bobot harus 100!');
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

            foreach ($request->questions as $key => $q) {
                $imagePath = null;
                if ($request->hasFile("questions.{$key}.image")) {
                    $imagePath = $request->file("questions.{$key}.image")->store('soal_images', 'public');
                }

                $question = Question::create([
                    'course_id' => $request->course_id,
                    'cpmk_id' => $q['cpmk_id'], // Otomatis jadi JSON karena casting
                    'question_text' => $q['question_text'],
                    'image_path' => $imagePath,
                    'created_by' => Auth::id(),
                ]);

                ExamQuestion::create([
                    'proposal_id' => $proposal->id,
                    'question_id' => $question->id,
                    'order_no' => $loop->iteration ?? 1, // Atau pake counter
                    'weight' => $q['weight'],
                ]);
            }

            DB::commit();
            return redirect()->route('monevakademik.tashih.index')->with('success', 'Berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function storeComment(Request $request)
    {
        // Langsung panggil ExamQuestionLog karena udah di-import di atas
        $log = ExamQuestionLog::create([
            'proposal_id' => $request->proposal_id,
            'order_no' => $request->order_no,
            'user_id' => Auth::id(),
            'type' => 'Komentar Kaprodi',
            'message' => $request->message
        ]);

        $log->load('user');

        return response()->json([
            'success' => true,
            'log' => $log
        ]);
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
            'questions.*.image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

            // 1. Ambil data soal yang LAMA untuk komparasi & ambil path gambar lama
            $oldQuestions = ExamQuestion::with('question')
                ->where('proposal_id', $proposal->id)
                ->get()
                ->keyBy('order_no');

            $orderCounter = 1;

            // 2. Looping data soal BARU untuk Logging
            foreach ($request->questions as $key => $q) {
                $oldEq = $oldQuestions->get($orderCounter);

                if ($oldEq) {
                    $changes = [];
                    if ($oldEq->question->question_text != $q['question_text'])
                        $changes[] = "Teks pertanyaan diperbarui";
                    if ($oldEq->weight != $q['weight'])
                        $changes[] = "Bobot diubah";

                    if ($request->hasFile("questions.{$key}.image")) {
                        $changes[] = "Gambar ilustrasi diperbarui";
                    }

                    if (!empty($changes)) {
                        ExamQuestionLog::create([
                            'proposal_id' => $proposal->id,
                            'order_no' => $orderCounter,
                            'user_id' => Auth::id(),
                            'type' => 'Revisi Dosen',
                            'message' => implode(', ', $changes)
                        ]);
                    }
                } else {
                    ExamQuestionLog::create([
                        'proposal_id' => $proposal->id,
                        'order_no' => $orderCounter,
                        'user_id' => Auth::id(),
                        'type' => 'Revisi Dosen',
                        'message' => 'Menambahkan butir soal baru.'
                    ]);
                }
                $orderCounter++;
            }

            // 3. Update Status Proposal
            $proposal->update([
                'exam_type' => $request->exam_type,
                'period_id' => $request->period_id,
                'status' => 'SUBMITTED',
            ]);

            // 4. Re-Insert Soal (Hapus link lama, buat Question baru)
            ExamQuestion::where('proposal_id', $proposal->id)->delete();

            $reinsertCounter = 1;
            foreach ($request->questions as $key => $q) {
                $oldEq = $oldQuestions->get($reinsertCounter);
                $imagePath = $oldEq ? $oldEq->question->image_path : null;

                // Jika ada upload gambar baru, simpan yang baru & hapus yang lama (opsional)
                if ($request->hasFile("questions.{$key}.image")) {
                    if ($imagePath)
                        Storage::disk('public')->delete($imagePath);
                    $imagePath = $request->file("questions.{$key}.image")->store('soal_images', 'public');
                }

                $question = Question::create([
                    'course_id' => $proposal->course_id,
                    'cpmk_id' => $q['cpmk_id'],
                    'question_text' => $q['question_text'],
                    'image_path' => $imagePath,
                    'created_by' => Auth::id(),
                ]);

                ExamQuestion::create([
                    'proposal_id' => $proposal->id,
                    'question_id' => $question->id,
                    'order_no' => $reinsertCounter++,
                    'weight' => $q['weight'],
                ]);
            }

            DB::commit();
            return redirect()->route('monevakademik.tashih.index')->with('success', 'Pengajuan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
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
            // 1. Ambil relasi ExamQuestion beserta Question-nya untuk ngecek path gambar
            $examQuestions = ExamQuestion::with('question')->where('proposal_id', $proposal->id)->get();

            // 2. Looping untuk menghapus file fisik gambar di Storage
            foreach ($examQuestions as $eq) {
                if ($eq->question && $eq->question->image_path) {
                    // Cek apakah filenya beneran ada di disk 'public' biar ga error pas mau dihapus
                    if (Storage::disk('public')->exists($eq->question->image_path)) {
                        Storage::disk('public')->delete($eq->question->image_path);
                    }

                    /* * CATATAN PENTING:
                     * Kalo soal ini dibikin khusus buat pengajuan ini aja (bukan dari Bank Soal), 
                     * kamu bisa hapus data tabel 'questions'-nya sekalian biar db ga kotor:
                     * * $eq->question->delete(); 
                     * * TAPI kalau sistemnya ngambil dari Bank Soal (dipakai rame-rame), 
                     * JANGAN di-delete data 'question'-nya, cukup file image atau pivotnya aja.
                     */
                }
            }

            // 3. Hapus relasi Pivot & Review
            \Modules\MonevAkademik\App\Models\ExamReview::where('proposal_id', $proposal->id)->delete();
            ExamQuestion::where('proposal_id', $proposal->id)->delete();

            // 4. Hapus Dokumen Utama
            $proposal->delete();

            DB::commit();
            return redirect()->route('monevakademik.tashih.index')->with('success', 'Pengajuan beserta lampiran berhasil dibatalkan dan dihapus.');
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

        $proposal = ExamProposal::with([
            'course',
            'creator',
            'period',
            'examQuestions.question'
        ])
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($proposal->status !== 'APPROVED') {
            return back()->with('error', 'Soal belum disetujui, tidak dapat dicetak!');
        }

        // ==============================================================
        // BYPASS PRODI: Tembak Langsung ke DB (Jaminan 100% Anti Error)
        // ==============================================================
        $unitName = '-';
        if ($proposal->course && $proposal->course->unit_id) {
            $unit = \Illuminate\Support\Facades\DB::table('mst_unit')
                ->where('id', $proposal->course->unit_id)
                ->first();
            if ($unit) {
                $unitName = $unit->unit_name;
            }
        }

        // Ambil Review Terakhir
        $approval = \Modules\MonevAkademik\App\Models\ExamReview::where('proposal_id', $proposal->id)
            ->latest()
            ->first();

        $kaprodi = $approval ? \App\Models\User::find($approval->reviewer_id) : null;

        // Path Logo UIN
        $logoPath = public_path('assets/images/uin.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true
        ])
            // PASSING VARIABEL $unitName KE VIEW
            ->loadView('monevakademik::tashih.print', compact('proposal', 'kaprodi', 'logoBase64', 'unitName'))
            ->setPaper('a4', 'portrait');

        $safeCourseName = str_replace(['/', '\\', ' '], '_', $proposal->course->course_name ?? 'Mata_Kuliah');

        return $pdf->stream('Soal_Ujian_' . $safeCourseName . '.pdf');
    }
}