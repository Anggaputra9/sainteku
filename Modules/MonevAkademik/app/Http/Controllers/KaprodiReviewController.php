<?php

namespace Modules\MonevAkademik\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MonevAkademik\app\Models\ExamProposal;
use Modules\MonevAkademik\app\Models\ExamReview;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifService;

class KaprodiReviewController extends Controller
{
    // Menampilkan daftar pengajuan yang masuk ke prodi
    public function index()
    {
        $proposals = ExamProposal::with(['course', 'creator'])
            ->where('status', 'SUBMITTED')
            ->get(); // Nanti bisa difilter sesuai unit_id Kaprodi

        return view('monevakademik::kaprodi.review.index', compact('proposals'))->with('title', 'Daftar Pengajuan Soal Masuk Prodi');
    }

    // Menyetujui soal (ACC) + trigger logic canvas tanda tangan
    public function approve(Request $request, $id)
    {
        $proposal = ExamProposal::findOrFail($id);

        // Logika simpan base64 canvas tanda tangan ke $request->signature_base64 jika ada
        if ($request->has('signature_base64')) {
            $user = Auth::user();
            $user->signature = $request->signature_base64; // Simpan ke mst_user
            $user->save();
        }

        $proposal->update([
            'status' => 'APPROVED',
            'approved_by' => Auth::id()
        ]);

        // Notifikasi balik ke dosen pengaju
        try {
            NotifService::sendToUser($proposal->created_by, [
                'action'       => 'menyetujui pengajuan soal',
                'item_name'    => ($proposal->exam_type ?? 'Soal') . ' (' . ($proposal->course->course_name ?? 'Matkul') . ')',
                'type'         => 'Tashih Soal',
                'url'          => route('monevakademik.tashih.index'),
                'reference_id' => $proposal->uuid ?? $proposal->id,
                'click_action' => 'open_tashih_modal',
                'status'       => 'online',
                'send_whatsapp' => true,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Notif Kaprodi approve gagal: ' . $e->getMessage());
        }

        return back()->with('success', 'Pengajuan soal berhasil disetujui!');
    }

    // Memberikan catatan revisi
    public function revise(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string']);

        $proposal = ExamProposal::findOrFail($id);
        $proposal->update(['status' => 'REVISED']);

        ExamReview::create([
            'proposal_id' => $proposal->id,
            'reviewer_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        // Notifikasi balik ke dosen pengaju
        try {
            NotifService::sendToUser($proposal->created_by, [
                'action'       => 'memberikan catatan revisi pada soal',
                'item_name'    => ($proposal->exam_type ?? 'Soal') . ' (' . ($proposal->course->course_name ?? 'Matkul') . ')',
                'type'         => 'Tashih Soal',
                'url'          => route('monevakademik.tashih.index'),
                'reference_id' => $proposal->uuid ?? $proposal->id,
                'click_action' => 'open_tashih_modal',
                'status'       => 'offline',
                'send_whatsapp' => true,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Notif Kaprodi revise gagal: ' . $e->getMessage());
        }

        return back()->with('warning', 'Catatan revisi telah dikirim ke Dosen bersangkutan.');
    }
}
