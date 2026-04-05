<?php

namespace Modules\MonevAkademik\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\MonevAkademik\App\Models\ExamProposal;
use Modules\MonevAkademik\App\Models\ExamReview;
use App\Models\User;

class ExamReviewController extends Controller
{
    // 1. FUNGSI UNTUK MINTA REVISI
    public function revise(Request $request, $uuid)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $proposal = ExamProposal::where('uuid', $uuid)->firstOrFail();

            // Ubah status pengajuan jadi REVISED
            $proposal->update(['status' => 'REVISED']);

            // Simpan catatan revisi ke tabel histori
            ExamReview::create([
                'proposal_id' => $proposal->id,
                'reviewer_id' => Auth::id(),
                'status' => 'REVISED',
                'comment' => $request->comment,
            ]);

            DB::commit();
            return redirect()->route('monevakademik.tashih.index')
                ->with('success', 'Catatan revisi berhasil dikirim ke Dosen bersangkutan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim revisi: ' . $e->getMessage());
        }
    }

    // 2. FUNGSI UNTUK SETUJUI & TANDA TANGAN
    public function approve(Request $request, $uuid)
    {
        DB::beginTransaction();
        try {
            $proposal = ExamProposal::where('uuid', $uuid)->firstOrFail();
            $user = Auth::user();

            // Handle Tanda Tangan Digital (Jika User Belum Punya TTD di Database)
            if (empty($user->signature) && $request->filled('signature_base64')) {
                // Di sini kita simpan base64 nya.
                // (Untuk best practice production, base64 ini bisa diubah jadi file gambar asli (.png) 
                // lalu disimpan di storage/public, tapi simpan base64 langsung ke DB juga gapapa kalau mau simple).

                // Asumsi di model User pakai update() biasa:
                User::where('id', $user->id)->update([
                    'signature' => $request->signature_base64
                ]);
            }

            // Ubah status pengajuan jadi APPROVED
            $proposal->update(['status' => 'APPROVED']);

            // Simpan histori persetujuan
            ExamReview::create([
                'proposal_id' => $proposal->id,
                'reviewer_id' => Auth::id(),
                'status' => 'APPROVED',
                'comment' => 'Pengajuan soal telah disetujui dan divalidasi oleh Kaprodi.',
            ]);

            DB::commit();
            return redirect()->route('monevakademik.tashih.index')
                ->with('success', 'Pengajuan ujian berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui pengajuan: ' . $e->getMessage());
        }
    }
}