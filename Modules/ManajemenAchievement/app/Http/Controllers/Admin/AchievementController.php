<?php

namespace Modules\ManajemenAchievement\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ManajemenAchievement\Models\Achievement;
use Modules\ManajemenAchievement\Models\AchievementType;
use Modules\ManajemenAchievement\Models\AchievementLevel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class AchievementController extends Controller
{
    /**
     * Cek akses: hanya admin unit atau admin super
     */
    private function checkAccess()
    {
        $user = Auth::user();
        $isAdmin = $user->roles()->where('role_code', 'ADM')->exists();
        $isAdminUnit = $user->roles()->where('role_code', 'OPS')->exists();

        if (!$isAdminUnit && !$isAdmin) {
            abort(403, 'Unauthorized access. Hanya admin yang dapat mengakses halaman ini.');
        }
    }

    /**
     * Display a listing of all achievements (for admin)
     */
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = Achievement::with(['user', 'type', 'level']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('achievement_date', $request->tahun);
        }

        // Filter by unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $achievements = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get year list for filter
        $tahunList = Achievement::selectRaw('YEAR(achievement_date) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Get unit list for filter
        $units = User::select('unit_id')->whereNotNull('unit_id')->distinct()->get();

        return view('manajemenachievement::admin.index', compact('achievements', 'tahunList', 'units'));
    }

    /**
     * Display pending achievements
     */
    public function pending(Request $request)
    {
        $this->checkAccess();

        $query = Achievement::with(['user', 'type', 'level'])
            ->where('status', 'pending');

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('achievement_date', $request->tahun);
        }

        $achievements = $query->orderBy('created_at', 'asc')->paginate(15);

        // Get year list for filter
        $tahunList = Achievement::selectRaw('YEAR(achievement_date) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('manajemenachievement::admin.pending', compact('achievements', 'tahunList'));
    }

    /**
     * Display the specified achievement
     */
    public function show($id)
    {
        $this->checkAccess();

        $achievement = Achievement::with(['user', 'type', 'level', 'approver'])
            ->findOrFail($id);

        return view('manajemenachievement::admin.show', compact('achievement'));
    }

    /**
     * Approve achievement
     */
    public function approve(Request $request, $id)
    {
        $this->checkAccess();

        $achievement = Achievement::findOrFail($id);

        $achievement->status = 'approved';
        $achievement->approved_by = Auth::user()->id;
        $achievement->approved_at = now();
        $achievement->save();

        // ✅ KIRIM NOTIFIKASI WA
        try {
            $whatsapp = new WhatsappService();
            $whatsapp->notifyApproved($achievement->user, $achievement, 'mahasiswa');
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA approve: ' . $e->getMessage());
        }

        return redirect()->route('admin.achievements.pending')
            ->with('success', 'Prestasi berhasil disetujui');
    }

    /**
     * Reject achievement
     */
    public function reject(Request $request, $id)
    {
        $this->checkAccess();

        $request->validate([
            'rejection_note' => 'required|string|min:10'
        ]);

        $achievement = Achievement::findOrFail($id);

        $achievement->status = 'rejected';
        $achievement->rejection_note = $request->rejection_note;
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
                'mahasiswa'
            );
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA reject: ' . $e->getMessage());
        }

        return redirect()->route('admin.achievements.pending')
            ->with('success', 'Prestasi ditolak');
    }

    /**
     * Get achievement statistics
     */
    public function statistics()
    {
        $this->checkAccess();

        $total = Achievement::count();
        $pending = Achievement::where('status', 'pending')->count();
        $approved = Achievement::where('status', 'approved')->count();
        $rejected = Achievement::where('status', 'rejected')->count();

        $perType = Achievement::with('type')
            ->selectRaw('achievement_type_id, count(*) as total')
            ->groupBy('achievement_type_id')
            ->get();

        $perYear = Achievement::selectRaw('YEAR(achievement_date) as tahun, count(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get();

        return response()->json([
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'per_type' => $perType,
            'per_year' => $perYear
        ]);
    }
}
