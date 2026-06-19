<?php

namespace Modules\MonevAkademik\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\MonevAkademik\app\Models\ExamProposal;
use Modules\MonevAkademik\app\Models\ExamQuestion;
use Modules\MonevAkademik\app\Models\Question;
use App\Models\MstCourse;
use App\Models\MstCpmk;
use App\Models\Period;
use Modules\MonevAkademik\app\Models\ExamQuestionLog;
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
        // --- LOGIC BARU: AMBIL MATKUL BERDASARKAN MULTI-UNIT DOSEN ---
        // 1. Kumpulin semua ID Unit yang boleh diakses user (Utama + Tambahan)
        $userUnitIds = DB::table('mst_user_unit')->where('user_id', $user->id)->pluck('unit_id')->toArray();
        $userUnitIds[] = $user->unit_id; // Masukin unit utamanya juga
        $userUnitIds = array_unique($userUnitIds);

        // 2. Ambil data unit user buat ngecek dia level apa (Univ / Fak / Prodi)
        $userUnits = DB::table('mst_unit')->whereIn('id', $userUnitIds)->get();
        $isUniv = $userUnits->where('unit_type_id', 1)->isNotEmpty();
        $fakultasIds = $userUnits->where('unit_type_id', 2)->pluck('id')->toArray();

        // 3. Tarik Mata Kuliah + Join ke Unit & Parent Unit (biar gampang difilter di frontend)
        $queryCourse = MstCourse::query()
            ->join('mst_unit as prodi', 'mst_course.unit_id', '=', 'prodi.id')
            ->leftJoin('mst_unit as fakultas', 'prodi.unit_parent', '=', 'fakultas.id')
            ->select(
                'mst_course.id',
                'mst_course.course_name',
                'prodi.id as prodi_id',
                'prodi.unit_name as prodi_name',
                'fakultas.id as fakultas_id',
                'fakultas.unit_name as fakultas_name'
            );

        // Kalau dia BUKAN orang Universitas, batasin matkulnya
        if (!$isUniv) {
            $queryCourse->where(function ($q) use ($userUnitIds, $fakultasIds) {
                // Matkul milik Prodi yang ID-nya ada di daftar unit user
                $q->whereIn('mst_course.unit_id', $userUnitIds)
                    // ATAU Matkul milik Prodi yang bernaung di bawah Fakultas si user
                    ->orWhereIn('prodi.unit_parent', $fakultasIds);
            });
        }

        $myCourses = $queryCourse->get();
        // -------------------------------------------------------------

        $isReviewer = $user->roles()->whereIn('role_name', [
            'Kaprodi',
            'Reviewer Internal',
            'Reviewer External',
        ])->exists();

        $reviewQueueCount = 0;
        if ($isReviewer) {
            $reviewQueueCount = ExamProposal::where('status', 'SUBMITTED')
                ->whereHas('course', fn ($query) => $query->where('unit_id', $user->unit_id))
                ->count();
        }

        return response()
            ->view('monevakademik::tashih.index', [
                ...compact('periods', 'myCourses', 'isReviewer', 'reviewQueueCount'),
                'title' => 'Tashih Soal & Pengajuan Review',
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function getCpmkForCourse(string $course_id)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        abort_unless(
            MstCourse::where('id', $course_id)->exists(),
            404,
            'Mata kuliah tidak ditemukan.',
        );

        $cpmks = MstCpmk::forCourse($course_id, true)
            ->get(['id', 'name', 'is_active']);

        return response()->json($cpmks)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function getMyProposalsData(Request $request)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $query = ExamProposal::with(['course', 'creator', 'period'])
            ->where('created_by', Auth::id());

        $documents = $this->buildProposalsQuery($request, $query)
            ->paginate($perPage)
            ->through(fn (ExamProposal $prop): array => $this->formatProposalForList($prop));

        return response()->json($documents)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function getReviewQueueData(Request $request)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        $isReviewer = $user->roles()->whereIn('role_name', [
            'Kaprodi',
            'Reviewer Internal',
            'Reviewer External',
        ])->exists();

        if (! $isReviewer) {
            abort(403, 'Unauthorized');
        }

        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $query = ExamProposal::with(['course', 'creator', 'period'])
            ->where('status', 'SUBMITTED')
            ->whereHas('course', fn ($q) => $q->where('unit_id', $user->unit_id));

        $documents = $this->buildProposalsQuery($request, $query)
            ->paginate($perPage)
            ->through(fn (ExamProposal $prop): array => $this->formatProposalForList($prop));

        return response()->json($documents)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function getProposalDetail(string $uuid)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $proposal = ExamProposal::with([
            'course',
            'creator',
            'period',
            'examQuestions.question',
            'reviews.reviewer',
            'logs.user',
        ])->where('uuid', $uuid)->firstOrFail();

        $user = Auth::user();
        $isReviewer = $user->roles()->whereIn('role_name', [
            'Kaprodi',
            'Reviewer Internal',
            'Reviewer External',
        ])->exists();

        $canAccess = $proposal->created_by === $user->id
            || ($isReviewer && $proposal->status === 'SUBMITTED'
                && $proposal->course && $proposal->course->unit_id === $user->unit_id);

        if (! $canAccess) {
            abort(403, 'Unauthorized');
        }

        return response()->json($proposal);
    }

    private function buildProposalsQuery(Request $request, $query)
    {
        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('exam_type', 'like', '%' . $search . '%')
                    ->orWhereHas('course', fn ($c) => $c->where('course_name', 'like', '%' . $search . '%'))
                    ->orWhereHas('creator', fn ($u) => $u->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('period', fn ($p) => $p->where('name', 'like', '%' . $search . '%'));
            });
        }

        $status = (string) $request->query('status', '');
        if ($status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        $examType = (string) $request->query('exam_type', '');
        if ($examType !== '') {
            $query->where('exam_type', $examType);
        }

        $sort = (string) $request->query('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('created_at')->orderBy('id'),
            'exam_asc' => $query->orderBy('exam_type'),
            'exam_desc' => $query->orderByDesc('exam_type'),
            'course_asc' => $query->orderBy(
                MstCourse::select('course_name')->whereColumn('mst_course.id', 'trx_exam_proposals.course_id')
            ),
            'course_desc' => $query->orderByDesc(
                MstCourse::select('course_name')->whereColumn('mst_course.id', 'trx_exam_proposals.course_id')
            ),
            default => $query->latest(),
        };

        return $query;
    }

    private function formatProposalForList(ExamProposal $prop): array
    {
        $courseName = $prop->course->course_name ?? 'Mata Kuliah';

        return [
            'id' => $prop->id,
            'uuid' => $prop->uuid,
            'course_name' => $courseName,
            'exam_type' => $prop->exam_type,
            'period_name' => $prop->period->name ?? '-',
            'status' => $prop->status,
            'creator_name' => $prop->creator->name ?? 'Dosen',
            'initial' => mb_strtoupper(mb_substr($courseName, 0, 1)),
            'created_at' => $prop->created_at?->format('d M Y') ?? '-',
        ];
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

        if (! $this->questionsHaveValidCpmk($request->course_id, $request->questions)) {
            return back()->with('error', 'Salah satu CPMK tidak valid untuk mata kuliah ini.');
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
                    'click_action' => 'open_tashih_modal',
                    'send_whatsapp' => true,
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

            if (! $this->questionsHaveValidCpmk($proposal->course_id, $request->questions)) {
                return back()->with('error', 'Salah satu CPMK tidak valid untuk mata kuliah ini.');
            }

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

            \Modules\MonevAkademik\app\Models\ExamReview::where('proposal_id', $proposal->id)->delete();
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
        if (! Auth::user()->hasPermission($this->moduleId, 'E')) {
            abort(403, 'Anda tidak memiliki izin untuk mencetak dokumen ini.');
        }

        ini_set('memory_limit', '256M');

        $proposal = ExamProposal::with([
            'course',
            'creator',
            'period',
            'examQuestions.question',
        ])
            ->where('uuid', $uuid)
            ->firstOrFail();

        if ($proposal->status !== 'APPROVED') {
            return back()->with('error', 'Soal belum disetujui, tidak dapat dicetak!');
        }

        $unitName = '-';
        if ($proposal->course && $proposal->course->unit_id) {
            $unit = DB::table('mst_unit')
                ->where('id', $proposal->course->unit_id)
                ->first();
            if ($unit) {
                $unitName = $unit->unit_name;
            }
        }

        $approval = \Modules\MonevAkademik\app\Models\ExamReview::where('proposal_id', $proposal->id)
            ->latest()
            ->first();

        $kaprodi = $approval ? \App\Models\User::find($approval->reviewer_id) : null;

        $logoBase64 = $this->optimizeImageForPdf($this->resolvePrintLogoPath(), 90, 90) ?? '';
        $creatorSignature = $this->resolveSignatureDataUri($proposal->creator->signature ?? null, 200, 80);
        $kaprodiSignature = $this->resolveSignatureDataUri($kaprodi->signature ?? null, 200, 80);

        $questionImages = [];
        foreach ($proposal->examQuestions as $examQuestion) {
            $imagePath = $examQuestion->question->image_path ?? null;
            if (! $imagePath) {
                continue;
            }

            $physicalPath = storage_path('app/public/' . ltrim($imagePath, '/'));
            $optimized = $this->optimizeImageForPdf($physicalPath, 600, 250);
            if ($optimized) {
                $questionImages[$examQuestion->question->id] = $optimized;
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96,
        ])
            ->loadView('monevakademik::tashih.print', compact(
                'proposal',
                'kaprodi',
                'logoBase64',
                'unitName',
                'questionImages',
                'creatorSignature',
                'kaprodiSignature',
            ))
            ->setPaper('a4', 'portrait');

        $safeCourseName = str_replace(['/', '\\', ' '], '_', $proposal->course->course_name ?? 'Mata_Kuliah');

        return $pdf->stream('Soal_Ujian_' . $safeCourseName . '.pdf');
    }

    private function resolveSignatureDataUri(?string $signature, int $maxWidth = 200, int $maxHeight = 80): ?string
    {
        if (! $signature) {
            return null;
        }

        if (str_starts_with($signature, 'data:image')) {
            return $this->optimizeDataUriImage($signature, $maxWidth, $maxHeight);
        }

        if (str_starts_with($signature, '/storage/') || str_starts_with($signature, 'storage/')) {
            $relative = ltrim(str_replace('/storage/', '', $signature), '/');

            return $this->optimizeImageForPdf(storage_path('app/public/' . $relative), $maxWidth, $maxHeight);
        }

        if (is_file($signature)) {
            return $this->optimizeImageForPdf($signature, $maxWidth, $maxHeight);
        }

        return null;
    }

    private function optimizeDataUriImage(string $dataUri, int $maxWidth, int $maxHeight): ?string
    {
        if (! preg_match('/^data:image\/(\w+);base64,(.+)$/', $dataUri, $matches)) {
            return null;
        }

        $binary = base64_decode($matches[2], true);
        if ($binary === false) {
            return null;
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'sig_');
        if ($tempPath === false) {
            return null;
        }

        file_put_contents($tempPath, $binary);
        $optimized = $this->optimizeImageForPdf($tempPath, $maxWidth, $maxHeight);
        @unlink($tempPath);

        return $optimized;
    }

    private function resolvePrintLogoPath(): string
    {
        $optimizedLogo = public_path('assets/images/uin-print.jpg');
        if (is_file($optimizedLogo)) {
            return $optimizedLogo;
        }

        $sourceLogo = public_path('assets/images/uin.png');
        if (is_file($sourceLogo)) {
            $this->resizeImageWithMagick($sourceLogo, $optimizedLogo, 90, 90);
        }

        return is_file($optimizedLogo) ? $optimizedLogo : $sourceLogo;
    }

    private function optimizeImageForPdf(?string $absolutePath, int $maxWidth = 600, int $maxHeight = 250): ?string
    {
        if (! $absolutePath || ! is_file($absolutePath)) {
            return null;
        }

        if (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg')) {
            return $this->optimizeImageWithGd($absolutePath, $maxWidth, $maxHeight);
        }

        return $this->optimizeImageWithMagickDataUri($absolutePath, $maxWidth, $maxHeight);
    }

    private function optimizeImageWithGd(string $absolutePath, int $maxWidth, int $maxHeight): ?string
    {
        $imageInfo = @getimagesize($absolutePath);
        if ($imageInfo === false) {
            return null;
        }

        [$width, $height, $type] = $imageInfo;
        $ratio = min($maxWidth / max($width, 1), $maxHeight / max($height, 1), 1);
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($absolutePath),
            IMAGETYPE_PNG => @imagecreatefrompng($absolutePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolutePath) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($absolutePath),
            default => false,
        };

        if ($src === false) {
            return null;
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        if ($dst === false) {
            imagedestroy($src);

            return null;
        }

        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagejpeg($dst, null, 80);
        $jpegData = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        if ($jpegData === false || $jpegData === '') {
            return null;
        }

        return 'data:image/jpeg;base64,' . base64_encode($jpegData);
    }

    private function optimizeImageWithMagickDataUri(string $absolutePath, int $maxWidth, int $maxHeight): ?string
    {
        $tempOut = tempnam(sys_get_temp_dir(), 'pdfimg_');
        if ($tempOut === false) {
            return null;
        }

        $tempJpg = $tempOut . '.jpg';
        @unlink($tempOut);

        if (! $this->resizeImageWithMagick($absolutePath, $tempJpg, $maxWidth, $maxHeight)) {
            @unlink($tempJpg);

            return null;
        }

        $jpegData = file_get_contents($tempJpg);
        @unlink($tempJpg);

        if ($jpegData === false || $jpegData === '') {
            return null;
        }

        return 'data:image/jpeg;base64,' . base64_encode($jpegData);
    }

    private function resizeImageWithMagick(string $source, string $destination, int $maxWidth, int $maxHeight): bool
    {
        $convert = '/usr/bin/convert';
        if (! is_executable($convert) || ! is_file($source)) {
            return false;
        }

        $command = sprintf(
            '%s %s -auto-orient -resize %dx%d> -strip -quality 80 %s 2>/dev/null',
            escapeshellarg($convert),
            escapeshellarg($source),
            $maxWidth,
            $maxHeight,
            escapeshellarg($destination),
        );

        exec($command, $output, $exitCode);

        return $exitCode === 0 && is_file($destination);
    }

    private function questionsHaveValidCpmk(string $courseId, array $questions): bool
    {
        foreach ($questions as $question) {
            $cpmkIds = $question['cpmk_id'] ?? [];

            if (! MstCpmk::validateIdsForCourse($courseId, is_array($cpmkIds) ? $cpmkIds : [])) {
                return false;
            }
        }

        return true;
    }
}