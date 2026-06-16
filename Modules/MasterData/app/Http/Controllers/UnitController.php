<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\Entities\Unit;
use Illuminate\Support\Facades\DB;
use Modules\MasterData\Support\UnitCodeGenerator;

class UnitController extends Controller
{
    public function index()
    {
        $parentUnits = Unit::where('is_active', '1')->orderBy('unit_name')->get();
        $unitTypes = DB::table('ref_unit_type')->orderBy('id')->get();

        return view('masterdata::units.index', compact('parentUnits', 'unitTypes'))
            ->with('title', 'Daftar Unit');
    }

    public function getUnitsData(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $unitTypes = DB::table('ref_unit_type')->pluck('description', 'id');
        $parentNames = Unit::pluck('unit_name', 'id');

        $childCounts = Unit::query()
            ->select('unit_parent', DB::raw('count(*) as cnt'))
            ->whereNotNull('unit_parent')
            ->where('unit_parent', '!=', '')
            ->groupBy('unit_parent')
            ->pluck('cnt', 'unit_parent');

        $userCounts = DB::table('mst_user')
            ->select('unit_id', DB::raw('count(*) as cnt'))
            ->groupBy('unit_id')
            ->pluck('cnt', 'unit_id');

        $pivotUserCounts = DB::table('mst_user_unit')
            ->select('unit_id', DB::raw('count(*) as cnt'))
            ->groupBy('unit_id')
            ->pluck('cnt', 'unit_id');

        $cplCounts = DB::table('mst_cpl')
            ->select('unit_id', DB::raw('count(*) as cnt'))
            ->groupBy('unit_id')
            ->pluck('cnt', 'unit_id');

        $units = $this->buildUnitsQuery($request)
            ->paginate($perPage)
            ->through(fn (Unit $unit): array => $this->formatUnitForApi(
                $unit,
                $unitTypes,
                $parentNames,
                $childCounts,
                $userCounts,
                $pivotUserCounts,
                $cplCounts,
            ));

        return response()->json($units)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function buildUnitsQuery(Request $request)
    {
        $query = Unit::query();

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('unit_name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('unit_parent', 'like', '%' . $search . '%');
            });
        }

        $status = $request->query('status');
        if (in_array($status, ['1', '0'], true)) {
            $query->where('is_active', $status);
        }

        $typeId = $request->query('type');
        if ($typeId !== null && $typeId !== '' && is_numeric($typeId)) {
            $query->where('unit_type_id', (int) $typeId);
        }

        $sort = (string) $request->query('sort', 'name_asc');
        match ($sort) {
            'newest' => $query->orderByDesc('created_at')->orderByDesc('id'),
            'oldest' => $query->orderBy('created_at')->orderBy('id'),
            'name_desc' => $query->orderByDesc('unit_name'),
            'code_asc' => $query->orderBy('id'),
            'code_desc' => $query->orderByDesc('id'),
            default => $query->orderBy('unit_name'),
        };

        return $query;
    }

    private function formatUnitForApi(
        Unit $unit,
        $unitTypes,
        $parentNames,
        $childCounts,
        $userCounts,
        $pivotUserCounts,
        $cplCounts,
    ): array {
        $childCount = (int) ($childCounts[$unit->id] ?? 0);
        $userCount = (int) ($userCounts[$unit->id] ?? 0) + (int) ($pivotUserCounts[$unit->id] ?? 0);
        $cplCount = (int) ($cplCounts[$unit->id] ?? 0);
        $isProdi = (int) $unit->unit_type_id === 3;

        return [
            'id' => $unit->id,
            'unit_name' => $unit->unit_name,
            'unit_parent' => $unit->unit_parent,
            'parent_name' => $unit->unit_parent
                ? ($parentNames[$unit->unit_parent] ?? $unit->unit_parent)
                : null,
            'unit_type_id' => $unit->unit_type_id,
            'type_name' => $unitTypes[$unit->unit_type_id] ?? ('Tipe ' . $unit->unit_type_id),
            'is_active' => $unit->is_active,
            'initial' => mb_strtoupper(mb_substr($unit->id, 0, 1)),
            'child_count' => $childCount,
            'user_count' => $userCount,
            'cpl_count' => $cplCount,
            'cpl_api_url' => $isProdi ? route('masterdata.units.cpl.api.data', $unit->id) : null,
            'cpl_store_url' => $isProdi ? route('masterdata.units.cpl.store', $unit->id) : null,
            'cpl_bulk_destroy_url' => $isProdi ? route('masterdata.units.cpl.bulk.destroy', $unit->id) : null,
            'update_url' => route('masterdata.units.update', $unit->id),
            'delete_url' => route('masterdata.units.destroy', $unit->id),
            'can_delete' => $childCount === 0 && $userCount === 0,
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|string|max:100',
            'unit_parent' => 'nullable|string|max:4',
            'unit_type_id' => 'nullable|integer',
        ]);

        $unitTypeId = (int) ($request->unit_type_id ?? 0);

        if ($unitTypeId < 1) {
            return redirect()->route('masterdata.units.index')
                ->with('error', 'Tipe unit wajib dipilih.');
        }

        $newId = app(UnitCodeGenerator::class)->generateUnique(
            $request->unit_name,
            $unitTypeId
        );

        Unit::create([
            'id' => $newId,
            'unit_name' => $request->unit_name,
            'unit_parent' => $request->unit_parent,
            'unit_type_id' => $request->unit_type_id,
            'is_active' => $request->has('is_active') ? '1' : '0',
            'created_at' => now(),
        ]);

        return redirect()->route('masterdata.units.index')
            ->with('success', 'Unit ' . $request->unit_name . ' berhasil ditambahkan!');
    }

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
            'is_active' => $request->has('is_active') ? '1' : '0',
        ]);

        return redirect()->route('masterdata.units.index')
            ->with('success', 'Data unit ' . $unit->unit_name . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);

        $childCount = Unit::where('unit_parent', $id)->count();
        $userCount = DB::table('mst_user')->where('unit_id', $id)->count()
            + DB::table('mst_user_unit')->where('unit_id', $id)->count();

        if ($childCount > 0 || $userCount > 0) {
            return redirect()->route('masterdata.units.index')
                ->with('error', 'Unit tidak dapat dihapus karena masih memiliki unit turunan atau pengguna terkait.');
        }

        try {
            $namaUnit = $unit->unit_name;
            $unit->delete();

            return redirect()->route('masterdata.units.index')
                ->with('success', 'Unit ' . $namaUnit . ' berhasil dihapus secara permanen!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('masterdata.units.index')
                    ->with('error', 'Gagal menghapus! Unit ini masih digunakan oleh entitas lain.');
            }

            throw $e;
        }
    }
}