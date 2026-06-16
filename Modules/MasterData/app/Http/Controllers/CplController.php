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
            ->get()
            ->map(fn (MstCpl $cpl): array => $this->formatCplForApi($cpl));

        return response()->json($cpls)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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

    private function formatCplForApi(MstCpl $cpl): array
    {
        return [
            'id' => $cpl->id,
            'unit_id' => $cpl->unit_id,
            'name' => $cpl->name,
            'is_active' => $cpl->is_active,
            'update_url' => route('masterdata.units.cpl.update', [$cpl->unit_id, $cpl->id]),
            'delete_url' => route('masterdata.units.cpl.delete', [$cpl->unit_id, $cpl->id]),
            'can_delete' => ! $this->isCplUsedInMapping($cpl->unit_id, $cpl->id),
        ];
    }
}