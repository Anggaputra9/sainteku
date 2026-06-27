<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Konversi skor jawaban lama (skala 0–100) ke skala bobot (0–bobot%).
     * Data baru yang sudah berbobot (score <= weight) tidak diubah.
     */
    public function up(): void
    {
        $rows = DB::table('trx_exam_attempt_answers as a')
            ->join('trx_exam_attempts as att', 'att.id', '=', 'a.attempt_id')
            ->join('trx_exam_rooms as r', 'r.id', '=', 'att.room_id')
            ->join('trx_exam_questions as eq', function ($join) {
                $join->on('eq.proposal_id', '=', 'r.proposal_id')
                    ->on('eq.question_id', '=', 'a.question_id');
            })
            ->whereNotNull('a.score')
            ->whereColumn('a.score', '>', 'eq.weight')
            ->select([
                'a.id',
                'a.score',
                'eq.weight',
            ])
            ->get();

        foreach ($rows as $row) {
            $weight = (float) $row->weight;
            if ($weight <= 0) {
                continue;
            }

            $converted = round(min((float) $row->score, 100) * $weight / 100, 2);

            DB::table('trx_exam_attempt_answers')
                ->where('id', $row->id)
                ->update(['score' => $converted]);
        }

        $attemptIds = DB::table('trx_exam_attempt_answers')
            ->whereNotNull('score')
            ->distinct()
            ->pluck('attempt_id');

        foreach ($attemptIds as $attemptId) {
            $totalScore = DB::table('trx_exam_attempt_answers')
                ->where('attempt_id', $attemptId)
                ->whereNotNull('score')
                ->sum('score');

            DB::table('trx_exam_attempts')
                ->where('id', $attemptId)
                ->update(['score' => round((float) $totalScore, 2)]);
        }
    }

    public function down(): void
    {
        // Tidak dapat dipulihkan secara akurat tanpa snapshot data lama.
    }
};