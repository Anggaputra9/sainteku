<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        // ==========================================
        // 1. CEK AKSES MASTER DATA (KHUSUS ADMIN)
        // ==========================================
        $data['isAdmin'] = DB::table('trx_user_role')
            ->join('mst_role', 'trx_user_role.role_id', '=', 'mst_role.id')
            ->where('user_id', $user->id)
            ->where('role_code', 'ADM')
            ->exists();

        if ($data['isAdmin']) {
            $data['totalUsers'] = DB::table('mst_user')->count();
            $data['totalRoles'] = DB::table('mst_role')->count();
            $data['totalUnits'] = DB::table('mst_unit')->count();
            $data['totalCourses'] = DB::table('mst_course')->count();
            $data['totalPeriods'] = DB::table('mst_period')->count();
            $data['totalInfraMaster'] = DB::table('mst_inventory')->count();
        }

        // ==========================================
        // 2. CEK AKSES TASHIH SOAL (MONEV AKADEMIK)
        // ==========================================
        $data['showTashih'] = $user->hasPermission(3, 'C') || $user->hasPermission(3, 'A');

        if ($data['showTashih']) {
            $data['isReviewerMonev'] = DB::table('trx_user_role')
                ->join('mst_role', 'trx_user_role.role_id', '=', 'mst_role.id')
                ->where('user_id', $user->id)
                ->whereIn('role_code', ['KPD', 'RVI', 'RVE', 'ADM', 'DKN'])
                ->exists();

            // MURNI PERSONAL (Pengajuan punya dia sendiri)
            $personalTashih = DB::table('trx_exam_proposals')->where('created_by', $user->id)
                ->selectRaw('COUNT(CASE WHEN status = ? THEN 1 END) AS submitted,
                    COUNT(CASE WHEN status = ? THEN 1 END) AS approved,
                    COUNT(CASE WHEN status = ? THEN 1 END) AS revised', ['SUBMITTED', 'APPROVED', 'REVISED'])
                ->first();

            $data['examSubmitted'] = (int) $personalTashih->submitted;
            $data['examApproved'] = (int) $personalTashih->approved;
            $data['examRevised'] = (int) $personalTashih->revised;

            // BANK SOAL (Global - Semua soal yang proposalnya sudah APPROVED)
            $data['totalBankSoal'] = DB::table('trx_questions')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('trx_exam_questions')
                        ->join('trx_exam_proposals', 'trx_exam_questions.proposal_id', '=', 'trx_exam_proposals.id')
                        ->whereColumn('trx_exam_questions.question_id', 'trx_questions.id')
                        ->where('trx_exam_proposals.status', 'APPROVED');
                })->count();

            // ANTREAN REVIEW (Khusus Kaprodi/Reviewer - Global)
            $data['examNeedAcc'] = 0;
            if ($data['isReviewerMonev']) {
                $data['examNeedAcc'] = DB::table('trx_exam_proposals')->where('status', 'SUBMITTED')->count();
            }
        }

        // ==========================================
        // 3. CEK AKSES INFRASTRUKTUR
        // ==========================================
        $data['showInfra'] = $user->hasPermission(6, 'C') || $user->hasPermission(6, 'A');

        if ($data['showInfra']) {
            $data['isReviewerInfra'] = $user->hasPermission(6, 'A');

            // MURNI PERSONAL (Peminjaman dia sendiri)
            $personalInfra = DB::table('trx_inventory_loans')->where('user_id', $user->id)
                ->selectRaw('COUNT(CASE WHEN status = ? THEN 1 END) AS pending,
                    COUNT(CASE WHEN status = ? THEN 1 END) AS borrowed,
                    COUNT(CASE WHEN status = ? THEN 1 END) AS completed', [0, 1, 3])
                ->first();

            $data['infraPending'] = (int) $personalInfra->pending;
            $data['infraDipinjam'] = (int) $personalInfra->borrowed;
            $data['infraSelesai'] = (int) $personalInfra->completed;

            // ANTREAN REVIEW (Khusus Admin/Approver Infra - Global)
            $data['infraNeedAcc'] = 0;
            if ($data['isReviewerInfra']) {
                $data['infraNeedAcc'] = DB::table('trx_inventory_loans')->where('status', 0)->count();
            }
        }

        // ==========================================
        // 4. CEK AKSES REPOSITORI DOKUMEN
        // ==========================================
        $data['showDoc'] = $user->hasPermission(1, 'C') || $user->hasPermission(1, 'A');

        if ($data['showDoc']) {
            $data['isReviewerDoc'] = $user->hasPermission(1, 'A');

            // MURNI PERSONAL (Dokumen unggahan dia sendiri)
            $personalDoc = DB::table('trx_document')->where('created_by', $user->id)
                ->selectRaw('COUNT(CASE WHEN status IN (?, ?) THEN 1 END) AS pending,
                    COUNT(CASE WHEN status = ? THEN 1 END) AS approved,
                    COUNT(CASE WHEN status = ? THEN 1 END) AS revision', [1, 2, 3, 4])
                ->first();

            $data['docPending'] = (int) $personalDoc->pending; // 1/2 = Draft/menunggu validasi
            $data['docApproved'] = (int) $personalDoc->approved; // 3 = Disetujui
            $data['docRevision'] = (int) $personalDoc->revision; // 4 = Perlu revisi/ditolak

            // TOTAL REPOSITORI (Global - Hanya yang sudah APPROVED)
            $data['totalDokumen'] = DB::table('trx_document')->where('status', 3)->count();

            // ANTREAN REVIEW (Khusus Reviewer Dokumen - Global)
            $data['docNeedAcc'] = 0;
            if ($data['isReviewerDoc']) {
                $data['docNeedAcc'] = DB::table('trx_document')->whereIn('status', [1, 2])->count();
            }
        }

        return view('pages.dashboard', $data)->with('title', 'DASHBOARD');
    }
}
