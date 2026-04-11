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
use App\Services\NotifService;

class ExamProposalController extends Controller
{
    private $moduleId = 3;

    public function index()
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Anda tidak memiliki akses ke modul ini.');
        }
        $user = Auth::user();
        $periods = DB::table('mst_period')->orderBy('name', 'desc')->get();
        $myCourses = MstCourse::where('unit_id', $user->unit_id)->get();
        $cpmkList = MstCpmk::where('is_active', '1')->get();

        $myProposals = ExamProposal::with(['course', 'creator', 'examQuestions.question', 'reviews.reviewer', 'logs.user'])
            ->where('created_by', $user->id)
            ->latest()
            ->get();

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
        if (!Auth::user()->hasPermission($this->moduleId, 'C')) {
            abort(403, 'Anda tidak memiliki akses untuk membuat pengajuan.');
        }
        $request->validate([
            'course_id' => 'required',
            'exam_type' => 'required|in:UTS,UAS',
            'period_id' => 'required',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required',
            'questions.*.cpmk_id' => 'required|array', // Validasi Array sudah benar
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
                    'cpmk_id' => $q['cpmk_id'], // Otomatis jadi JSON karena model casts array
                    'question_text' => $q['question_text'],
                    'image_path' => $imagePath,
                    'created_by' => Auth::id(),
                ]);

                ExamQuestion::create([
                    'proposal_id' => $proposal->id,
                    'question_id' => $question->id,
                    'order_no' => $loop->iteration ?? 1,
                    'weight' => $q['weight'],
                ]);
            }

            DB::commit();
            NotifService::sendToApprovers(
                'RVW_SL', // Kode Modul Review Soal
                'A',      // Kode Permission Approve
                Auth::user()->unit_id,
                [
                    'action' => 'mengajukan review untuk soal',
                    'item_name' => $proposal->exam_type . ' (' . ($proposal->course->course_name ?? 'Matkul') . ')',
                    'type' => 'Tashih Soal',
                    'url' => route('monevakademik.tashih.index'),
                    'reference_id' => $proposal->uuid,
                    'click_action' => 'open_tashih_modal'
                ]
            );
            return redirect()->route('monevakademik.tashih.index')->with('success', 'Berhasil dikirim!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function storeComment(Request $request)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'U')) {
            abort(403, 'Anda tidak memiliki akses untuk memberikan komentar.');
        }
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
            'questions.*.cpmk_id' => 'required|array', // TAMBAHAN: Biar ga error pas update
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

            $oldQuestions = ExamQuestion::with('question')
                ->where('proposal_id', $proposal->id)
                ->get()
                ->keyBy('order_no');

            $orderCounter = 1;

            foreach ($request->questions as $key => $q) {
                $oldEq = $oldQuestions->get($orderCounter);

                if ($oldEq) {
                    $changes = [];
                    if ($oldEq->question->question_text != $q['question_text']) {
                        $changes[] = "Teks pertanyaan diperbarui";
                    }
                    if ($oldEq->weight != $q['weight']) {
                        $changes[] = "Bobot diubah";
                    }

                    // Cek perubahan array CPMK
                    $oldCpmk = is_array($oldEq->question->cpmk_id) ? $oldEq->question->cpmk_id : (json_decode($oldEq->question->cpmk_id, true) ?? []);
                    $newCpmk = $q['cpmk_id'];
                    sort($oldCpmk);
                    sort($newCpmk);
                    if ($oldCpmk !== $newCpmk) {
                        $changes[] = "Target CPMK diubah";
                    }

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

            $proposal->update([
                'exam_type' => $request->exam_type,
                'period_id' => $request->period_id,
                'status' => 'SUBMITTED',
            ]);

            ExamQuestion::where('proposal_id', $proposal->id)->delete();

            $reinsertCounter = 1;
            foreach ($request->questions as $key => $q) {
                $oldEq = $oldQuestions->get($reinsertCounter);
                $imagePath = $oldEq ? $oldEq->question->image_path : null;

                if ($request->hasFile("questions.{$key}.image")) {
                    if ($imagePath) {
                        Storage::disk('public')->delete($imagePath);
                    }
                    $imagePath = $request->file("questions.{$key}.image")->store('soal_images', 'public');
                }

                $question = Question::create([
                    'course_id' => $proposal->course_id,
                    'cpmk_id' => $q['cpmk_id'], // Tetap array, model handle JSON
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
            $examQuestions = ExamQuestion::with('question')->where('proposal_id', $proposal->id)->get();

            foreach ($examQuestions as $eq) {
                if ($eq->question && $eq->question->image_path) {
                    if (Storage::disk('public')->exists($eq->question->image_path)) {
                        Storage::disk('public')->delete($eq->question->image_path);
                    }
                }
            }

            \Modules\MonevAkademik\App\Models\ExamReview::where('proposal_id', $proposal->id)->delete();
            ExamQuestion::where('proposal_id', $proposal->id)->delete();
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

        $unitName = '-';
        if ($proposal->course && $proposal->course->unit_id) {
            $unit = \Illuminate\Support\Facades\DB::table('mst_unit')
                ->where('id', $proposal->course->unit_id)
                ->first();
            if ($unit) {
                $unitName = $unit->unit_name;
            }
        }

        $approval = \Modules\MonevAkademik\App\Models\ExamReview::where('proposal_id', $proposal->id)
            ->latest()
            ->first();

        $kaprodi = $approval ? \App\Models\User::find($approval->reviewer_id) : null;

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
            ->loadView('monevakademik::tashih.print', compact('proposal', 'kaprodi', 'logoBase64', 'unitName'))
            ->setPaper('a4', 'portrait');

        $safeCourseName = str_replace(['/', '\\', ' '], '_', $proposal->course->course_name ?? 'Mata_Kuliah');

        return $pdf->stream('Soal_Ujian_' . $safeCourseName . '.pdf');
    }
}