<?php

namespace Modules\MonevAkademik\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\MonevAkademik\App\Models\ExamProposal;
use Modules\MonevAkademik\App\Models\ExamReview;
use App\Models\User;
use Modules\MonevAkademik\App\Models\ExamQuestionLog;
use App\Services\NotifService;

class ExamReviewController extends Controller
{
    // 1. FUNGSI UNTUK MINTA REVISI
    public function revise(Request $request, $uuid)
    {
        $proposal = ExamProposal::where('uuid', $uuid)->firstOrFail();

        DB::beginTransaction();
        try {
            // 1. Update status jadi revisi
            $proposal->update(['status' => 'REVISED']);

            // 2. Simpan Catatan Umum
            ExamReview::create([
                'proposal_id' => $proposal->id,
                'reviewer_id' => Auth::id(),
                'status' => 'REVISED',
                'comment' => $request->comment ?? 'Berkas dikembalikan untuk diperbaiki.'
            ]);

            // 3. Simpan Catatan PER SOAL ke tabel Log
            if ($request->has('question_comments') && is_array($request->question_comments)) {
                foreach ($request->question_comments as $orderNo => $msg) {
                    if (!empty($msg)) {

                        // GANTI DARI: \App\Models\ExamQuestionLog::create
                        // JADI INI (Biar dia pake import yang dari Modules di atas):
                        ExamQuestionLog::create([
                            'proposal_id' => $proposal->id,
                            'order_no' => $orderNo,
                            'user_id' => Auth::id(),
                            'type' => 'Komentar Kaprodi',
                            'message' => $msg
                        ]);

                    }
                }
            }

            DB::commit();
            NotifService::sendToUser($proposal->created_by, [
                'action' => 'memberikan catatan revisi pada soal',
                'item_name' => $proposal->exam_type . ' (' . ($proposal->course->course_name ?? 'Matkul') . ')',
                'type' => 'Revisi',
                'url' => route('monevakademik.tashih.index'),
                'reference_id' => $proposal->uuid,
                'click_action' => 'open_tashih_modal',
                'status' => 'offline' // Bikin titik merah di UI
            ]);
            return redirect()->back()->with('success', 'Pengajuan dikembalikan ke dosen beserta catatan revisi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses review: ' . $e->getMessage());
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
            NotifService::sendToUser($proposal->created_by, [
                'action' => 'telah menyetujui pengajuan soal',
                'item_name' => $proposal->exam_type . ' (' . ($proposal->course->course_name ?? 'Matkul') . ')',
                'type' => 'Disetujui',
                'url' => route('monevakademik.tashih.index'),
                'reference_id' => $proposal->uuid,
                'click_action' => 'open_tashih_modal',
                'status' => 'online' // Bikin titik hijau di UI
            ]);
            return redirect()->route('monevakademik.tashih.index')
                ->with('success', 'Pengajuan ujian berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui pengajuan: ' . $e->getMessage());
        }
    }
}