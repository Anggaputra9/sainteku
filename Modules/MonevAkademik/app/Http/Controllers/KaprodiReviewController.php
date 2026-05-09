<?php

namespace Modules\MonevAkademik\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MonevAkademik\App\Models\ExamProposal;
use Modules\MonevAkademik\App\Models\ExamReview;
use Illuminate\Support\Facades\Auth;

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

        return back()->with('warning', 'Catatan revisi telah dikirim ke Dosen bersangkutan.');
    }
}