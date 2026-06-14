<?php

namespace Modules\Ujian\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\MonevAkademik\app\Models\ExamProposal;
use Modules\Ujian\Models\ExamAttempt;
use Modules\Ujian\Models\ExamAttemptAnswer;
use Modules\Ujian\Models\ExamAttemptEvent;
use Modules\Ujian\Models\ExamRoom;

/**
 * RoomController
 * --------------
 * Mengelola CRUD ruang ujian. Untuk halaman ini frontend pakai
 * single-page Alpine modal, jadi action store/update/destroy/publish/close
 * boleh return JSON kalau request Accept: application/json.
 *
 * Resource binding pakai UUID (lihat ExamRoom::getRouteKeyName()),
 * jadi URL admin tidak menggunakan auto-increment id yang gampang ditebak.
 */
class RoomController extends Controller
{
    /* =========================================================
     | Guard
     |==========================================================*/
    private function guardLecturer(): void
    {
        $user = Auth::user();
        $allowed = $user && $user->roles()
            ->whereIn('role_code', ['ADM', 'DSN', 'KPD'])
            ->exists();

        abort_unless($allowed, 403, 'Hanya dosen / kaprodi / administrator yang boleh mengakses ruang ujian.');
    }

    private function isAdmin(): bool
    {
        return Auth::user()?->roles()->where('role_code', 'ADM')->exists() ?? false;
    }

    /* =========================================================
     | Index — list rooms (HTML view; JSON kalau diminta AJAX)
     |==========================================================*/
    public function index(Request $request)
    {
        $this->guardLecturer();

        $proposals = $this->getApprovedProposals();
        $proposalDefaults = $this->getProposalFilterDefaults($proposals);

        return view('ujian::rooms.index', compact('proposals', 'proposalDefaults'))
            ->with('title', 'Ruang Ujian');
    }

