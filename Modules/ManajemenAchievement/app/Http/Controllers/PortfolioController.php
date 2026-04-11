<?php

namespace Modules\ManajemenAchievement\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\ManajemenAchievement\Models\Achievement;
use Modules\ManajemenAchievement\Models\DosenAchievement;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Menampilkan daftar user yang memiliki prestasi
     */
    public function index(Request $request)
    {
        $query = User::where(function ($q) {
            $q->whereHas('achievements', function ($sub) {
                $sub->where('status', 'approved');
            })->orWhereHas('dosenAchievements', function ($sub) {
                $sub->where('status', 'approved');
            });
        });

        // Filter berdasarkan type
        if ($request->type == 'dosen') {
            $query = User::whereHas('dosenAchievements', function ($q) {
                $q->where('status', 'approved');
            });
        } elseif ($request->type == 'mahasiswa') {
            $query = User::whereHas('achievements', function ($q) {
                $q->where('status', 'approved');
            });
        }

        $users = $query->paginate(12);

        return view('manajemenachievement::portfolio.index', compact('users'));
    }

    /**
     * Menampilkan detail portfolio user
     */
    public function show($userId, Request $request)
    {
        $user = User::with([
            'achievements' => function ($q) {
                $q->with(['type', 'level'])->where('status', 'approved');
            },
            'dosenAchievements' => function ($q) {
                $q->with(['kategori', 'tingkat'])->where('status', 'approved');
            }
        ])->findOrFail($userId);

        // Ambil prestasi mahasiswa
        $mahasiswaAchievements = $user->achievements->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => 'mahasiswa',
                'kategori' => $item->type->description ?? 'Prestasi',
                'judul' => $item->title,
                'deskripsi' => $item->description,
                'tanggal' => $item->achievement_date,
                'tingkat' => $item->level->description ?? '-',
                'file_path' => $item->file_path,
                'file_name' => $item->file_name,
                'url' => $item->url,
                'penerbit' => $item->publisher,
                'created_at' => $item->created_at,
            ];
        });

        // Ambil prestasi dosen
        $dosenAchievements = $user->dosenAchievements->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => 'dosen',
                'kategori' => $item->kategori->nama ?? 'Prestasi Dosen',
                'judul' => $item->judul,
                'deskripsi' => $item->deskripsi,
                'tanggal' => $item->tanggal,
                'tingkat' => $item->tingkat->nama ?? '-',
                'file_path' => $item->file_path,
                'file_name' => $item->file_name,
                'url' => $item->url,
                'penerbit' => $item->penyelenggara,
                'created_at' => $item->created_at,
            ];
        });

        // Gabungkan
        $allAchievements = $mahasiswaAchievements->concat($dosenAchievements);

        // Filter tahun
        if ($request->filled('tahun')) {
            $allAchievements = $allAchievements->filter(function ($item) use ($request) {
                return date('Y', strtotime($item['tanggal'])) == $request->tahun;
            });
        }

        // Urutkan
        $allAchievements = $allAchievements->sortByDesc('tanggal');
        $achievementsByYear = $allAchievements->groupBy(function ($item) {
            return date('Y', strtotime($item['tanggal']));
        });

        // Statistik
        $statistics = [
            'total' => $allAchievements->count(),
            'mahasiswa' => $mahasiswaAchievements->count(),
            'dosen' => $dosenAchievements->count(),
            'per_tahun' => $allAchievements->groupBy(function ($item) {
                return date('Y', strtotime($item['tanggal']));
            })->map->count(),
        ];

        // Tahun untuk filter
        $tahunList = $allAchievements->map(function ($item) {
            return date('Y', strtotime($item['tanggal']));
        })->unique()->sortDesc();

        return view('manajemenachievement::portfolio.show', compact(
            'user',
            'achievementsByYear',
            'statistics',
            'tahunList'
        ));
    }
}
