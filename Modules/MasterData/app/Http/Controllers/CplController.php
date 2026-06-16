<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MstCpl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\MasterData\Support\CplCodeGenerator;

class CplController extends Controller
{
    public function getCplData(Request $request, string $unitId): JsonResponse
    {
        $this->ensureProdiExists($unitId);

        $cpls = MstCpl::query()
            ->where('unit_id', $unitId)
            ->orderBy('id')
            ->get();

        $cplIds = $cpls->pluck('id')->all();
        $usedInMapping = array_flip($this->cplIdsUsedInMapping($unitId, $cplIds));

        $data = $cpls->map(function (MstCpl $cpl) use ($usedInMapping): array {
            $canDelete = ! isset($usedInMapping[$cpl->id]);

            return $this->formatCplForApi($cpl, $canDelete);
        });

        return response()->json($data);
    }

    public function store(Request $request, string $unitId): JsonResponse
    {
        $this->ensureProdiExists($unitId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|in:0,1',
        ]);

        $id = app(CplCodeGenerator::class)->generateNext($unitId);

        MstCpl::create([
            'unit_id' => $unitId,
            'id' => $id,
            'name' => $validated['name'],
            'is_active' => $validated['is_active'],
            'created_at' => now(),
        ]);

        $cpl = $this->findCplOrFail($unitId, $id);

        return response()->json([
            'message' => 'CPL berhasil ditambahkan.',
            'cpl' => $this->formatCplForApi($cpl),
        ], 201);
    }

    public function update(Request $request, string $unitId, string $cplId): JsonResponse
    {
        $this->findCplOrFail($unitId, $cplId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|in:0,1',
        ]);

        MstCpl::query()
            ->where('unit_id', $unitId)
            ->where('id', $cplId)
            ->update([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
            ]);

        return response()->json([
            'message' => 'CPL berhasil diperbarui.',
            'cpl' => $this->formatCplForApi($this->findCplOrFail($unitId, $cplId)),
        ]);
    }

    public function destroy(string $unitId, string $cplId): JsonResponse
    {
        $this->findCplOrFail($unitId, $cplId);

        if ($this->isCplUsedInMapping($unitId, $cplId)) {
            return response()->json([
                'message' => 'CPL tidak dapat dihapus karena masih dipetakan ke CPMK mata kuliah.',
            ], 422);
        }

        MstCpl::query()
            ->where('unit_id', $unitId)
            ->where('id', $cplId)
            ->delete();

        return response()->json([
            'message' => 'CPL berhasil dihapus.',
        ]);
    }

    public function legacyPostDestroy(Request $request, string $unitId, string $cplId): JsonResponse
    {
        $request->merge(['ids' => [$cplId]]);

        return $this->bulkDestroy($request, $unitId);
    }

    public function bulkDestroy(Request $request, string $unitId): JsonResponse
    {
        $this->ensureProdiExists($unitId);

        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|string|max:5',
        ]);

        $deleted = [];
        $skipped = [];

        foreach ($validated['ids'] as $cplId) {
            $exists = MstCpl::query()
                ->where('unit_id', $unitId)
                ->where('id', $cplId)
                ->exists();

            if (! $exists) {
                $skipped[] = ['id' => $cplId, 'reason' => 'CPL tidak ditemukan.'];
                continue;
            }

            if ($this->isCplUsedInMapping($unitId, $cplId)) {
                $skipped[] = ['id' => $cplId, 'reason' => 'Masih dipetakan ke CPMK mata kuliah.'];
                continue;
            }

            MstCpl::query()
                ->where('unit_id', $unitId)
                ->where('id', $cplId)
                ->delete();

            $deleted[] = $cplId;
        }

        if (count($deleted) === 0) {
            return response()->json([
                'message' => 'Tidak ada CPL yang dapat dihapus.',
                'deleted_count' => 0,
                'skipped' => $skipped,
            ], 422);
        }

        $message = count($deleted) === 1
            ? 'CPL berhasil dihapus.'
            : count($deleted).' CPL berhasil dihapus.';

        if (count($skipped) > 0) {
            $message .= ' '.count($skipped).' CPL dilewati.';
        }

        return response()->json([
            'message' => $message,
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
            'skipped' => $skipped,
        ]);
    }

    private function ensureProdiExists(string $unitId): void
    {
        $unit = DB::table('mst_unit')->where('id', $unitId)->first();

        abort_unless($unit, 404, 'Unit tidak ditemukan.');
        abort_unless((int) $unit->unit_type_id === 3, 422, 'CPL hanya dapat dikelola pada Program Studi.');
    }

    private function findCplOrFail(string $unitId, string $cplId): MstCpl
    {
        $this->ensureProdiExists($unitId);

        return MstCpl::query()
            ->where('unit_id', $unitId)
            ->where('id', $cplId)
            ->firstOrFail();
    }

    private function isCplUsedInMapping(string $unitId, string $cplId): bool
    {
        return DB::table('trx_cpl_cpmk_mapping')
            ->where('unit_id', $unitId)
            ->where('cpl_id', $cplId)
            ->exists();
    }

    private function cplIdsUsedInMapping(string $unitId, array $cplIds): array
    {
        if ($cplIds === []) {
            return [];
        }

        return DB::table('trx_cpl_cpmk_mapping')
            ->where('unit_id', $unitId)
            ->whereIn('cpl_id', $cplIds)
            ->distinct()
            ->pluck('cpl_id')
            ->all();
    }

    private function formatCplForApi(MstCpl $cpl, ?bool $canDelete = null): array
    {
        if ($canDelete === null) {
            $canDelete = ! $this->isCplUsedInMapping($cpl->unit_id, $cpl->id);
        }

        return [
            'id' => $cpl->id,
            'unit_id' => $cpl->unit_id,
            'name' => $cpl->name,
            'is_active' => $cpl->is_active,
            'update_url' => route('masterdata.units.cpl.update', [$cpl->unit_id, $cpl->id], false),
            'delete_url' => route('masterdata.units.cpl.delete', [$cpl->unit_id, $cpl->id], false),
            'bulk_destroy_url' => route('masterdata.units.cpl.bulk.destroy', $cpl->unit_id, false),
            'can_delete' => $canDelete,
        ];
    }
}