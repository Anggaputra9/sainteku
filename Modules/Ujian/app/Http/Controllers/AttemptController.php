<?php

namespace Modules\Ujian\Http\Controllers;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Ujian\Jobs\GradeAttemptJob;
use Modules\Ujian\Models\ExamAttempt;
use Modules\Ujian\Models\ExamAttemptAnswer;
use Modules\Ujian\Models\ExamAttemptEvent;
use Modules\Ujian\Models\ExamRoom;

class AttemptController extends Controller
{
    /* =========================================================
     | Halaman join — input kode ruang ujian
     |==========================================================*/
    public function join()
    {
        return view('ujian::attempt.join')->with('title', 'Masuk Ruang Ujian');
    }

    /**
     * Endpoint untuk hasil scan QR — mahasiswa membuka link
     * /ujian/attempt/scan?code=ABCDEF dari hasil scanner kamera HP/laptop.
     * Kalau kode valid → langsung lanjut ke work; kalau tidak →
     * redirect kembali ke halaman join dengan pesan error.
     */
    public function scan(Request $request)
    {
        $code = strtoupper(trim((string) $request->query('code', '')));
        if ($code === '') {
            return redirect()->route('ujian.attempt.join');
        }

        $request->merge(['room_code' => $code]);
        return $this->joinSubmit($request);
    }

    /**
     * Mahasiswa submit kode ruang → cari, validasi, lalu redirect ke halaman work.
     */
    public function joinSubmit(Request $request)
    {
        $request->validate([
            'room_code' => 'required|string|max:20',
        ]);

        $code = strtoupper(trim($request->input('room_code')));
        $room = ExamRoom::where('room_code', $code)->first();

        if (!$room) {
            return back()->withErrors(['room_code' => 'Kode ruang ujian tidak ditemukan.'])->withInput();
        }

        $room->autoStartIfScheduled();
        if ($room->autoCloseIfExpired()) {
            $room->refresh();
        }

        if ($room->status === 'CLOSED') {
            return back()->withErrors(['room_code' => 'Ruang ujian sudah ditutup.'])->withInput();
        }
        if ($room->status === 'DRAFT') {
            $joinOpens = $room->start_at?->copy()->subMinutes(ExamRoom::JOIN_GRACE_MINUTES);

            return back()->withErrors([
                'room_code' => $joinOpens
                    ? 'Ruang ujian belum dibuka. Mahasiswa dapat masuk mulai '
                        . $joinOpens->translatedFormat('d M Y H:i') . ' WIB.'
                    : 'Ruang ujian belum dimulai.',
            ])->withInput();
        }
        if ($room->status !== 'PUBLISHED' || ! $room->is_active) {
            return back()->withErrors(['room_code' => 'Ruang ujian tidak aktif.'])->withInput();
        }

        $now = now();
        $joinOpens = $room->joinOpensAt();

        if (! $joinOpens || $now->lt($joinOpens)) {
            return back()->withErrors([
                'room_code' => 'Ruang ujian belum dibuka. Mahasiswa dapat masuk mulai '
                    . ($joinOpens?->translatedFormat('d M Y H:i') ?? '-') . ' WIB.',
            ])->withInput();
        }

        $hasAttempt = ExamAttempt::where('room_id', $room->id)
            ->where('user_id', Auth::id())
            ->exists();

        $joinDeadline = $room->joinDeadline();
        if (! $hasAttempt && $joinDeadline && $now->gt($joinDeadline)) {
            return back()->withErrors([
                'room_code' => 'Batas waktu masuk ruang ujian sudah berakhir pada '
                    . $joinDeadline->translatedFormat('d M Y H:i') . ' WIB.',
            ])->withInput();
        }

        if ($now->gt($room->end_at)) {
            return back()->withErrors([
                'room_code' => 'Ruang ujian sudah berakhir pada ' . $room->end_at->translatedFormat('d M Y H:i') . ' WIB.',
            ])->withInput();
        }

        return redirect()->route('ujian.attempt.work', ['code' => $room->room_code]);
    }

