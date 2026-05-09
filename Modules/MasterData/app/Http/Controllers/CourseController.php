<?php

namespace Modules\MasterData\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    // 1. Memuat Halaman Index Pertama Kali
    public function index(Request $request)
    {
        // Hanya memuat View awal. Datanya akan ditarik lewat Alpine.js (AJAX)
        $faculties = DB::table('mst_unit')
            ->where('is_active', '1')
            ->where('unit_type_id', 2) // Asumsi 2 adalah tipe Fakultas
            ->get();

        return view('masterdata::courses.index', compact('faculties'))->with('title', 'Daftar Mata Kuliah');
    }

    // 2. API: Mendapatkan daftar Prodi berdasarkan Fakultas yang dipilih
    public function getProdi(Request $request)
    {
        $fakultasId = $request->query('fakultas_id');

        if (!$fakultasId) {
            return response()->json([]);
        }

        $prodis = DB::table('mst_unit')
            ->where('is_active', '1')
            ->where('unit_type_id', 3)
            // ==========================================
            // FIX: Ganti parent_id jadi unit_parent
            // ==========================================
            ->where('unit_parent', $fakultasId)
            ->get(['id', 'unit_name']);

        return response()->json($prodis)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // 3. API: Mendapatkan data Mata Kuliah berdasarkan Filter & Pencarian
    public function getCoursesData(Request $request)
    {
        $search = $request->query('search');
        $fakultasId = $request->query('fakultas_id');
        $prodiId = $request->query('prodi_id');

        $query = DB::table('mst_course')
            ->leftJoin('mst_unit as prodi', 'mst_course.unit_id', '=', 'prodi.id')
            // ==========================================
            // FIX: Ganti parent_id jadi unit_parent
            // ==========================================
            ->leftJoin('mst_unit as fakultas', 'prodi.unit_parent', '=', 'fakultas.id')
            ->select('mst_course.*', 'prodi.unit_name as prodi_name', 'fakultas.unit_name as fakultas_name');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('mst_course.course_name', 'like', "%{$search}%")
                    ->orWhere('mst_course.id', 'like', "%{$search}%");
            });
        }

        if ($prodiId) {
            $query->where('mst_course.unit_id', $prodiId);
        } elseif ($fakultasId) {
            // ==========================================
            // FIX: Ganti parent_id jadi unit_parent
            // ==========================================
            $query->where('prodi.unit_parent', $fakultasId);
        }

        // Pagination menggunakan fitur bawaan Laravel, merespons dalam bentuk JSON
        $courses = $query->orderBy('mst_course.id', 'asc')->paginate(12); // Gue set 12 biar gridnya pas rata

        return response()->json($courses)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // 4. Menyimpan Data Baru (Auto-Increment)
    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:100',
            'unit_id' => 'required|string|exists:mst_unit,id',
            'is_active' => 'required|in:0,1',
        ]);

        // LOGIKA AUTO-INCREMENT MENGGUNAKAN DB BUILDER
        $lastCourse = DB::table('mst_course')->orderBy('id', 'desc')->first();

        if ($lastCourse) {
            $lastNumber = (int) substr($lastCourse->id, 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $newId = 'MK' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        DB::table('mst_course')->insert([
            'id' => $newId,
            'course_name' => $request->course_name,
            'unit_id' => $request->unit_id,
            'is_active' => $request->is_active,
            'created_at' => now(),
        ]);

        return redirect()->route('masterdata.courses.index')->with('success', 'Data Mata Kuliah berhasil ditambahkan dengan Kode ' . $newId);
    }

    // 5. Memperbarui Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_name' => 'required|string|max:100',
            'unit_id' => 'required|string|exists:mst_unit,id',
            'is_active' => 'required|in:0,1',
        ]);

        DB::table('mst_course')->where('id', $id)->update([
            'course_name' => $request->course_name,
            'unit_id' => $request->unit_id,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('masterdata.courses.index')->with('success', 'Data Mata Kuliah berhasil diperbarui.');
    }

    // 6. Menghapus Data
    public function destroy($id)
    {
        try {
            DB::table('mst_course')->where('id', $id)->delete();
            return redirect()->route('masterdata.courses.index')->with('success', 'Data Mata Kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.courses.index')->with('error', 'Gagal menghapus! Mata kuliah ini sedang terhubung dengan relasi CPMK/CPL atau Pengajuan Soal.');
        }
    }
}