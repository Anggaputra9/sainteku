<?php

namespace App\Services;

class AiGradingResponseParser
{
    /**
     * @return array{score: float|null, feedback: string, parsed: bool}
     */
    public static function parse(string $response): array
    {
        $score = null;
        $feedback = '';

        if (preg_match('/(?:NILAI|SCORE|SKOR)\s*:?\s*(\d+(?:\.\d+)?)/i', $response, $matches)) {
            $score = max(0, min(100, (float) $matches[1]));
        }

        if (preg_match('/FEEDBACK\s*:\s*(.+)$/is', $response, $matches)) {
            $feedback = trim($matches[1]);
        } elseif ($score !== null) {
            $parts = preg_split('/(?:NILAI|SCORE|SKOR)\s*:?\s*\d+(?:\.\d+)?/i', $response, 2);
            if (isset($parts[1])) {
                $feedback = trim($parts[1]);
            }
        }

        if ($feedback === '') {
            $feedback = trim($response);
        }

        return [
            'score' => $score,
            'feedback' => $feedback,
            'parsed' => $score !== null,
        ];
    }
}