    /* =========================================================
     | Halaman pengerjaan ujian
     |==========================================================*/
    public function work(Request $request, string $code)
    {
        $room = ExamRoom::where('room_code', strtoupper($code))->firstOrFail();

        $room->autoStartIfScheduled();
        if ($room->autoCloseIfExpired()) {
            $room->refresh();
        }

        // Validasi window
        $this->ensureRoomOpen($room);

        $attempt = $this->getOrCreateAttempt($room, $request);

        // Auto-submit kalau waktunya sudah lewat
        if ($attempt->isOngoing() && $attempt->isExpired()) {
            $this->finalize($attempt, 'AUTO_SUBMITTED_TIME');
            return redirect()->route('ujian.attempt.finished', ['code' => $room->room_code]);
        }

        // Sudah selesai → redirect ke halaman selesai
        if ($attempt->isFinished()) {
            return redirect()->route('ujian.attempt.finished', ['code' => $room->room_code]);
        }

        // Load soal dari proposal, urut by order_no
        $room->load([
            'proposal.examQuestions.question',
        ]);

        $questions = $this->resolveQuestionsForAttempt($room, $attempt);

        // Pre-load jawaban yang sudah ada
        $existingAnswers = ExamAttemptAnswer::where('attempt_id', $attempt->id)
            ->get()
            ->keyBy('question_id');

        return view('ujian::attempt.work', [
            'title'           => 'Mengerjakan: ' . $room->title,
            'room'            => $room,
            'attempt'         => $attempt,
            'questions'       => $questions,
            'existingAnswers' => $existingAnswers,
        ]);
    }

    /* =========================================================
     | API: simpan jawaban (auto-save)
     |==========================================================*/
    public function saveAnswer(Request $request, string $code)
    {
        $request->validate([
            'question_id' => 'required|integer',
            'answer_text' => 'nullable|string',
        ]);

        $room = ExamRoom::where('room_code', strtoupper($code))->firstOrFail();
        $attempt = $this->getMyAttempt($room);

        if (!$attempt || !$attempt->isOngoing()) {
            return response()->json(['ok' => false, 'message' => 'Sesi ujian tidak aktif.'], 422);
        }
        if ($attempt->isExpired()) {
            $this->finalize($attempt, 'AUTO_SUBMITTED_TIME');
            return response()->json(['ok' => false, 'redirect' => route('ujian.attempt.finished', ['code' => $code])], 200);
        }

        // Pastikan question_id memang bagian dari proposal ini
        $valid = DB::table('trx_exam_questions')
            ->where('proposal_id', $room->proposal_id)
            ->where('question_id', $request->question_id)
            ->exists();
        if (!$valid) {
            return response()->json(['ok' => false, 'message' => 'Soal tidak valid.'], 422);
        }

        $text = (string) $request->input('answer_text', '');
        ExamAttemptAnswer::updateOrCreate(
            [
                'attempt_id'  => $attempt->id,
                'question_id' => $request->question_id,
            ],
            [
                'answer_text' => $text,
                'is_answered' => trim($text) !== '',
            ]
        );

        $attempt->update(['last_activity_at' => now()]);

        return response()->json(['ok' => true, 'saved_at' => now()->toDateTimeString()]);
    }

    /* =========================================================
     | API: log event tab-switch / focus
     |==========================================================*/
    public function recordEvent(Request $request, string $code)
    {
        $request->validate([
            'event_type' => 'required|string|max:30',
            'payload'    => 'nullable|array',
        ]);

        $room = ExamRoom::where('room_code', strtoupper($code))->firstOrFail();
        $attempt = $this->getMyAttempt($room);

        if (!$attempt) {
            return response()->json(['ok' => false], 404);
        }

        $eventType = $request->input('event_type');
        ExamAttemptEvent::create([
            'attempt_id'  => $attempt->id,
            'event_type'  => $eventType,
            'payload'     => $request->input('payload'),
            'occurred_at' => now(),
        ]);

        // Hanya event "focus_lost" yang dihitung sebagai pelanggaran tab-switch
        $shouldCount = in_array($eventType, ['focus_lost', 'tab_hidden', 'fullscreen_exit'], true);

        if ($shouldCount && $attempt->isOngoing()) {
            $attempt->increment('tab_switch_count');
            $attempt->refresh();

            $policy = $room->tab_switch_policy;
            $shouldAutoSubmit = match ($policy) {
                'unlimited' => false,
                'strict'    => true,                                  // 1x langsung kena
                'limited'   => $attempt->tab_switch_count > $room->tab_switch_limit,
                default     => false,
            };

            if ($shouldAutoSubmit) {
                $this->finalize($attempt, 'AUTO_SUBMITTED_VIOLATION');
                return response()->json([
                    'ok'              => true,
                    'auto_submitted'  => true,
                    'tab_switch_count'=> $attempt->tab_switch_count,
                    'redirect'        => route('ujian.attempt.finished', ['code' => $code]),
                ]);
            }
        }

        return response()->json([
            'ok'                => true,
            'tab_switch_count'  => (int) $attempt->tab_switch_count,
        ]);
    }

