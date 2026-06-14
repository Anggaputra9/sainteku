<?php

namespace Modules\Ujian\Jobs;

use App\Services\AiGradingService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Ujian\Models\ExamAttempt;
use Modules\Ujian\Models\ExamAttemptAnswer;

class GradeAttemptJob
{
    use Queueable;

    public $attempt;

    /**
     * Create a new job instance.
     */
    public function __construct(ExamAttempt $attempt)
    {
        $this->attempt = $attempt;
    }

    /**
     * Execute the job.
     */
    public function handle(AiGradingService $gradingService): void
    {
        $attempt = $this->attempt->fresh();
        if (!$attempt) {
            return;
        }

        $cacheKey = "attempt_grading_{$attempt->id}";
        Cache::put($cacheKey, true, now()->addMinutes(30));

        try {
            $room = $attempt->room;
            $room->load('proposal.examQuestions.question');

            DB::beginTransaction();

            foreach ($room->proposal->examQuestions as $examQuestion) {
                $answer = ExamAttemptAnswer::where('attempt_id', $attempt->id)
                    ->where('question_id', $examQuestion->question_id)
                    ->first();

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
                        'graded_by' => null,
                        'graded_at' => now(),
                    ]);
                    continue;
                }

                $result = $gradingService->gradeQuestionText(
                    $examQuestion->question->question_text,
                    $answer->answer_text,
                    $examQuestion->question->key_answer ?? null,
                );

                if (!$result['success']) {
                    throw new \RuntimeException($result['error'] ?? 'AI grading gagal.');
                }

                $answer->update([
                    'score' => $result['score'],
                    'grading_method' => 'ai',
                    'ai_feedback' => $result['feedback'],
                    'graded_by' => null,
                    'graded_at' => now(),
                ]);
            }

            $finalScore = $attempt->recalculateScore();
            $attempt->update([
                'grader_note' => 'Dikoreksi otomatis dengan AI pada ' . now()->translatedFormat('d M Y H:i'),
            ]);

            DB::commit();

            Log::info("Auto-grading completed for attempt {$attempt->uuid}", [
                'student' => $attempt->user?->name,
                'score' => $finalScore,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Auto-grading failed for attempt {$attempt->uuid}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        } finally {
            Cache::forget($cacheKey);
        }
    }

}
