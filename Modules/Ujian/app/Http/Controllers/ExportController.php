<?php

namespace Modules\Ujian\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Modules\Ujian\Models\ExamRoom;

class ExportController extends Controller
{
    /**
     * Export hasil ujian ke PDF.
     */
    public function exportRoomResults(ExamRoom $room)
    {
        $this->guardLecturer();
        $this->ensureCanManage($room);

        abort_unless($room->status === 'CLOSED', 422, 'Export PDF hanya tersedia setelah ujian selesai.');

        $room->load([
            'proposal.examQuestions',
            'proposal.course',
            'attempts' => function ($q) {
                $q->whereIn('status', ['SUBMITTED', 'AUTO_SUBMITTED_TIME', 'AUTO_SUBMITTED_VIOLATION'])
                  ->with(['user:id,name,identity_id', 'answers' => function($query) {
                      $query->whereNotNull('score');
                  }])
                  ->orderBy('submitted_at');
            }
        ]);

        // Hitung statistik
        $totalAttempts = $room->attempts->count();
        $gradedAttempts = $room->attempts->filter(function($attempt) {
            return $attempt->score !== null;
        })->count();
        $avgScore = $room->attempts->whereNotNull('score')->avg('score');

        // Prepare data untuk PDF
        $data = [
            'room' => $room,
            'attempts' => $room->attempts,
            'totalAttempts' => $totalAttempts,
            'gradedAttempts' => $gradedAttempts,
            'avgScore' => $avgScore ? round($avgScore, 2) : 0,
            'exportDate' => now()->translatedFormat('d F Y H:i'),
            'exportBy' => Auth::user()->name,
        ];

        $pdf = Pdf::loadView('ujian::exports.room-results', $data);
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Hasil_Ujian_' . str_replace(' ', '_', $room->title) . '_' . now()->format('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }

    private function guardLecturer(): void
    {
        $user = Auth::user();
        $allowed = $user && $user->roles()
            ->whereIn('role_code', ['ADM', 'DSN', 'KPD'])
            ->exists();

        abort_unless($allowed, 403, 'Hanya dosen / kaprodi / administrator yang boleh mengakses.');
    }

    private function ensureCanManage(ExamRoom $room): void
    {
        $user = Auth::user();
        $isAdmin = $user->roles()->where('role_code', 'ADM')->exists();
        $isCreator = $room->created_by === $user->id;

        abort_unless($isAdmin || $isCreator, 403, 'Anda tidak berhak mengakses ruang ujian ini.');
    }
}