    /* =========================================================
     | Submit manual
     |==========================================================*/
    public function submit(Request $request, string $code)
    {
        $room = ExamRoom::where('room_code', strtoupper($code))->firstOrFail();
        $attempt = $this->getMyAttempt($room);

        if (!$attempt) {
            return redirect()->route('ujian.attempt.join');
        }

        if ($attempt->isOngoing()) {
            $this->finalize($attempt, 'SUBMITTED');
        }

        return redirect()->route('ujian.attempt.finished', ['code' => $room->room_code])
            ->with('success', 'Jawaban berhasil disubmit.');
    }

    /* =========================================================
     | Halaman hasil ujian per attempt — dipanggil dengan UUID.
     | Dosen / admin bisa lihat hasil milik siapa pun di room-nya;
     | mahasiswa hanya boleh lihat hasil miliknya sendiri.
     |==========================================================*/
    public function result(ExamAttempt $attempt)
    {
        $attempt->load([
            'room.proposal.examQuestions.question',
            'room.proposal.course.unit',
            'room.proposal.period',
            'user:id,name,identity_id',
            'answers',
        ]);

        $user = Auth::user();
        $isOwner    = $attempt->user_id === Auth::id();
        $isLecturer = $user && $user->roles()
            ->whereIn('role_code', ['ADM', 'DSN', 'KPD'])
            ->exists();
        $isRoomCreator = $attempt->room && $attempt->room->created_by === Auth::id();

        abort_unless($isOwner || $isLecturer || $isRoomCreator, 403, 'Anda tidak berhak melihat hasil ini.');

        $totalQuestions = $attempt->room->proposal->examQuestions->count();

        return view('ujian::attempt.result', [
            'title'          => 'Hasil Ujian',
            'attempt'        => $attempt,
            'totalQuestions' => $totalQuestions,
            'isLecturer'     => $isLecturer || $isRoomCreator,
        ]);
    }

    /* =========================================================
     | Halaman selesai
     |==========================================================*/
    public function finished(string $code)
    {
        $room = ExamRoom::where('room_code', strtoupper($code))->firstOrFail();
        $attempt = $this->getMyAttempt($room);

        abort_unless($attempt, 404);

        $attempt->loadCount(['answers as answered_count' => fn ($q) => $q->where('is_answered', true)]);
        $totalQuestions = $room->proposal()->withCount('examQuestions')->first()->exam_questions_count ?? 0;

        return view('ujian::attempt.finished', compact('room', 'attempt', 'totalQuestions'))
            ->with('title', 'Ujian Selesai');
    }

    /* =========================================================
     | Helpers
     |==========================================================*/
    private function ensureRoomOpen(ExamRoom $room): void
    {
        abort_if($room->status !== 'PUBLISHED' || ! $room->is_active, 403, 'Ruang ujian tidak aktif.');

        $now = now();
        $joinOpens = $room->joinOpensAt();
        abort_if(! $joinOpens || $now->lt($joinOpens), 403, 'Ruang ujian belum dibuka.');
        abort_if($now->gt($room->end_at), 403, 'Ruang ujian sudah berakhir.');
    }

