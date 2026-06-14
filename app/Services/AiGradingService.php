<?php

namespace App\Services;

use App\Models\AiSetting;
use Modules\MonevAkademik\app\Models\Question;
use Modules\Ujian\Models\ExamAttemptAnswer;
use Illuminate\Support\Facades\Log;

class AiGradingService
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Grade a single answer using AI.
     *
     * @param ExamAttemptAnswer $answer
     * @param AiSetting|null $setting
     * @return array ['success' => bool, 'score' => float, 'feedback' => string, 'error' => string|null]
     */
    public function gradeAnswer(ExamAttemptAnswer $answer, ?AiSetting $setting = null): array
    {
        // Load question with relations
        $answer->load('question');
        $question = $answer->question;

        if (!$question) {
            return [
                'success' => false,
                'score' => 0,
                'feedback' => '',
                'error' => 'Question not found.',
            ];
        }

        // Build grading prompt
        $prompt = $this->buildGradingPrompt($question, $answer->answer_text);

        // Send to AI
        $result = $this->aiService->sendPrompt($prompt, $setting);

        if (!$result['success']) {
            return [
                'success' => false,
                'score' => 0,
                'feedback' => '',
                'error' => $result['error'],
            ];
        }

        // Parse AI response
        $parsed = $this->parseGradingResponse($result['response'], $question);

        return [
            'success' => true,
            'score' => $parsed['score'],
            'feedback' => $parsed['feedback'],
            'error' => null,
        ];
    }

    /**
     * Grade multiple answers in batch.
     *
     * @param array $answers Array of ExamAttemptAnswer
     * @param AiSetting|null $setting
     * @return array
     */
    public function gradeAnswersBatch(array $answers, ?AiSetting $setting = null): array
    {
        $results = [];

        foreach ($answers as $answer) {
            $results[$answer->id] = $this->gradeAnswer($answer, $setting);
        }

        return $results;
    }

    /**
     * Build grading prompt for AI.
     */
    private function buildGradingPrompt(Question $question, string $studentAnswer): string
    {
        $prompt = "Anda adalah seorang dosen yang sedang mengoreksi jawaban ujian mahasiswa.\n\n";
        $prompt .= "**SOAL:**\n";
        $prompt .= strip_tags($question->question_text) . "\n\n";

        // Add key answer if available
        if (!empty($question->key_answer)) {
            $prompt .= "**KUNCI JAWABAN:**\n";
            $prompt .= strip_tags($question->key_answer) . "\n\n";
        }

        $prompt .= "**JAWABAN MAHASISWA:**\n";
        $prompt .= $studentAnswer . "\n\n";

        $prompt .= "**INSTRUKSI PENILAIAN:**\n";
        $prompt .= "1. Baca dan pahami soal, kunci jawaban, dan jawaban mahasiswa.\n";
        $prompt .= "2. Evaluasi jawaban mahasiswa berdasarkan:\n";
        $prompt .= "   - Ketepatan konsep dan pemahaman materi\n";
        $prompt .= "   - Kelengkapan jawaban\n";
        $prompt .= "   - Struktur dan kejelasan penjelasan\n";
        $prompt .= "   - Relevansi dengan pertanyaan\n";
        $prompt .= "3. Berikan nilai dalam skala 0-100.\n";
        $prompt .= "4. Berikan feedback konstruktif untuk mahasiswa.\n\n";

        $prompt .= "**FORMAT RESPONS:**\n";
        $prompt .= "NILAI: [angka 0-100]\n";
        $prompt .= "FEEDBACK: [feedback untuk mahasiswa]\n\n";

        $prompt .= "Berikan penilaian yang objektif dan adil.";

        return $prompt;
    }

    /**
     * Parse AI grading response.
     */
    private function parseGradingResponse(string $response, Question $question): array
    {
        $score = 0;
        $feedback = '';

        // Try to extract score
        if (preg_match('/NILAI\s*:\s*(\d+(?:\.\d+)?)/i', $response, $matches)) {
            $score = (float) $matches[1];
            // Ensure score is within 0-100
            $score = max(0, min(100, $score));
        }

        // Try to extract feedback
        if (preg_match('/FEEDBACK\s*:\s*(.+?)(?=\n\n|$)/is', $response, $matches)) {
            $feedback = trim($matches[1]);
        }

        // If parsing failed, use the whole response as feedback
        if (empty($feedback)) {
            $feedback = $response;
        }

        return [
            'score' => $score,
            'feedback' => $feedback,
        ];
    }

    /**
     * Grade all answers for an exam attempt.
     *
     * @param int $attemptId
     * @param AiSetting|null $setting
     * @return array ['success' => bool, 'graded_count' => int, 'total_score' => float, 'errors' => array]
     */
    public function gradeExamAttempt(int $attemptId, ?AiSetting $setting = null): array
    {
        $answers = ExamAttemptAnswer::where('attempt_id', $attemptId)
            ->where('is_answered', true)
            ->whereNull('score') // Only grade ungraded answers
            ->get();

        if ($answers->isEmpty()) {
            return [
                'success' => true,
                'graded_count' => 0,
                'total_score' => 0,
                'errors' => [],
            ];
        }

        $gradedCount = 0;
        $totalScore = 0;
        $errors = [];

        foreach ($answers as $answer) {
            $result = $this->gradeAnswer($answer, $setting);

            if ($result['success']) {
                // Update answer with score and feedback
                $answer->update([
                    'score' => $result['score'],
                    'grader_note' => $result['feedback'],
                ]);

                $gradedCount++;
                $totalScore += $result['score'];
            } else {
                $errors[] = [
                    'answer_id' => $answer->id,
                    'question_id' => $answer->question_id,
                    'error' => $result['error'],
                ];
            }
        }

        // Calculate average score for the attempt
        if ($gradedCount > 0) {
            $averageScore = $totalScore / $gradedCount;

            // Update attempt score
            $attempt = \Modules\Ujian\Models\ExamAttempt::find($attemptId);
            if ($attempt) {
                $attempt->update(['score' => $averageScore]);
            }
        }

        return [
            'success' => empty($errors),
            'graded_count' => $gradedCount,
            'total_score' => $totalScore,
            'average_score' => $gradedCount > 0 ? $totalScore / $gradedCount : 0,
            'errors' => $errors,
        ];
    }

    /**
     * Re-grade a single answer (for manual correction).
     */
    public function regradeAnswer(ExamAttemptAnswer $answer, ?AiSetting $setting = null): array
    {
        return $this->gradeAnswer($answer, $setting);
    }

    /**
     * Get grading statistics for an attempt.
     */
    public function getGradingStats(int $attemptId): array
    {
        $answers = ExamAttemptAnswer::where('attempt_id', $attemptId)->get();

        $total = $answers->count();
        $graded = $answers->whereNotNull('score')->count();
        $ungraded = $total - $graded;
        $avgScore = $answers->whereNotNull('score')->avg('score') ?? 0;

        return [
            'total_questions' => $total,
            'graded' => $graded,
            'ungraded' => $ungraded,
            'average_score' => round($avgScore, 2),
            'completion_percentage' => $total > 0 ? round(($graded / $total) * 100, 2) : 0,
        ];
    }
}
