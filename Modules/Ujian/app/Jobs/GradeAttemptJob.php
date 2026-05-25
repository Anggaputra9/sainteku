<?php

namespace Modules\Ujian\Jobs;

use App\Services\AiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Ujian\Models\ExamAttempt;
use Modules\Ujian\Models\ExamAttemptAnswer;

class GradeAttemptJob implements ShouldQueue
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
    public function handle(AiService $aiService): void
    {
        try {
            $attempt = $this->attempt;
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

                // Koreksi dengan AI
                $prompt = $this->buildGradingPrompt($examQuestion->question, $answer->answer_text);
                $aiResponse = $aiService->sendMessage($prompt);
                [$score, $feedback] = $this->parseAiResponse($aiResponse);

                $answer->update([
                    'score' => $score,
                    'grading_method' => 'ai',
                    'ai_feedback' => $feedback,
                    'graded_by' => null,
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
        }
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
}
