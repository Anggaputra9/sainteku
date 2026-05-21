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
            $personalTashihQ = DB::table('trx_exam_proposals')->where('created_by', $user->id);

            $data['examSubmitted'] = (clone $personalTashihQ)->where('status', 'SUBMITTED')->count();
            $data['examApproved'] = (clone $personalTashihQ)->where('status', 'APPROVED')->count();
            $data['examRevised'] = (clone $personalTashihQ)->where('status', 'REVISED')->count();

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
            $personalInfraQ = DB::table('trx_inventory_loans')->where('user_id', $user->id);

            $data['infraPending'] = (clone $personalInfraQ)->where('status', 0)->count();
            $data['infraDipinjam'] = (clone $personalInfraQ)->where('status', 1)->count();
            $data['infraSelesai'] = (clone $personalInfraQ)->where('status', 3)->count();

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
            $personalDocQ = DB::table('trx_document')->where('created_by', $user->id);

            $data['docPending'] = (clone $personalDocQ)->whereIn('status', [1, 2])->count(); // 1/2 = Draft/menunggu validasi
            $data['docApproved'] = (clone $personalDocQ)->where('status', 3)->count(); // 3 = Disetujui
            $data['docRevision'] = (clone $personalDocQ)->where('status', 4)->count(); // 4 = Perlu revisi/ditolak

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