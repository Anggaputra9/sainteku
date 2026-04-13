<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\Entities\Unit;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    // 1. Menampilkan Halaman Utama (Index) beserta Modalnya
    public function index(Request $request)
    {
        // Ambil parameter dari query string
        $search = $request->input('search', '');
        $perPage = max(1, $request->input('per_page', 10));

        // Query data units dengan search filter
        $unitsQuery = Unit::query();

        // Terapkan filter search (cari di unit_name)
        if (!empty($search)) {
            $unitsQuery->where('unit_name', 'like', '%' . $search . '%');
        }

        $units = $unitsQuery->orderBy('id')->paginate($perPage);

        // Mengambil data untuk Dropdown di Modal Create & Edit
        $parentUnits = Unit::where('is_active', '1')->orderBy('unit_name')->get();
        $unitTypes = DB::table('ref_unit_type')->orderBy('id')->get(); // Mengambil dari tabel referensi tipe unit

        // Kirimkan ke view
        return view('masterdata::units.index', compact('units', 'parentUnits', 'unitTypes'));
    }

    // 2. Memproses Data dari Modal Tambah (Create)
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            // Pastikan ID wajib diisi, maksimal 4 karakter, dan belum ada di tabel mst_unit
            'unit_name' => 'required|string|max:100',
            'unit_parent' => 'nullable|string|max:4',
            'unit_type_id' => 'nullable|integer',
            // Checkbox tidak usah divalidasi karena kita tangani pakai $request->has() di bawah
        ]);

        $lastUnit = Unit::where('id', 'like', 'U%')->orderBy('id', 'desc')->first();
        if (!$lastUnit) {
            $newId = 'U001';
        } else {
            $lastId = $lastUnit->id;
            $lastNumber = (int) substr($lastId, 1);
            $nextNumber = $lastNumber + 1;
            $newId = 'U' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        // Simpan data
        Unit::create([
            'id' => $newId, // <--- GANTI JADI INI! Gunakan variabel $newId yang sudah di-generate
            'unit_name' => $request->unit_name,
            'unit_parent' => $request->unit_parent,
            'unit_type_id' => $request->unit_type_id,
            'is_active' => $request->has('is_active') ? '1' : '0',
            'created_at' => now(),
        ]);

        return redirect()->route('masterdata.units.index')
            ->with('success', 'Unit ' . $request->unit_name . ' berhasil ditambahkan!');
    }

    // 3. Memproses Data dari Modal Ubah (Edit)
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'unit_name' => 'required|string|max:100',
            'unit_parent' => 'nullable|string|max:4',
            'unit_type_id' => 'nullable|integer',
        ]);

        $unit->update([
            'unit_name' => $request->unit_name,
            'unit_parent' => $request->unit_parent,
            'unit_type_id' => $request->unit_type_id,
            'is_active' => $request->has('is_active') ? '1' : '0', // Penanganan checkbox yang aman
        ]);

        return redirect()->route('masterdata.units.index')
            ->with('success', 'Data unit ' . $unit->unit_name . ' berhasil diperbarui!');
    }

    // 4. Memproses Data dari Modal Hapus (Delete)
    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $namaUnit = $unit->unit_name; 
            
            $unit->delete();

            return redirect()->route('masterdata.units.index')
                ->with('success', 'Unit ' . $namaUnit . ' berhasil dihapus secara permanen!');
                
        } catch (\Illuminate\Database\QueryException $e) {
            // Menangkap error 1451 (Foreign Key Constraint Violation)
            if ($e->getCode() == "23000") {
                return redirect()->route('masterdata.units.index')
                    ->with('error', 'Gagal menghapus! Unit ini tidak bisa dihapus karena masih menaungi pengguna (User) atau entitas lain.');
            }
            
            // Jika error lain, tetap lemparkan
            throw $e;
        }
    }
}
