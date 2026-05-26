<?php

namespace Modules\MasterData\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Tambahan wajib untuk menghapus file lama

class InfrastructureController extends Controller
{
    /**
     * Menampilkan daftar infrastruktur dan inventaris
     */
    public function index(Request $request)
    {
        // Ambil parameter dari query string
        $search = $request->input('search', '');
        $type = $request->input('inventory_type', '');
        $status = $request->input('status', '');
        $perPage = min(100, max(1, (int) $request->input('per_page', 10)));

        // Ambil data inventaris beserta relasinya dengan search filter
        $infrastructures = DB::table('mst_inventory')
            ->leftJoin('mst_inventory_type', 'mst_inventory.inventory_type', '=', 'mst_inventory_type.id')
            ->leftJoin('mst_unit', 'mst_inventory.unit_id', '=', 'mst_unit.id')
            ->select('mst_inventory.*', 'mst_inventory_type.description as type_description', 'mst_unit.unit_name');

        // Terapkan filter search (cari di item_name, brand, description)
        if (!empty($search)) {
            $infrastructures->where(function ($query) use ($search) {
                $query->where('mst_inventory.item_name', 'like', '%' . $search . '%')
                    ->orWhere('mst_inventory.id', 'like', '%' . $search . '%')
                    ->orWhere('mst_inventory.brand', 'like', '%' . $search . '%')
                    ->orWhere('mst_inventory.description', 'like', '%' . $search . '%');
            });
        }

        if ($type !== '') {
            $infrastructures->where('mst_inventory.inventory_type', $type);
        }

        if ($status !== '' && in_array($status, ['0', '1'], true)) {
            $infrastructures->where('mst_inventory.status', $status);
        }

        $infrastructures = $infrastructures
            ->orderBy('mst_inventory.created_at', 'desc')
            ->paginate($perPage);

        // Ambil data untuk opsi dropdown di Modal Create & Edit
        $inventoryTypes = DB::table('mst_inventory_type')->get();
        $units = DB::table('mst_unit')->where('is_active', '1')->get();

        return view('masterdata::infrastructures.index', compact('infrastructures', 'inventoryTypes', 'units'))->with('title', 'Daftar Infrastruktur & Inventaris');
    }

    /**
     * Menyimpan data baru ke database (Create)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'item_name'      => 'required|string|max:255',
            'inventory_type' => 'required',
            'stock'          => 'required|integer|min:0',
            'brand'          => 'nullable|string|max:100',
            'unit_measure'   => 'nullable|string|max:50',
            'price'          => 'nullable|numeric|min:0',
            'status'         => 'required|in:0,1',
            'unit_id'        => 'nullable|string|max:4',
            'description'    => 'nullable|string',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maks 2MB
        ]);

        try {
            // 2. Generate Custom ID (I0001, I0002, dst)
            $lastItem = DB::table('mst_inventory')->orderBy('id', 'desc')->first();
            $newId = !$lastItem ? 'I0001' : 'I' . str_pad((int) substr($lastItem->id, 1) + 1, 4, '0', STR_PAD_LEFT);

            // 3. Proses Upload Foto (Jika Ada)
            $photoPath = null;
            if ($request->hasFile('photo')) {
                // Simpan ke storage/app/public/infrastructures
                $photoPath = $request->file('photo')->store('infrastructures', 'public');
            }

            // 4. Insert ke Database
            DB::table('mst_inventory')->insert([
                'id'             => $newId,
                'item_name'      => $request->item_name,
                'inventory_type' => $request->inventory_type,
                'brand'          => $request->brand,
                'unit_measure'   => $request->unit_measure,
                'stock'          => $request->stock,
                'price'          => $request->price ?? 0,
                'status'         => $request->status,
                'unit_id'        => $request->unit_id,
                'description'    => $request->description,
                'photo'          => $photoPath,
                'created_at'     => now(),
            ]);

            return redirect()->route('masterdata.infrastructures.index')
                ->with('success', 'Data "' . $request->item_name . '" berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui data yang sudah ada di database (Update)
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'item_name'      => 'required|string|max:255',
            'inventory_type' => 'required',
            'stock'          => 'required|integer|min:0',
            'brand'          => 'nullable|string|max:100',
            'unit_measure'   => 'nullable|string|max:50',
            'price'          => 'nullable|numeric|min:0',
            'status'         => 'required|in:0,1',
            'unit_id'        => 'nullable|string|max:4',
            'description'    => 'nullable|string',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maks 2MB
        ]);

        try {
            // 2. Cek apakah data ada
            $item = DB::table('mst_inventory')->where('id', $id)->first();
            if (!$item) {
                return redirect()->route('masterdata.infrastructures.index')
                    ->with('error', 'Data tidak ditemukan!');
            }

            // 3. Siapkan array data yang mau di-update
            $updateData = [
                'item_name'      => $request->item_name,
                'inventory_type' => $request->inventory_type,
                'brand'          => $request->brand,
                'unit_measure'   => $request->unit_measure,
                'stock'          => $request->stock,
                'price'          => $request->price ?? 0,
                'status'         => $request->status,
                'unit_id'        => $request->unit_id,
                'description'    => $request->description,
            ];

            // 4. Proses Upload Foto Baru (Jika Ada)
            if ($request->hasFile('photo')) {
                // Hapus foto lama dari storage agar tidak memenuhi memori server
                if ($item->photo && Storage::disk('public')->exists($item->photo)) {
                    Storage::disk('public')->delete($item->photo);
                }
                
                // Simpan foto baru dan masukkan path-nya ke array update
                $updateData['photo'] = $request->file('photo')->store('infrastructures', 'public');
            }

            // 5. Eksekusi Update
            DB::table('mst_inventory')->where('id', $id)->update($updateData);

            return redirect()->route('masterdata.infrastructures.index')
                ->with('success', 'Data "' . $request->item_name . '" berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data dari database (Delete)
     */
    public function destroy($id)
    {
        try {
            $item = DB::table('mst_inventory')->where('id', $id)->first();
            
            if ($item) {
                // Hapus file foto fisiknya dari folder storage sebelum menghapus data dari database
                if ($item->photo && Storage::disk('public')->exists($item->photo)) {
                    Storage::disk('public')->delete($item->photo);
                }
                
                // Hapus data dari tabel
                DB::table('mst_inventory')->where('id', $id)->delete();
                
                return redirect()->route('masterdata.infrastructures.index')
                    ->with('success', 'Data infrastruktur berhasil dihapus!');
            }

            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Data tidak ditemukan!');
                
        } catch (\Exception $e) {
            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}