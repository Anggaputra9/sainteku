<?php

namespace Modules\ManajemenAchievement\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;  // ✅ TAMBAHKAN INI
use Illuminate\Http\Request;
use Modules\ManajemenAchievement\Models\DosenAchievement;
use Modules\ManajemenAchievement\Models\DosenKategori;
use Modules\ManajemenAchievement\Models\DosenTingkat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsappService;  // ✅ PASTIKAN INI, BUKAN TRAIT!

class DosenController extends Controller
{
    /**
     * Display a listing of all dosen achievements
     */
    public function index(Request $request)
    {
        $query = DosenAchievement::with(['user', 'kategori', 'tingkat']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $achievements = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get year list for filter
        $tahunList = DosenAchievement::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('manajemenachievement::admin.dosen.index', compact('achievements', 'tahunList'));
    }

    /**
     * Display pending dosen achievements
     */
    public function pending(Request $request)
    {
        $query = DosenAchievement::with(['user', 'kategori', 'tingkat'])
            ->where('status', 'pending');

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $achievements = $query->orderBy('created_at', 'asc')->paginate(15);

        // Get year list for filter
        $tahunList = DosenAchievement::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('manajemenachievement::admin.dosen.pending', compact('achievements', 'tahunList'));
    }

    /**
     * Display the specified dosen achievement
     */
    public function show($id)
    {
        $achievement = DosenAchievement::with(['user', 'kategori', 'tingkat', 'approver'])
            ->findOrFail($id);

        return view('manajemenachievement::admin.dosen.show', compact('achievement'));
    }

    /**
     * Approve dosen achievement
     */
    public function approve(Request $request, $id)
    {
        $achievement = DosenAchievement::findOrFail($id);

        $achievement->status = 'approved';
        $achievement->approved_by = Auth::user()->id;
        $achievement->approved_at = now();
        $achievement->save();

        // ✅ KIRIM NOTIFIKASI WA
        try {
            $wa = new WhatsappService();
            $wa->notifyApproved($achievement->user, $achievement, 'dosen');
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA approve dosen', ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.dosen.pending')
            ->with('success', 'Prestasi dosen berhasil disetujui');
    }


    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_note' => 'required|string|min:10'
        ]);

        $achievement = DosenAchievement::findOrFail($id);

        $achievement->status = 'rejected';
        $achievement->catatan_penolakan = $request->rejection_note;
        $achievement->approved_by = Auth::user()->id;
        $achievement->approved_at = now();
        $achievement->save();

        // ✅ KIRIM NOTIFIKASI WA
        try {
            $whatsapp = new WhatsappService();
            $whatsapp->notifyRejected(
                $achievement->user,
                $achievement,
                $request->rejection_note,
                'dosen'
            );
            Log::info('WA reject dosen sukses', ['id' => $id]);
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA reject dosen: ' . $e->getMessage());
        }

        return redirect()->route('admin.dosen.pending')
            ->with('success', 'Prestasi dosen ditolak');
    }
    
}
