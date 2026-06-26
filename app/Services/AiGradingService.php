<?php

namespace App\Services;

use App\Models\AiSetting;
use Modules\MonevAkademik\app\Models\Question;
use Modules\Ujian\Models\ExamAttemptAnswer;

class AiGradingService
{
    public function __construct(
        protected AiService $aiService,
    ) {}

    /**
     * Koreksi satu jawaban — seluruh parameter model (max_tokens, temperature, dll.)
     * diambil dari pengaturan AI aktif, tanpa override manual di kode.
     *
     * @return array{success: bool, score: float, feedback: string, error: string|null}
     */
    public function gradeAnswer(ExamAttemptAnswer $answer, ?AiSetting $setting = null): array
    {
        $answer->load('question');
        $question = $answer->question;

        if (!$question) {
            return $this->fail('Soal tidak ditemukan.');
        }

        return $this->gradeQuestionText(
            $question->question_text,
            $answer->answer_text,
            $question->key_answer ?? null,
            $setting,
        );
    }

    /**
     * @return array{success: bool, score: float, feedback: string, error: string|null}
     */
    public function gradeQuestionText(
        string $questionText,
        ?string $answerText,
        ?string $keyAnswer = null,
        ?AiSetting $setting = null,
    ): array {
        $setting = $this->resolveSetting($setting);
        if (!$setting) {
            return $this->fail('Konfigurasi AI aktif tidak ditemukan.');
        }

        $prompt = $this->buildGradingPrompt($questionText, $answerText, $keyAnswer);

        // Tanpa array $options — max_tokens, temperature, top_p, dll. dari AiSetting.
        $result = $this->aiService->sendPrompt($prompt, $setting);

        if (!$result['success']) {
            return $this->fail($result['error'] ?? 'Permintaan AI gagal.');
        }

        $parsed = AiGradingResponseParser::parse($result['response']);
        if (!$parsed['parsed']) {
            return $this->fail('Gagal membaca nilai dari respons AI.');
        }

        return [
            'success' => true,
            'score' => $this->applyLenientScore((float) $parsed['score'], $answerText),
            'feedback' => $parsed['feedback'],
            'error' => null,
        ];
    }

    public function buildGradingPrompt(string $questionText, ?string $answerText, ?string $keyAnswer = null): string
    {
        $question = $this->normalizeForGrading(strip_tags($questionText));
        $answer = $this->normalizeForGrading(trim((string) $answerText));
        $answer = $answer !== '' ? $answer : '(Tidak dijawab)';

        $prompt = "Anda adalah dosen pengoreksi ujian essay yang adil, konstruktif, dan cukup toleran.\n";
        $prompt .= "Utamakan penilaian berimbang: beri apresiasi pada pemahaman yang sudah benar, jangan terlalu keras pada kekurangan kecil.\n\n";
        $prompt .= "SOAL:\n{$question}\n\n";

        if (!empty($keyAnswer)) {
            $prompt .= "KUNCI JAWABAN (referensi, bukan harus sama persis):\n" . $this->normalizeForGrading(strip_tags($keyAnswer)) . "\n\n";
        }

        $prompt .= "JAWABAN MAHASISWA:\n{$answer}\n\n";
        $prompt .= "INSTRUKSI PENILAIAN:\n";
        $prompt .= "- Skala 0-100. Fokus pada pemahaman konsep, relevansi, dan kebenaran substansi.\n";
        $prompt .= "- Beri nilai parsial (partial credit) bila jawaban benar sebagian.\n";
        $prompt .= "- Sinonim, parafrase, atau urutan berbeda tetap boleh dinilai baik bila maknanya sesuai.\n";
        $prompt .= "- Jangan menurunkan nilai banyak hanya karena ejaan, tata bahasa, atau format penulisan.\n";
        $prompt .= "- Gunakan benefit of the doubt bila jawaban ambigu tetapi masih relevan dengan soal.\n";
        $prompt .= "- Rubrik toleran:\n";
        $prompt .= "  * 85-100: konsep utama benar dan cukup lengkap.\n";
        $prompt .= "  * 70-84: konsep utama benar, ada detail yang kurang.\n";
        $prompt .= "  * 55-69: sebagian besar benar atau pemahaman cukup meski belum lengkap.\n";
        $prompt .= "  * 40-54: ada pemahaman terbatas, beberapa poin relevan.\n";
        $prompt .= "  * 20-39: minim relevan, tetapi masih ada usaha jawaban.\n";
        $prompt .= "  * 0: tidak dijawab, kosong, atau sama sekali tidak relevan.\n";
        $prompt .= "- Hindari memberi nilai di bawah 40 jika jawaban jelas relevan dan menunjukkan pemahaman dasar.\n";
        $prompt .= "- FEEDBACK wajib Bahasa Indonesia formal yang jelas dan mudah dibaca.\n";
        $prompt .= "- FEEDBACK 2-3 kalimat utuh. Tanpa LaTeX, tanpa simbol rumus, tanpa kata acak.\n";
        $prompt .= "- Jangan ulang atau mengutip jawaban mahasiswa di FEEDBACK.\n";
        $prompt .= "- Hanya keluarkan tepat 2 baris berikut, tanpa teks lain:\n\n";
        $prompt .= "NILAI: [angka 0-100]\n";
        $prompt .= "FEEDBACK: [kalimat penilaian]";

        return $prompt;
    }

    /**
     * Grade multiple answers in batch.
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
     * Grade all answers for an exam attempt.
     */
    public function gradeExamAttempt(int $attemptId, ?AiSetting $setting = null): array
    {
        $answers = ExamAttemptAnswer::where('attempt_id', $attemptId)
            ->where('is_answered', true)
            ->whereNull('score')
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

        if ($gradedCount > 0) {
            $averageScore = $totalScore / $gradedCount;
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

    public function regradeAnswer(ExamAttemptAnswer $answer, ?AiSetting $setting = null): array
    {
        return $this->gradeAnswer($answer, $setting);
    }

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

    private function resolveSetting(?AiSetting $setting): ?AiSetting
    {
        if ($setting && $setting->is_active) {
            return $setting;
        }

        return AiSetting::getActiveDefault();
    }

    /**
     * Koreksi ringan agar jawaban substantif yang relevan tidak mendapat skor terlalu rendah.
     */
    private function applyLenientScore(float $score, ?string $answerText): float
    {
        $answer = trim((string) $answerText);
        if ($answer === '' || $answer === '(Tidak dijawab)') {
            return 0;
        }

        $wordCount = count(preg_split('/\s+/u', $answer, -1, PREG_SPLIT_NO_EMPTY) ?: []);

        if ($wordCount >= 15 && $score > 0 && $score < 40) {
            $score = 40;
        } elseif ($wordCount >= 8 && $score > 0 && $score < 30) {
            $score = 30;
        }

        return min(100, round($score, 2));
    }

    private function normalizeForGrading(string $text): string
    {
        $text = preg_replace('/\$\$([^$]+)\$\$/', '$1', $text) ?? $text;
        $text = preg_replace('/\$([^$]+)\$/', '$1', $text) ?? $text;
        $text = preg_replace('/\s+/', ' ', $text) ?? $text;

        return trim($text);
    }

    /**
     * @return array{success: bool, score: float, feedback: string, error: string|null}
     */
    private function fail(string $error): array
    {
        return [
            'success' => false,
            'score' => 0,
            'feedback' => '',
            'error' => $error,
        ];
    }
}