<?php

namespace Modules\ManajemenAchievement\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ManajemenAchievement\Models\Achievement;
use Modules\ManajemenAchievement\Models\AchievementType;
use Modules\ManajemenAchievement\Models\AchievementLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    /**
     * Cek akses: hanya mahasiswa atau admin super
     */
    private function checkAccess()
    {
        $user = Auth::user();
        $isAdmin = $user->roles()->where('role_code', 'ADM')->exists();
        $isMahasiswa = ($user->user_type == 'MHS' || $user->roles()->where('role_code', 'MHS')->exists());

        if (!$isMahasiswa && !$isAdmin) {
            abort(403, 'Unauthorized access. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
    }

    /**
     * Menampilkan daftar prestasi mahasiswa
     */
    public function index(Request $request)
    {
        $this->checkAccess();

        $user = Auth::user();

        $query = Achievement::with(['type', 'level'])
            ->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $achievements = $query->orderBy('created_at', 'desc')->paginate(10);

        $types = AchievementType::where('is_active', '1')->get();
        $levels = AchievementLevel::where('is_active', '1')->get();

        return view('manajemenachievement::index', compact('achievements', 'types', 'levels'));
    }

    public function create()
    {
        $this->checkAccess();

        $types = AchievementType::where('is_active', '1')->get();
        $levels = AchievementLevel::where('is_active', '1')->get();

        return view('manajemenachievement::create', compact('types', 'levels'));
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'achievement_type_id' => 'required|exists:mst_achievement_type,id',
            'achievement_level_id' => 'required|exists:mst_achievement_level,id',
            'title' => 'required|string|max:255',
            'achievement_date' => 'required|date',
            'description' => 'nullable|string',
            'publication_type' => 'nullable|string',
            'publisher' => 'nullable|string',
            'url' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();

        $data = [
            'user_id' => $user->id,
            'achievement_type_id' => $request->achievement_type_id,
            'achievement_level_id' => $request->achievement_level_id,
            'title' => $request->title,
            'description' => $request->description,
            'achievement_date' => $request->achievement_date,
            'publication_type' => $request->publication_type,
            'publisher' => $request->publisher,
            'url' => $request->url,
            'status' => 'pending',
            'unit_id' => $user->unit_id ?? null
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/achievements/mahasiswa', $fileName);

            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
        }

        Achievement::create($data);

        return redirect()->route('student.achievements.index')
            ->with('success', 'Prestasi berhasil diajukan');
    }

    public function show($id)
    {
        $this->checkAccess();

        $achievement = Achievement::with(['user', 'type', 'level'])
            ->where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('manajemenachievement::show', compact('achievement'));
    }

    public function edit($id)
    {
        $this->checkAccess();

        $achievement = Achievement::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $types = AchievementType::where('is_active', '1')->get();
        $levels = AchievementLevel::where('is_active', '1')->get();

        return view('manajemenachievement::edit', compact('achievement', 'types', 'levels'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAccess();

        $achievement = Achievement::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $request->validate([
            'achievement_type_id' => 'required|exists:mst_achievement_type,id',
            'achievement_level_id' => 'required|exists:mst_achievement_level,id',
            'title' => 'required|string|max:255',
            'achievement_date' => 'required|date',
            'description' => 'nullable|string',
            'publication_type' => 'nullable|string',
            'publisher' => 'nullable|string',
            'url' => 'nullable|url',
        ]);

        $achievement->update($request->all());

        return redirect()->route('student.achievements.index')
            ->with('success', 'Prestasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkAccess();

        $achievement = Achievement::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        if ($achievement->file_path) {
            Storage::delete($achievement->file_path);
        }

        $achievement->delete();

        return redirect()->route('student.achievements.index')
            ->with('success', 'Prestasi berhasil dihapus');
    }

    public function download($id)
    {
        $this->checkAccess();

        $achievement = Achievement::findOrFail($id);

        if (!Storage::exists($achievement->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return Storage::download($achievement->file_path, $achievement->file_name);
    }
}