    /**
     * Ambil atau buat attempt untuk user saat ini di room ini.
     * Sekaligus set started_at + expires_at saat pertama kali masuk.
     */
    private function getOrCreateAttempt(ExamRoom $room, Request $request): ExamAttempt
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($room, $userId, $request) {
            $attempt = ExamAttempt::where('room_id', $room->id)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            $now = now();
            $examStart = $room->started_at ?? $room->start_at;
            $attemptStart = ($examStart && $now->lt($examStart)) ? $examStart->copy() : $now->copy();

            if (!$attempt) {
                $attempt = ExamAttempt::create([
                    'room_id'          => $room->id,
                    'user_id'          => $userId,
                    'status'           => 'ONGOING',
                    'started_at'       => $attemptStart,
                    'expires_at'       => $this->calculateExpiresAt($room, $attemptStart),
                    'last_activity_at' => $now,
                    'ip_address'       => $request->ip(),
                    'user_agent'       => substr((string) $request->userAgent(), 0, 250),
                ]);

                ExamAttemptEvent::create([
                    'attempt_id'  => $attempt->id,
                    'event_type'  => 'start',
                    'occurred_at' => $now,
                ]);
            } elseif ($attempt->status === 'NOT_STARTED') {
                $attempt->update([
                    'status'           => 'ONGOING',
                    'started_at'       => $now,
                    'expires_at'       => $this->calculateExpiresAt($room, $now),
                    'last_activity_at' => $now,
                ]);
            }

            return $attempt;
        });
    }

    private function getMyAttempt(ExamRoom $room): ?ExamAttempt
    {
        return ExamAttempt::where('room_id', $room->id)
            ->where('user_id', Auth::id())
            ->first();
    }

    private function calculateExpiresAt(ExamRoom $room, Carbon $startedAt): Carbon
    {
        $byDuration = $startedAt->copy()->addMinutes($room->duration_minutes);
        // Tidak boleh melebihi end_at room
        return $byDuration->gt($room->end_at) ? $room->end_at->copy() : $byDuration;
    }

    /**
     * Tentukan koleksi soal untuk attempt ini dengan urutan yang STABIL.
     *
     * Aturan:
     * - Jika attempt belum punya `question_order`, kunci urutan sekarang
     *   (acak bila room.shuffle_questions = true, kalau tidak ikut order_no).
     * - Jika sudah punya, ikuti urutan yang tersimpan supaya susunan
     *   soal tidak berubah saat mahasiswa me-refresh halaman.
     * - Jika ada perubahan komposisi soal (mis. dosen mengedit proposal
     *   setelah attempt dibuat), soal baru yang belum tercatat akan
     *   ditempelkan di belakang dan urutan baru disimpan ulang.
     */
    private function resolveQuestionsForAttempt(ExamRoom $room, ExamAttempt $attempt)
    {
        // Default ordering: by order_no (sesuai susunan dosen di proposal)
        $base = $room->proposal->examQuestions
            ->sortBy('order_no')
            ->values();

        // Map question_id -> examQuestion (pivot dengan relasi `question`)
        $byQuestionId = $base->keyBy(fn ($eq) => (int) $eq->question_id);

        $savedOrder = is_array($attempt->question_order) ? $attempt->question_order : null;

        if ($savedOrder) {
            // Pakai urutan yang sudah dikunci
            $ordered = collect($savedOrder)
                ->map(fn ($qid) => $byQuestionId->get((int) $qid))
                ->filter()
                ->values();

            // Jika ada soal baru di proposal yang belum ada di savedOrder,
            // tempelkan di belakang dan persist ulang urutannya.
            $missing = $base->reject(
                fn ($eq) => in_array((int) $eq->question_id, array_map('intval', $savedOrder), true)
            )->values();

            if ($missing->isNotEmpty()) {
                $ordered = $ordered->concat($missing)->values();
                $attempt->update([
                    'question_order' => $ordered->pluck('question_id')
                        ->map(fn ($v) => (int) $v)
                        ->values()
                        ->all(),
                ]);
            }

            return $ordered;
        }

        // Belum pernah dikunci → tentukan sekarang (acak bila perlu) lalu simpan.
        $questions = $room->shuffle_questions
            ? $base->shuffle()->values()
            : $base;

        $attempt->update([
            'question_order' => $questions->pluck('question_id')
                ->map(fn ($v) => (int) $v)
                ->values()
                ->all(),
        ]);

        return $questions;
    }

    /**
     * Finalize attempt: set status, submitted_at, log event.
     * Dispatch auto-grading job if enabled.
     */
    private function finalize(ExamAttempt $attempt, string $finalStatus): void
    {
        DB::transaction(function () use ($attempt, $finalStatus) {
            $attempt->update([
                'status'       => $finalStatus,
                'submitted_at' => now(),
            ]);

            ExamAttemptEvent::create([
                'attempt_id'  => $attempt->id,
                'event_type'  => $finalStatus === 'SUBMITTED' ? 'manual_submit' : 'auto_submit',
                'payload'     => ['final_status' => $finalStatus],
                'occurred_at' => now(),
            ]);

            // Koreksi AI otomatis di background (setelah respons submit dikirim ke mahasiswa)
            $room = $attempt->room;
            if ($room && $room->auto_grading_enabled) {
                $attemptId = $attempt->id;
                dispatch(function () use ($attemptId) {
                    $freshAttempt = ExamAttempt::find($attemptId);
                    if (!$freshAttempt) {
                        return;
                    }

                    (new GradeAttemptJob($freshAttempt))->handle(app(\App\Services\AiGradingService::class));
                })->afterResponse();
            }
        });
    }

    /* =========================================================
     | AI Grading Methods
     |==========================================================*/

    /**
     * Koreksi satu jawaban dengan AI.
     */
    public function gradeWithAi(Request $request, ExamAttemptAnswer $answer)
    {
        $this->authorizeGrading($answer->attempt);

        $answer->load('question');

        if (!$answer->question) {
            return response()->json(['message' => 'Soal tidak ditemukan.'], 404);
        }

        $grading = app(\App\Services\AiGradingService::class);

        try {
            $result = $grading->gradeAnswer($answer);

            if (!$result['success']) {
                return response()->json(['message' => 'AI grading gagal: ' . $result['error']], 500);
            }

            $answer->update([
                'score' => $result['score'],
                'grader_note' => null,
                'grading_method' => 'ai',
                'ai_feedback' => $result['feedback'],
                'graded_by' => Auth::id(),
                'graded_at' => now(),
            ]);

            $attempt = $answer->attempt;
            $totalScore = $attempt->recalculateScore();

            return response()->json([
                'message' => 'Jawaban berhasil dikoreksi dengan AI. Score: ' . $result['score'],
                'score' => $result['score'],
                'feedback' => $result['feedback'],
                'total_score' => $totalScore,
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'AI grading error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Koreksi semua jawaban dengan AI.
     */
    public function gradeAllWithAi(ExamAttempt $attempt)
    {
        $this->authorizeGrading($attempt);

        $attempt->load(['room.proposal.examQuestions', 'answers.question']);

        $gradedCount = 0;
        $errors = [];
        $grading = app(\App\Services\AiGradingService::class);

        DB::transaction(function () use ($attempt, $grading, &$gradedCount, &$errors) {
            $questions = $attempt->room->proposal->examQuestions;
            $existingAnswers = $attempt->answers->keyBy('question_id');

            foreach ($questions as $examQuestion) {
                $questionId = $examQuestion->question_id;

                if ($existingAnswers->has($questionId)) {
                    $answer = $existingAnswers->get($questionId);
                } else {
                    $answer = ExamAttemptAnswer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $questionId,
                        'answer_text' => null,
                        'is_answered' => false,
                    ]);
                    $answer->load('question');
                }

                if (!$answer->is_answered) {
                    $answer->update([
                        'score' => 0,
                        'grading_method' => 'manual',
                        'grader_note' => 'Tidak dijawab - nilai otomatis 0',
                        'graded_by' => Auth::id(),
                        'graded_at' => now(),
                    ]);
                    $gradedCount++;
                    continue;
                }

                if (!$answer->question) {
                    $errors[] = "Soal #{$questionId}: Question not found";
                    continue;
                }

                try {
                    $result = $grading->gradeAnswer($answer);

                    if ($result['success']) {
                        $answer->update([
                            'score' => $result['score'],
                            'grader_note' => null,
                            'grading_method' => 'ai',
                            'ai_feedback' => $result['feedback'],
                            'graded_by' => Auth::id(),
                            'graded_at' => now(),
                        ]);

                        $gradedCount++;
                    } else {
                        $errors[] = "Soal #{$questionId}: " . $result['error'];
                    }
                } catch (\Exception $e) {
                    $errors[] = "Soal #{$questionId}: " . $e->getMessage();
                }
            }

            $attempt->recalculateScore();
        });

        $message = "Berhasil mengoreksi {$gradedCount} jawaban.";
        if (count($errors) > 0) {
            $message .= " Gagal: " . count($errors) . " jawaban.";
        }

        return response()->json([
            'message' => $message,
            'graded_count' => $gradedCount,
            'errors' => $errors,
        ]);
    }

    /**
     * Authorize grading access.
     */
    private function authorizeGrading(ExamAttempt $attempt): void
    {
        $user = Auth::user();
        $isAdmin = $user->roles()->where('role_code', 'ADM')->exists();
        $isRoomCreator = $attempt->room && $attempt->room->created_by === $user->id;

        abort_unless($isAdmin || $isRoomCreator, 403, 'Anda tidak berhak mengoreksi ujian ini.');
    }
}
