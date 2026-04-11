<?php

namespace Modules\ManajemenAchievement\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ManajemenAchievement\Models\DosenAchievement;
use Modules\ManajemenAchievement\Models\DosenKategori;
use Modules\ManajemenAchievement\Models\DosenTingkat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DosenAchievementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = DosenAchievement::with(['kategori', 'tingkat'])
            ->where('user_id', $user->id);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $achievements = $query->orderBy('created_at', 'desc')->paginate(10);

        // Data untuk filter tahun
        $tahunList = DosenAchievement::where('user_id', $user->id)
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // ✅ TAMBAHKAN INI UNTUK MODAL
        $kategori = DosenKategori::where('is_active', true)->get();
        $tingkat = DosenTingkat::where('is_active', true)->get();

        return view('manajemenachievement::dosen.index', compact(
            'achievements',
            'tahunList',
            'kategori',      // ✅ Pastikan ini ada
            'tingkat'        // ✅ Pastikan ini ada
        ));
    }

    public function create()
    {
        $kategori = DosenKategori::where('is_active', true)->get();
        $tingkat = DosenTingkat::where('is_active', true)->get();

        return view('manajemenachievement::dosen.create', compact('kategori', 'tingkat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:dosen_kategori,id',
            'tingkat_id' => 'required|exists:dosen_tingkat,id',
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $user = Auth::user();

        $data = [
            'user_id' => $user->id,
            'kategori_id' => $request->kategori_id,
            'tingkat_id' => $request->tingkat_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'penyelenggara' => $request->penyelenggara,
            'url' => $request->url,
            'jenis_publikasi' => $request->jenis_publikasi,
            'nama_jurnal' => $request->nama_jurnal,
            'volume' => $request->volume,
            'nomor' => $request->nomor,
            'halaman' => $request->halaman,
            'issn' => $request->issn,
            'nomor_pendaftaran' => $request->nomor_pendaftaran,
            'status_hki' => $request->status_hki,
            'isbn' => $request->isbn,
            'penerbit' => $request->penerbit,
            'jumlah_halaman' => $request->jumlah_halaman,
            'status' => 'pending',
            'unit_id' => $user->unit_id ?? null
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/achievements/dosen', $fileName);

            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
        }

        DosenAchievement::create($data);

        return redirect()->route('dosen.repository.index')
            ->with('success', 'Prestasi dosen berhasil diajukan');
    }

    public function show($id)
    {
        $achievement = DosenAchievement::with(['user', 'kategori', 'tingkat'])
            ->where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('manajemenachievement::dosen.show', compact('achievement'));
    }

    public function edit($id)
    {
        $achievement = DosenAchievement::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $kategori = DosenKategori::where('is_active', true)->get();
        $tingkat = DosenTingkat::where('is_active', true)->get();

        return view('manajemenachievement::dosen.edit', compact('achievement', 'kategori', 'tingkat'));
    }

    public function update(Request $request, $id)
    {
        // ✅ PASTIKAN PAKAI MODEL
        $achievement = DosenAchievement::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $request->validate([
            'kategori_id' => 'required|exists:dosen_kategori,id',
            'tingkat_id' => 'required|exists:dosen_tingkat,id',
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
        ]);

        $achievement->update($request->all());

        return redirect()->route('dosen.repository.index')
            ->with('success', 'Prestasi dosen berhasil diperbarui');
    }

    public function destroy($id)
    {
        // ✅ PASTIKAN PAKAI MODEL
        $achievement = DosenAchievement::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        if ($achievement->file_path) {
            Storage::delete($achievement->file_path);
        }

        $achievement->delete();

        return redirect()->route('dosen.repository.index')
            ->with('success', 'Prestasi dosen berhasil dihapus');
    }

    public function download($id)
    {
        $achievement = DosenAchievement::findOrFail($id);

        if (!Storage::exists($achievement->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return Storage::download($achievement->file_path, $achievement->file_name);
    }
}
