<?php

namespace Modules\MasterData\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InfrastructureController extends Controller
{
    public function index(Request $request)
    {
        $inventoryTypes = DB::table('mst_inventory_type')->orderBy('description')->get();
        $units = DB::table('mst_unit')->where('is_active', '1')->orderBy('unit_name')->get();

        return view('masterdata::infrastructures.index', compact('inventoryTypes', 'units'))
            ->with('title', 'Daftar Infrastruktur & Inventaris');
    }

    public function getInfrastructuresData(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $loanCounts = DB::table('trx_inventory_loans')
            ->select('inventory_id', DB::raw('count(*) as cnt'))
            ->groupBy('inventory_id')
            ->pluck('cnt', 'inventory_id');

        $items = $this->buildInfrastructuresQuery($request)
            ->paginate($perPage)
            ->through(fn (object $item): array => $this->formatInfrastructureForApi($item, $loanCounts));

        return response()->json($items)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function buildInfrastructuresQuery(Request $request)
    {
        $query = DB::table('mst_inventory')
            ->leftJoin('mst_inventory_type', 'mst_inventory.inventory_type', '=', 'mst_inventory_type.id')
            ->leftJoin('mst_unit', 'mst_inventory.unit_id', '=', 'mst_unit.id')
            ->select(
                'mst_inventory.*',
                'mst_inventory_type.description as type_description',
                'mst_unit.unit_name',
            );

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('mst_inventory.item_name', 'like', '%' . $search . '%')
                    ->orWhere('mst_inventory.id', 'like', '%' . $search . '%')
                    ->orWhere('mst_inventory.brand', 'like', '%' . $search . '%')
                    ->orWhere('mst_inventory.description', 'like', '%' . $search . '%')
                    ->orWhere('mst_unit.unit_name', 'like', '%' . $search . '%');
            });
        }

        $type = $request->query('inventory_type');
        if ($type !== null && $type !== '') {
            $query->where('mst_inventory.inventory_type', $type);
        }

        $status = $request->query('status');
        if (in_array($status, ['0', '1'], true)) {
            $query->where('mst_inventory.status', $status);
        }

        $sort = (string) $request->query('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('mst_inventory.created_at')->orderBy('mst_inventory.id'),
            'name_asc' => $query->orderBy('mst_inventory.item_name'),
            'name_desc' => $query->orderByDesc('mst_inventory.item_name'),
            'code_asc' => $query->orderBy('mst_inventory.id'),
            'code_desc' => $query->orderByDesc('mst_inventory.id'),
            'stock_asc' => $query->orderBy('mst_inventory.stock'),
            'stock_desc' => $query->orderByDesc('mst_inventory.stock'),
            default => $query->orderByDesc('mst_inventory.created_at')->orderByDesc('mst_inventory.id'),
        };

        return $query;
    }

    private function formatInfrastructureForApi(object $item, $loanCounts): array
    {
        $loanCount = (int) ($loanCounts[$item->id] ?? 0);

        return [
            'id' => $item->id,
            'item_name' => $item->item_name,
            'inventory_type' => $item->inventory_type,
            'type_description' => $item->type_description ?? ('Tipe ' . $item->inventory_type),
            'brand' => $item->brand,
            'unit_measure' => $item->unit_measure,
            'stock' => (int) $item->stock,
            'price' => (float) ($item->price ?? 0),
            'price_formatted' => number_format((float) ($item->price ?? 0), 0, ',', '.'),
            'status' => $item->status,
            'unit_id' => $item->unit_id,
            'unit_name' => $item->unit_name,
            'description' => $item->description,
            'photo' => $item->photo,
            'photo_url' => $item->photo ? asset('storage/' . $item->photo) : null,
            'initial' => mb_strtoupper(mb_substr($item->item_name, 0, 1)),
            'loan_count' => $loanCount,
            'update_url' => route('masterdata.infrastructures.update', $item->id),
            'delete_url' => route('masterdata.infrastructures.destroy', $item->id),
            'can_delete' => $loanCount === 0,
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'inventory_type' => 'required',
            'stock' => 'required|integer|min:0',
            'brand' => 'nullable|string|max:100',
            'unit_measure' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
            'unit_id' => 'nullable|string|max:4',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $lastItem = DB::table('mst_inventory')->orderBy('id', 'desc')->first();
            $newId = ! $lastItem ? 'I0001' : 'I' . str_pad((int) substr($lastItem->id, 1) + 1, 4, '0', STR_PAD_LEFT);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('infrastructures', 'public');
            }

            DB::table('mst_inventory')->insert([
                'id' => $newId,
                'item_name' => $request->item_name,
                'inventory_type' => $request->inventory_type,
                'brand' => $request->brand,
                'unit_measure' => $request->unit_measure,
                'stock' => $request->stock,
                'price' => $request->price ?? 0,
                'status' => $request->status,
                'unit_id' => $request->unit_id,
                'description' => $request->description,
                'photo' => $photoPath,
                'created_at' => now(),
            ]);

            return redirect()->route('masterdata.infrastructures.index')
                ->with('success', 'Data "' . $request->item_name . '" berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'inventory_type' => 'required',
            'stock' => 'required|integer|min:0',
            'brand' => 'nullable|string|max:100',
            'unit_measure' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
            'unit_id' => 'nullable|string|max:4',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $item = DB::table('mst_inventory')->where('id', $id)->first();
            if (! $item) {
                return redirect()->route('masterdata.infrastructures.index')
                    ->with('error', 'Data tidak ditemukan!');
            }

            $updateData = [
                'item_name' => $request->item_name,
                'inventory_type' => $request->inventory_type,
                'brand' => $request->brand,
                'unit_measure' => $request->unit_measure,
                'stock' => $request->stock,
                'price' => $request->price ?? 0,
                'status' => $request->status,
                'unit_id' => $request->unit_id,
                'description' => $request->description,
            ];

            if ($request->hasFile('photo')) {
                if ($item->photo && Storage::disk('public')->exists($item->photo)) {
                    Storage::disk('public')->delete($item->photo);
                }
                $updateData['photo'] = $request->file('photo')->store('infrastructures', 'public');
            }

            DB::table('mst_inventory')->where('id', $id)->update($updateData);

            return redirect()->route('masterdata.infrastructures.index')
                ->with('success', 'Data "' . $request->item_name . '" berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $loanCount = DB::table('trx_inventory_loans')->where('inventory_id', $id)->count();

        if ($loanCount > 0) {
            return redirect()->route('masterdata.infrastructures.index')
                ->with('error', 'Infrastruktur tidak dapat dihapus karena masih memiliki riwayat peminjaman.');
        }

        try {
            $item = DB::table('mst_inventory')->where('id', $id)->first();

            if ($item) {
                if ($item->photo && Storage::disk('public')->exists($item->photo)) {
                    Storage::disk('public')->delete($item->photo);
                }

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