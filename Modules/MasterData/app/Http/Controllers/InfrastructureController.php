<?php

namespace Modules\MasterData\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Gunakan model jika Anda sudah membuatnya. Contoh:
// use Modules\MasterData\app\Models\Inventory; 

class InfrastructureController extends Controller
{
    /**
     * Menampilkan halaman utama (Index) Data Infrastruktur
     */
    public function index(Request $request)
    {
        // OPSI 1: Jika Anda SUDAH membuat Model 'Inventory' (Disarankan)
        // $infrastructures = Inventory::with('type')->orderBy('created_at', 'desc')->paginate(10);

        // OPSI 2: Jika Anda BELUM membuat Model dan ingin langsung pakai Query Builder
        $infrastructures = DB::table('mst_inventory')
            ->leftJoin('mst_inventory_type', 'mst_inventory.inventory_type', '=', 'mst_inventory_type.id')
            ->select('mst_inventory.*', 'mst_inventory_type.description as type_description')
            ->orderBy('mst_inventory.created_at', 'desc')
            ->paginate(10);
        $inventoryTypes = DB::table('mst_inventory_type')->get();
        return view('masterdata::infrastructures.index', compact('infrastructures', 'inventoryTypes'));
    }

    public function destroy($id)
    {
        try {
            // Ambil nama barang terlebih dahulu untuk ditampilkan di pesan sukses
            $item = DB::table('mst_inventory')->where('id', $id)->first();
            
            if (!$item) {
                return redirect()->route('masterdata.infrastructures.index')
                    ->with('error', 'Data tidak ditemukan!');
            }

            // Hapus data
            DB::table('mst_inventory')->where('id', $id)->delete();

            return redirect()->route('masterdata.infrastructures.index')
                ->with('success', 'Data infrastruktur "' . $item->description . '" berhasil dihapus secara permanen!');

        } catch (\Illuminate\Database\QueryException $e) {
            // Error 1451 terjadi jika data inventaris ini sudah terpakai di tabel lain (Foreign Key)
            if ($e->getCode() == "23000") {
                return redirect()->route('masterdata.infrastructures.index')
                    ->with('error', 'Gagal menghapus! Infrastruktur ini sedang digunakan di transaksi peminjaman/modul lain.');
            }
            
            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // Fungsi-fungsi lain (store, update, destroy) bisa ditambahkan di sini nanti.../**
    public function store(Request $request)
    {
        // 1. Validasi Input dari Modal Create
        $request->validate([
            'description'    => 'required|string|max:255',
            'inventory_type' => 'required',
            'quantity'       => 'required|integer|min:1',
        ]);

        try {
            // 2. Generate ID Otomatis (Format: I0001, I0002, dst. Maksimal 5 karakter)
            $lastItem = DB::table('mst_inventory')->orderBy('id', 'desc')->first();
            if (!$lastItem) {
                $newId = 'I0001';
            } else {
                // Ambil angka dari ID terakhir, lalu tambah 1
                $lastNumber = (int) substr($lastItem->id, 1);
                $newId = 'I' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }

            // 3. Insert Data ke Tabel mst_inventory (Disesuaikan dengan kolom asli database)
            DB::table('mst_inventory')->insert([
                'id'             => $newId, // ID Wajib dimasukkan karena string bukan auto-increment
                'description'    => $request->description,
                'inventory_type' => $request->inventory_type,
                'quantity'       => $request->quantity,
                'created_at'     => now(), // Hanya created_at yang ada di migration
            ]);

            return redirect()->route('masterdata.infrastructures.index')
                ->with('success', 'Data infrastruktur "' . $request->description . '" berhasil ditambahkan dengan ID ' . $newId . '!');

        } catch (\Exception $e) {
            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui data infrastruktur di database (Proses Update)
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi Input dari Modal Edit
        $request->validate([
            'description'    => 'required|string|max:255',
            'inventory_type' => 'required',
            'quantity'       => 'required|integer|min:0',
        ]);

        try {
            // 2. Pastikan data yang mau diedit itu ada
            $item = DB::table('mst_inventory')->where('id', $id)->first();
            if (!$item) {
                return redirect()->route('masterdata.infrastructures.index')
                    ->with('error', 'Data tidak ditemukan!');
            }

            // 3. Update Data di Tabel mst_inventory (Tanpa updated_at/updated_by karena tidak ada di tabel)
            DB::table('mst_inventory')->where('id', $id)->update([
                'description'    => $request->description,
                'inventory_type' => $request->inventory_type,
                'quantity'       => $request->quantity,
            ]);

            return redirect()->route('masterdata.infrastructures.index')
                ->with('success', 'Data infrastruktur "' . $request->description . '" berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }
}