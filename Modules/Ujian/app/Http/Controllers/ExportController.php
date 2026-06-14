<?php

namespace Modules\Ujian\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\PdfImageHelper;
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
            'proposal.course:id,course_name',
            'attempts' => function ($q) {
                $q->whereIn('status', ['SUBMITTED', 'AUTO_SUBMITTED_TIME', 'AUTO_SUBMITTED_VIOLATION'])
                  ->with('user:id,name,identity_id')
                  ->withCount(['answers as answered_count' => fn ($query) => $query->where('is_answered', true)])
                  ->orderBy('submitted_at');
            }
        ]);

        $totalQuestions = $room->proposal->examQuestions->count();
        $totalAttempts = $room->attempts->count();
        $gradedAttempts = $room->attempts->filter(fn ($attempt) => $attempt->score !== null)->count();
        $avgScore = $room->attempts->whereNotNull('score')->avg('score');

        $data = [
            'room' => $room,
            'attempts' => $room->attempts,
            'totalQuestions' => $totalQuestions,
            'totalAttempts' => $totalAttempts,
            'gradedAttempts' => $gradedAttempts,
            'avgScore' => $avgScore ? round($avgScore, 2) : 0,
            'exportDate' => now()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') . ' WIB',
            'exportBy' => Auth::user()->name,
            'logoBase64' => PdfImageHelper::uinPrintLogoDataUri(),
        ];

        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
            'dpi' => 96,
        ])
            ->loadView('ujian::exports.room-results', $data)
            ->setPaper('a4', 'portrait');

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