    public function getRoomsData(Request $request)
    {
        $this->guardLecturer();
        ExamRoom::autoStartScheduled();
        ExamRoom::autoCloseExpired();

        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $rooms = $this->buildRoomsQuery($request)
            ->paginate($perPage)
            ->through(fn (ExamRoom $room): array => $this->formatRoomForList($room));

        return response()->json($rooms)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /* =========================================================
     | Show JSON — dipakai modal Detail (load fresh data + attempts)
     |==========================================================*/
    public function show(ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        $room->autoStartIfScheduled();
        if ($room->autoCloseIfExpired()) {
            $room->refresh();
        }

        $room->load([
            'proposal.course:id,course_name',
            'proposal.period:id,name',
            'proposal.examQuestions',
        ]);

        $totalQuestions = $room->proposal->examQuestions->count();

        $attempts = ExamAttempt::with('user:id,name,identity_id')
            ->withCount(['answers as answered_count' => fn ($q) => $q->where('is_answered', true)])
            ->where('room_id', $room->id)
            ->orderByDesc('last_activity_at')
            ->orderByDesc('started_at')
            ->get()
            ->map(fn ($a) => [
                'uuid'             => $a->uuid,
                'user_name'        => $a->user?->name,
                'user_identity'    => $a->user?->identity_id,
                'status'           => $a->status,
                'status_label'     => $a->statusLabel(),
                'started_at'       => $a->started_at?->toDateTimeString(),
                'expires_at'       => $a->expires_at?->toDateTimeString(),
                'submitted_at'     => $a->submitted_at?->toDateTimeString(),
                'last_activity_at' => $a->last_activity_at?->toDateTimeString(),
                'tab_switch_count' => (int) $a->tab_switch_count,
                'answered'         => (int) $a->answered_count,
                'total_questions'  => $totalQuestions,
                'score'            => $a->score,
            ]);

        // Format datetime ke "Y-m-d H:i" + "d M Y H:i" supaya:
        //  - ramah ditampilkan langsung di UI (tanpa suffix "Z" / micro-detik)
        //  - tetap mudah dipakai untuk pre-fill input datetime-local saat edit
        return response()->json([
            'proposal_context' => $this->resolveProposalContext((int) $room->proposal_id),
            'room' => array_merge($room->toArray(), [
                'tab_switch_label' => $room->tabSwitchLabel(),
                'join_url'         => route('ujian.attempt.join'),
                'qr_payload'       => $room->room_code,
                'started_at'       => $room->started_at?->format('Y-m-d H:i:s'),
                'start_at'         => $room->start_at?->format('Y-m-d H:i:s'),
                'end_at'           => $room->end_at?->format('Y-m-d H:i:s'),
                'start_at_human'   => $room->start_at?->translatedFormat('d M Y H:i') . ' WIB',
                'started_at_human' => $room->started_at?->translatedFormat('d M Y H:i') . ' WIB',
                'end_at_human'     => $room->end_at?->translatedFormat('d M Y H:i') . ' WIB',
                'join_grace_minutes' => ExamRoom::JOIN_GRACE_MINUTES,
                'join_opens_at_human' => $room->joinOpensAt()?->translatedFormat('d M Y H:i') . ' WIB',
                'join_deadline_human' => $room->joinDeadline()?->translatedFormat('d M Y H:i') . ' WIB',
            ]),
            'total_questions' => $totalQuestions,
            'attempts'        => $attempts,
            'server_time'     => now()->toDateTimeString(),
        ]);
    }

    /* =========================================================
     | Store — return JSON / redirect
     |==========================================================*/
    public function store(Request $request)
    {
        $this->guardLecturer();
        $data = $this->validateRoom($request);
        $data['created_by'] = Auth::id();

        $room = ExamRoom::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "Ruang ujian dibuat. Kode: {$room->room_code}",
                'room'    => $room,
            ]);
        }
        return back()->with('success', "Ruang ujian dibuat. Kode: {$room->room_code}");
    }

    /* =========================================================
     | Update
     |==========================================================*/
    public function update(Request $request, ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        $hasAttempts = $room->attempts()->exists();
        $data = $this->validateRoom($request, $hasAttempts, $room);
        $room->update($data);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Ruang ujian diperbarui.', 'room' => $room->fresh()]);
        }
        return back()->with('success', 'Ruang ujian diperbarui.');
    }

    /* =========================================================
     | Destroy
     |==========================================================*/
    public function destroy(Request $request, ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        abort_if($room->attempts()->exists(), 422, 'Tidak bisa dihapus, sudah ada peserta ujian.');
        $room->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Ruang ujian dihapus.']);
        }
        return back()->with('success', 'Ruang ujian dihapus.');
    }

    public function destroyAttempt(Request $request, ExamRoom $room, ExamAttempt $attempt)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        abort_if($attempt->room_id !== $room->id, 404, 'Peserta ujian tidak ditemukan pada room ini.');

        DB::transaction(function () use ($attempt) {
            $attempt->answers()->delete();
            $attempt->events()->delete();
            $attempt->delete();
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Peserta ujian berhasil dihapus dari room.']);
        }

        return back()->with('success', 'Peserta ujian berhasil dihapus dari room.');
    }

    /* =========================================================
     | Status transition
     |==========================================================*/
    /**
     * Mulai ujian secara manual — mahasiswa dapat langsung masuk.
     * Dipakai dosen yang ingin mempercepat sebelum jadwal terjadwal.
     */
    public function start(Request $request, ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        abort_if($room->status !== 'DRAFT', 422, 'Ruang ujian sudah dimulai atau ditutup.');

        $now = now();
        $room->update([
            'started_at' => $now,
            'status'     => 'PUBLISHED',
            'is_active'  => true,
            'end_at'     => ExamRoom::computeEndAt($now, (int) $room->duration_minutes),
        ]);

        $fresh = $room->fresh();
        $message = "Ujian dimulai. Mahasiswa dapat masuk menggunakan kode {$room->room_code} "
            . "(batas masuk {$fresh->joinDeadline()?->translatedFormat('d M Y H:i')} WIB).";

        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'room' => $room->fresh()]);
        }

        return back()->with('success', $message);
    }

    public function close(Request $request, ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        // Auto-submit semua attempt yang masih ONGOING
        $ongoingAttempts = ExamAttempt::where('room_id', $room->id)
            ->where('status', 'ONGOING')
            ->get();

        foreach ($ongoingAttempts as $attempt) {
            $attempt->update([
                'status' => 'AUTO_SUBMITTED_TIME',
                'submitted_at' => now(),
            ]);

            // Log event
            ExamAttemptEvent::create([
                'attempt_id' => $attempt->id,
                'event_type' => 'auto_submit_room_closed',
                'payload' => ['reason' => 'Room closed by lecturer'],
                'occurred_at' => now(),
            ]);
        }

        $room->update(['status' => 'CLOSED', 'is_active' => false]);

        $message = 'Ruang ujian ditutup.';
        if ($ongoingAttempts->count() > 0) {
            $message .= ' ' . $ongoingAttempts->count() . ' peserta yang masih mengerjakan telah di-submit otomatis.';
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }
        return back()->with('success', $message);
    }

    /**
     * Reopen — buka kembali ruang ujian yang sudah CLOSED.
     *
     * Dipakai dosen ketika butuh memberi kesempatan tambahan (mis. mahasiswa
     * yang belum sempat ikut, atau perpanjangan waktu). Wajib mengirim
     * `end_at` baru yang ada di masa depan; bila tidak, room akan langsung
     * ter-auto-close lagi pada request berikutnya.
     *
     * Body params:
     *  - end_at (required, date, after:now)
     *  - duration_minutes (optional, override durasi attempt baru)
     */
    public function reopen(Request $request, ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        $data = $request->validate([
            'end_at'           => 'required|date|after:now',
            'duration_minutes' => 'nullable|integer|min:1|max:600',
        ]);

        $now = now();
        $duration = ! empty($data['duration_minutes'])
            ? (int) $data['duration_minutes']
            : (int) $room->duration_minutes;
        $endAt = Carbon::parse($data['end_at']);

        $update = [
            'started_at' => $now,
            'status'     => 'PUBLISHED',
            'is_active'  => true,
            'end_at'     => $endAt->gt($now) ? $endAt : ExamRoom::computeEndAt($now, $duration),
        ];

        if (! empty($data['duration_minutes'])) {
            $update['duration_minutes'] = $duration;
        }

        $room->update($update);

        $message = 'Ruang ujian dibuka kembali sampai '
            . $room->end_at->translatedFormat('d M Y H:i') . ' WIB.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'room'    => $room->fresh(),
            ]);
        }
        return back()->with('success', $message);
    }

    /**
     * Reset pelanggaran dan izinkan peserta melanjutkan ujian.
     *
     * Dipakai dosen untuk memberi kesempatan kedua kepada mahasiswa yang
     * ter-auto-submit karena pelanggaran (tab switch). Hanya bisa dilakukan
     * jika ruang ujian masih PUBLISHED dan belum melewati end_at.
     */
    public function resetViolation(Request $request, ExamRoom $room, ExamAttempt $attempt)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        // Validasi: attempt harus milik room ini
        abort_if($attempt->room_id !== $room->id, 404, 'Peserta ujian tidak ditemukan pada room ini.');

        // Validasi: hanya bisa reset jika status AUTO_SUBMITTED_VIOLATION
        abort_if(
            $attempt->status !== 'AUTO_SUBMITTED_VIOLATION',
            422,
            'Hanya peserta yang ter-auto-submit karena pelanggaran yang bisa di-reset.'
        );

        // Validasi: ruang ujian harus masih aktif
        abort_if(
            $room->status !== 'PUBLISHED' || !$room->is_active,
            422,
            'Ruang ujian sudah ditutup, tidak bisa reset pelanggaran.'
        );

        // Validasi: waktu ujian harus masih berlangsung
        $now = now();
        abort_if(
            $now->gt($room->end_at),
            422,
            'Waktu ujian sudah berakhir, tidak bisa reset pelanggaran.'
        );

        // Reset attempt ke ONGOING
        $newExpiresAt = $this->calculateExpiresAt($room, $now);

        $attempt->update([
            'status' => 'ONGOING',
            'submitted_at' => null,
            'tab_switch_count' => 0,
            'expires_at' => $newExpiresAt,
            'last_activity_at' => $now,
        ]);

        // Log event
        ExamAttemptEvent::create([
            'attempt_id' => $attempt->id,
            'event_type' => 'violation_reset',
            'payload' => [
                'reset_by' => Auth::id(),
                'reset_at' => $now->toDateTimeString(),
                'new_expires_at' => $newExpiresAt->toDateTimeString(),
            ],
            'occurred_at' => $now,
        ]);

        $message = 'Pelanggaran di-reset. Peserta dapat melanjutkan ujian sampai '
            . $newExpiresAt->translatedFormat('d M Y H:i') . ' WIB.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'attempt' => $attempt->fresh(),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Grading - Dosen mengoreksi dan memberi nilai untuk jawaban mahasiswa.
     *
     * Endpoint ini menerima array scores yang berisi question_id, score, dan grader_note.
     * Total score attempt akan dihitung otomatis berdasarkan bobot soal.
     */
    public function gradeAttempt(Request $request, ExamRoom $room, ExamAttempt $attempt)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        // Validasi: attempt harus milik room ini
        abort_if($attempt->room_id !== $room->id, 404, 'Peserta ujian tidak ditemukan pada room ini.');

        // Validasi: attempt harus sudah selesai
        abort_if(
            !$attempt->isFinished(),
            422,
            'Hanya peserta yang sudah selesai ujian yang bisa dinilai.'
        );

        $data = $request->validate([
            'scores' => 'required|array',
            'scores.*.answer_id' => 'nullable|integer',
            'scores.*.score' => 'required|numeric|min:0|max:100',
            'scores.*.grader_note' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($attempt, $data) {
            // Update score untuk setiap jawaban
            foreach ($data['scores'] as $questionId => $scoreData) {
                $answer = ExamAttemptAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $questionId)
                    ->first();

                if ($answer) {
                    $answer->update([
                        'score' => $scoreData['score'],
                        'grader_note' => $scoreData['grader_note'] ?? null,
                        'grading_method' => 'manual',
                        'ai_feedback' => null,
                        'graded_by' => Auth::id(),
                        'graded_at' => now(),
                    ]);
                } else {
                    // Buat jawaban baru jika belum ada
                    ExamAttemptAnswer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $questionId,
                        'answer_text' => null,
                        'is_answered' => false,
                        'score' => $scoreData['score'],
                        'grader_note' => $scoreData['grader_note'] ?? null,
                        'grading_method' => 'manual',
                        'graded_by' => Auth::id(),
                        'graded_at' => now(),
                    ]);
                }
            }

            // Hitung total score berdasarkan bobot soal
            $room = $attempt->room;
            $room->load('proposal.examQuestions');
            $totalScore = 0;
            $totalWeight = 0;

            foreach ($room->proposal->examQuestions as $examQuestion) {
                $answer = ExamAttemptAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $examQuestion->question_id)
                    ->first();

                if ($answer && $answer->score !== null) {
                    $weight = $examQuestion->weight ?? 0;
                    $totalScore += ($answer->score * $weight / 100);
                    $totalWeight += $weight;
                }
            }

            // Simpan total score ke attempt
            $finalScore = $totalWeight > 0 ? $totalScore : 0;
            $attempt->update([
                'score' => $finalScore,
                'grader_note' => 'Dinilai oleh ' . Auth::user()->name . ' pada ' . now()->translatedFormat('d M Y H:i'),
            ]);
        });

        $message = 'Penilaian berhasil disimpan. Total skor: ' . number_format($attempt->fresh()->score, 2);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'attempt' => $attempt->fresh()->load('answers'),
            ]);
        }

        return back()->with('success', $message);
    }

    /* =========================================================
     | Live monitoring data (polling AJAX)
     |==========================================================*/
    public function liveMonitor(ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        // Polling juga merupakan kesempatan refresh status; auto-close
        // memastikan UI dosen langsung berubah tanpa harus reload halaman.
        $room->autoStartIfScheduled();
        if ($room->autoCloseIfExpired()) {
            $room->refresh();
        }

        $room->load('proposal.examQuestions');
        $totalQuestions = $room->proposal->examQuestions->count();

        $attempts = ExamAttempt::with('user:id,name,identity_id')
            ->withCount(['answers as answered_count' => fn ($q) => $q->where('is_answered', true)])
            ->where('room_id', $room->id)
            ->orderByDesc('last_activity_at')
            ->orderByDesc('started_at')
            ->get()
            ->map(fn ($a) => [
                'uuid'             => $a->uuid,
                'user_name'        => $a->user?->name,
                'user_identity'    => $a->user?->identity_id,
                'status'           => $a->status,
                'status_label'     => $a->statusLabel(),
                'started_at'       => $a->started_at?->toDateTimeString(),
                'expires_at'       => $a->expires_at?->toDateTimeString(),
                'submitted_at'     => $a->submitted_at?->toDateTimeString(),
                'last_activity_at' => $a->last_activity_at?->toDateTimeString(),
                'tab_switch_count' => (int) $a->tab_switch_count,
                'answered'         => (int) $a->answered_count,
                'total_questions'  => $totalQuestions,
            ]);

        return response()->json([
            'total_questions' => $totalQuestions,
            'attempts'        => $attempts,
            'server_time'     => now()->toDateTimeString(),
        ]);
    }

    /* =========================================================
     | Batch AI Grading
     |==========================================================*/

    /**
     * Mulai batch grading untuk semua attempt yang sudah selesai.
     * Proses berjalan sinkron dengan progress disimpan di cache.
     */
    public function gradeAllAttempts(Request $request, ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        $forceRegrade = $request->boolean('force_regrade', false);

        // Ambil semua attempt yang sudah selesai
        $attempts = ExamAttempt::with(['user:id,name,identity_id', 'answers'])
            ->where('room_id', $room->id)
            ->whereIn('status', ['SUBMITTED', 'AUTO_SUBMITTED_TIME', 'AUTO_SUBMITTED_VIOLATION'])
            ->when(!$forceRegrade, fn($q) => $q->whereNull('score'))
            ->orderBy('submitted_at')
            ->get();

        if ($attempts->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada peserta yang perlu dikoreksi.',
            ], 422);
        }

        $room->load('proposal.examQuestions.question');
        $totalAttempts = $attempts->count();
        $totalQuestions = $room->proposal->examQuestions->count();
        $cacheKey = "room_{$room->uuid}_grading_progress";
        $cancelKey = "room_{$room->uuid}_grading_cancel";

        // Reset cancel flag
        Cache::forget($cancelKey);

        // Inisialisasi progress
        Cache::put($cacheKey, [
            'status' => 'processing',
            'current_attempt' => 0,
            'total_attempts' => $totalAttempts,
            'current_student' => '',
            'current_question' => 0,
            'total_questions' => $totalQuestions,
            'failed' => [],
            'message' => 'Memulai koreksi...',
        ], now()->addHours(2));

        $aiService = app(AiService::class);
        $failedAttempts = [];
        $processedCount = 0;

        foreach ($attempts as $index => $attempt) {
            // Cek cancel flag
            if (Cache::get($cancelKey)) {
                Cache::put($cacheKey, [
                    'status' => 'cancelled',
                    'current_attempt' => $processedCount,
                    'total_attempts' => $totalAttempts,
                    'current_student' => '',
                    'current_question' => 0,
                    'total_questions' => $totalQuestions,
                    'failed' => $failedAttempts,
                    'message' => 'Proses dibatalkan oleh pengguna.',
                ], now()->addMinutes(10));

                return response()->json([
                    'message' => 'Proses koreksi dibatalkan.',
                    'processed' => $processedCount,
                    'failed' => count($failedAttempts),
                ]);
            }

            $studentName = $attempt->user?->name ?? 'Unknown';
            $processedCount++;

            try {
                DB::beginTransaction();

                foreach ($room->proposal->examQuestions as $qIndex => $examQuestion) {
                    // Update progress
                    Cache::put($cacheKey, [
                        'status' => 'processing',
                        'current_attempt' => $processedCount,
                        'total_attempts' => $totalAttempts,
                        'current_student' => $studentName,
                        'current_question' => $qIndex + 1,
                        'total_questions' => $totalQuestions,
                        'failed' => $failedAttempts,
                        'message' => "Mengoreksi {$studentName} - Soal " . ($qIndex + 1) . " dari {$totalQuestions} (Peserta {$processedCount} dari {$totalAttempts})",
                    ], now()->addHours(2));

                    $answer = $attempt->answers->firstWhere('question_id', $examQuestion->question_id);

                    // Jika tidak ada jawaban atau kosong, beri nilai 0
                    if (!$answer || trim($answer->answer_text ?? '') === '') {
                        if (!$answer) {
                            $answer = ExamAttemptAnswer::create([
                                'attempt_id' => $attempt->id,
                                'question_id' => $examQuestion->question_id,
                                'answer_text' => null,
                                'is_answered' => false,
                            ]);
                        }

                        $answer->update([
                            'score' => 0,
                            'grading_method' => 'ai',
                            'ai_feedback' => 'Tidak dijawab - nilai otomatis 0',
                            'graded_by' => Auth::id(),
                            'graded_at' => now(),
                        ]);
                        continue;
                    }

                    // Koreksi dengan AI
                    $prompt = $this->buildGradingPrompt($examQuestion->question, $answer->answer_text);
                    $aiResponse = $aiService->sendMessage($prompt);
                    [$score, $feedback] = $this->parseAiResponse($aiResponse);

                    $answer->update([
                        'score' => $score,
                        'grading_method' => 'ai',
                        'ai_feedback' => $feedback,
                        'graded_by' => Auth::id(),
                        'graded_at' => now(),
                    ]);
                }

                // Hitung total score
                $totalScore = 0;
                $totalWeight = 0;

                foreach ($room->proposal->examQuestions as $examQuestion) {
                    $answer = ExamAttemptAnswer::where('attempt_id', $attempt->id)
                        ->where('question_id', $examQuestion->question_id)
                        ->first();

                    if ($answer && $answer->score !== null) {
                        $weight = $examQuestion->weight ?? 0;
                        $totalScore += ($answer->score * $weight / 100);
                        $totalWeight += $weight;
                    }
                }

                $finalScore = $totalWeight > 0 ? $totalScore : 0;
                $attempt->update([
                    'score' => $finalScore,
                    'grader_note' => 'Dikoreksi otomatis dengan AI pada ' . now()->translatedFormat('d M Y H:i'),
                ]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $failedAttempts[] = [
                    'uuid' => $attempt->uuid,
                    'student' => $studentName,
                    'error' => $e->getMessage(),
                ];
            }
        }

        // Selesai
        Cache::put($cacheKey, [
            'status' => 'completed',
            'current_attempt' => $totalAttempts,
            'total_attempts' => $totalAttempts,
            'current_student' => '',
            'current_question' => 0,
            'total_questions' => $totalQuestions,
            'failed' => $failedAttempts,
            'message' => 'Koreksi selesai!',
        ], now()->addMinutes(10));

        return response()->json([
            'message' => 'Koreksi selesai.',
            'processed' => $processedCount,
            'failed' => count($failedAttempts),
            'failed_details' => $failedAttempts,
        ]);
    }

    /**
     * Get progress koreksi batch (untuk polling).
     */
    public function gradingProgress(ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        $cacheKey = "room_{$room->uuid}_grading_progress";
        $progress = Cache::get($cacheKey);

        if (!$progress) {
            return response()->json([
                'status' => 'idle',
                'message' => 'Tidak ada proses koreksi yang sedang berjalan.',
            ]);
        }

        return response()->json($progress);
    }

    /**
     * Cancel proses koreksi batch.
     */
    public function cancelGrading(ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        $cancelKey = "room_{$room->uuid}_grading_cancel";
        Cache::put($cancelKey, true, now()->addMinutes(5));

        return response()->json([
            'message' => 'Permintaan pembatalan dikirim. Proses akan berhenti setelah soal saat ini selesai.',
        ]);
    }

    private function buildGradingPrompt($question, $answer): string
    {
        return "Kamu adalah asisten dosen yang bertugas mengoreksi jawaban ujian mahasiswa.\n\n"
            . "SOAL:\n{$question->question_text}\n\n"
            . "JAWABAN MAHASISWA:\n{$answer}\n\n"
            . "Tugasmu:\n"
            . "1. Baca dan pahami soal serta jawaban mahasiswa\n"
            . "2. Berikan nilai 0-100 berdasarkan:\n"
            . "   - Ketepatan jawaban (50%)\n"
            . "   - Kelengkapan penjelasan (30%)\n"
            . "   - Struktur dan kejelasan (20%)\n"
            . "3. Berikan feedback singkat (1-2 kalimat) yang konstruktif\n\n"
            . "Format respons:\n"
            . "NILAI: [angka 0-100]\n"
            . "FEEDBACK: [feedback singkat]";
    }

    private function parseAiResponse(string $response): array
    {
        $score = 0;
        $feedback = 'Tidak ada feedback dari AI.';

        if (preg_match('/NILAI:\s*(\d+(?:\.\d+)?)/i', $response, $matches)) {
            $score = min(100, max(0, (float) $matches[1]));
        }

        if (preg_match('/FEEDBACK:\s*(.+?)(?=\n\n|\n[A-Z]+:|$)/s', $response, $matches)) {
            $feedback = trim($matches[1]);
        }

        return [$score, $feedback];
    }

    /* =========================================================
     | Helpers
     |==========================================================*/
    private function buildRoomsQuery(Request $request)
    {
        $query = ExamRoom::with([
                'proposal.course:id,course_name',
                'proposal.period:id,name',
            ])
            ->withCount([
                'attempts',
                'attempts as attempts_finished_count' => function ($q) {
                    $q->whereIn('status', ['SUBMITTED', 'AUTO_SUBMITTED_TIME', 'AUTO_SUBMITTED_VIOLATION']);
                },
                'attempts as attempts_ongoing_count' => function ($q) {
                    $q->where('status', 'ONGOING');
                },
            ]);

        if (! $this->isAdmin()) {
            $query->where('created_by', Auth::id());
        }

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('room_code', 'like', "%{$search}%")
                    ->orWhereHas('proposal.course', fn ($qq) => $qq->where('course_name', 'like', "%{$search}%"));
            });
        }

        $status = $request->query('status');
        if (in_array($status, ['DRAFT', 'PUBLISHED', 'CLOSED'], true)) {
            $query->where('status', $status);
        }

        $sort = (string) $request->query('sort', 'newest');
        match ($sort) {
            'oldest'     => $query->orderBy('id'),
            'title_asc'  => $query->orderBy('title'),
            'title_desc' => $query->orderByDesc('title'),
            default      => $query->orderByDesc('id'),
        };

        return $query;
    }

    private function formatRoomForList(ExamRoom $room): array
    {
        return [
            'uuid'                     => $room->uuid,
            'title'                    => $room->title,
            'room_code'                => $room->room_code,
            'initial'                  => strtoupper(substr($room->title, 0, 1)),
            'course_name'              => $room->proposal->course->course_name ?? '-',
            'start_at'                 => $room->start_at?->format('d M Y H:i'),
            'end_at_time'              => $room->end_at?->format('H:i'),
            'duration_minutes'         => $room->duration_minutes,
            'tab_switch_label'         => $room->tabSwitchLabel(),
            'attempts_count'           => (int) $room->attempts_count,
            'attempts_ongoing_count'   => (int) $room->attempts_ongoing_count,
            'attempts_finished_count'  => (int) $room->attempts_finished_count,
            'status'                   => $room->status,
            'show_url'                 => route('ujian.rooms.show', $room->uuid),
            'update_url'               => route('ujian.rooms.update', $room->uuid),
            'delete_url'               => route('ujian.rooms.destroy', $room->uuid),
            'can_delete'               => (int) $room->attempts_count === 0,
        ];
    }

    private function getApprovedProposals()
    {
        $proposalsQuery = $this->buildApprovedProposalsQuery();

        if (! $this->isAdmin()) {
            $proposalsQuery->where('trx_exam_proposals.created_by', Auth::id());
        }

        return $proposalsQuery
            ->orderByDesc('trx_exam_proposals.id')
            ->get($this->approvedProposalSelectColumns())
            ->map(fn ($p) => $this->formatProposalItem($p))
            ->values();
    }

    private function buildApprovedProposalsQuery()
    {
        return ExamProposal::query()
            ->join('mst_course', 'trx_exam_proposals.course_id', '=', 'mst_course.id')
            ->join('mst_unit as prodi', 'mst_course.unit_id', '=', 'prodi.id')
            ->leftJoin('mst_unit as fakultas', 'prodi.unit_parent', '=', 'fakultas.id')
            ->leftJoin('mst_period', 'trx_exam_proposals.period_id', '=', 'mst_period.id')
            ->where('trx_exam_proposals.status', 'APPROVED');
    }

    private function approvedProposalSelectColumns(): array
    {
        return [
            'trx_exam_proposals.id',
            'trx_exam_proposals.exam_type',
            'trx_exam_proposals.course_id',
            'mst_course.course_name',
            'prodi.id as prodi_id',
            'prodi.unit_name as prodi_name',
            'fakultas.id as fakultas_id',
            'fakultas.unit_name as fakultas_name',
            'mst_period.name as period_name',
        ];
    }

    private function formatProposalItem(object $p): array
    {
        return [
            'id'            => $p->id,
            'exam_type'     => $p->exam_type,
            'course_id'     => $p->course_id,
            'course_name'   => $p->course_name,
            'prodi_id'      => $p->prodi_id,
            'prodi_name'    => $p->prodi_name,
            'fakultas_id'   => $p->fakultas_id,
            'fakultas_name' => $p->fakultas_name,
            'period_name'   => $p->period_name,
            'label'         => trim(
                ($p->course_name ?? 'Mata kuliah ?') . ' — ' . $p->exam_type
                . ($p->period_name ? ' (' . $p->period_name . ')' : '')
            ),
            'short_label'   => trim(
                $p->exam_type . ($p->period_name ? ' (' . $p->period_name . ')' : '')
            ),
        ];
    }

    private function buildProposalContextQuery()
    {
        return ExamProposal::query()
            ->join('mst_course', 'trx_exam_proposals.course_id', '=', 'mst_course.id')
            ->join('mst_unit as prodi', 'mst_course.unit_id', '=', 'prodi.id')
            ->leftJoin('mst_unit as fakultas', 'prodi.unit_parent', '=', 'fakultas.id')
            ->leftJoin('mst_period', 'trx_exam_proposals.period_id', '=', 'mst_period.id');
    }

    private function resolveProposalContext(int $proposalId): ?array
    {
        if ($proposalId <= 0) {
            return null;
        }

        $proposal = $this->buildProposalContextQuery()
            ->where('trx_exam_proposals.id', $proposalId)
            ->first($this->approvedProposalSelectColumns());

        return $proposal ? $this->formatProposalItem($proposal) : null;
    }

    private function getProposalFilterDefaults($proposals): array
    {
        if ($this->isAdmin()) {
            return [
                'fakultas_id' => '',
                'prodi_id'    => '',
                'course_id'   => '',
                'proposal_id' => '',
            ];
        }

        $latest = $proposals->first();
        if (! $latest) {
            return [
                'fakultas_id' => '',
                'prodi_id'    => '',
                'course_id'   => '',
                'proposal_id' => '',
            ];
        }

        return [
            'fakultas_id' => (string) ($latest['fakultas_id'] ?? ''),
            'prodi_id'    => (string) ($latest['prodi_id'] ?? ''),
            'course_id'   => (string) ($latest['course_id'] ?? ''),
            'proposal_id' => (string) ($latest['id'] ?? ''),
        ];
    }

    private function ensureCanManage(ExamRoom $room): void
    {
        if ($this->isAdmin()) return;
        abort_unless($room->created_by === Auth::id(), 403, 'Bukan ruang ujian Anda.');
    }

    private function calculateExpiresAt(ExamRoom $room, $startedAt)
    {
        $byDuration = $startedAt->copy()->addMinutes($room->duration_minutes);
        // Tidak boleh melebihi end_at room
        return $byDuration->gt($room->end_at) ? $room->end_at->copy() : $byDuration;
    }

    private function validateRoom(Request $request, bool $restricted = false, ?ExamRoom $room = null): array
    {
        $rules = [
            'proposal_id'         => 'required|integer|exists:trx_exam_proposals,id',
            'title'               => 'required|string|max:150',
            'description'         => 'nullable|string|max:1000',
            'start_at'            => 'required|date',
            'duration_minutes'    => 'required|integer|min:1|max:600',
            'tab_switch_policy'   => 'required|in:unlimited,strict,limited',
            'tab_switch_limit'    => 'nullable|integer|min:0|max:50',
            'shuffle_questions'   => 'nullable|boolean',
            'show_remaining_time' => 'nullable|boolean',
            'auto_grading_enabled' => 'nullable|boolean',
        ];

        $data = $request->validate($rules);

        if ($restricted) {
            unset($data['proposal_id']);
        }

        $data['shuffle_questions']   = $request->boolean('shuffle_questions');
        $data['show_remaining_time'] = $request->boolean('show_remaining_time', true);
        $data['auto_grading_enabled'] = $request->boolean('auto_grading_enabled', false);
        $data['tab_switch_limit']    = $data['tab_switch_policy'] === 'limited'
            ? ($data['tab_switch_limit'] ?? 0)
            : 0;

        $scheduledStart = Carbon::parse($data['start_at']);
        if ($room && $room->status !== 'DRAFT') {
            unset($data['start_at']);
        } else {
            $data['end_at'] = ExamRoom::computeEndAt($scheduledStart, (int) $data['duration_minutes']);
        }

        if (!empty($data['proposal_id'])) {
            $approved = ExamProposal::where('id', $data['proposal_id'])
                ->where('status', 'APPROVED')
                ->exists();
            abort_unless($approved, 422, 'Paket soal yang dipilih belum disetujui.');
        }

        return $data;
    }
}
