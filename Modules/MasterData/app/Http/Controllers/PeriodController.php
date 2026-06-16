<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodController extends Controller
{
    private const SEMESTERS = ['Gasal', 'Genap'];

    public function index()
    {
        return view('masterdata::periods.index')
            ->with('title', 'Tahun Akademik');
    }

    public function getPeriodsData(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $proposalCounts = DB::table('trx_exam_proposals')
            ->select('period_id', DB::raw('count(*) as cnt'))
            ->groupBy('period_id')
            ->pluck('cnt', 'period_id');

        $periods = $this->buildPeriodsQuery($request)
            ->paginate($perPage)
            ->through(fn (Period $period): array => $this->formatPeriodForApi($period, $proposalCounts));

        return response()->json($periods)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function buildPeriodsQuery(Request $request)
    {
        $query = Period::query();

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('semester', 'like', '%' . $search . '%');
            });
        }

        $status = $request->query('status');
        if (in_array($status, ['1', '0'], true)) {
            $query->where('is_active', $status);
        }

        $semester = trim((string) $request->query('semester', ''));
        if ($semester !== '' && in_array($semester, self::SEMESTERS, true)) {
            $query->where('semester', $semester);
        }

        $sort = (string) $request->query('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('id'),
            'name_asc' => $query->orderBy('name')->orderBy('semester'),
            'name_desc' => $query->orderByDesc('name')->orderByDesc('semester'),
            'semester_asc' => $query->orderBy('semester')->orderByDesc('name'),
            default => $query->orderByDesc('id'),
        };

        return $query;
    }

    private function formatPeriodForApi(Period $period, $proposalCounts): array
    {
        $proposalCount = (int) ($proposalCounts[$period->id] ?? 0);
        $label = trim($period->name . ' ' . $period->semester);

        return [
            'id' => $period->id,
            'name' => $period->name,
            'semester' => $period->semester,
            'label' => $label,
            'is_active' => $period->is_active,
            'initial' => mb_strtoupper(mb_substr($period->name, 0, 1)),
            'proposal_count' => $proposalCount,
            'created_at' => $period->created_at,
            'update_url' => route('masterdata.periods.update', $period->id),
            'delete_url' => route('masterdata.periods.destroy', $period->id),
            'can_delete' => $proposalCount === 0,
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:15', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['required', 'string', 'in:' . implode(',', self::SEMESTERS)],
        ], [
            'name.regex' => 'Format tahun akademik harus seperti 2025/2026.',
            'semester.in' => 'Semester harus Gasal atau Genap.',
        ]);

        if ($this->periodExists($validated['name'], $validated['semester'])) {
            return redirect()->route('masterdata.periods.index')
                ->with('error', 'Periode ' . $validated['name'] . ' ' . $validated['semester'] . ' sudah terdaftar.');
        }

        $isActive = $request->has('is_active') ? '1' : '0';
        $nextId = ((int) Period::max('id')) + 1;

        DB::transaction(function () use ($validated, $isActive, $nextId) {
            if ($isActive === '1') {
                Period::query()->update(['is_active' => '0']);
            }

            $period = new Period();
            $period->id = $nextId;
            $period->name = $validated['name'];
            $period->semester = $validated['semester'];
            $period->is_active = $isActive;
            $period->created_at = now();
            $period->save();
        });

        return redirect()->route('masterdata.periods.index')
            ->with('success', 'Tahun akademik ' . $validated['name'] . ' ' . $validated['semester'] . ' berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $period = Period::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:15', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['required', 'string', 'in:' . implode(',', self::SEMESTERS)],
        ], [
            'name.regex' => 'Format tahun akademik harus seperti 2025/2026.',
            'semester.in' => 'Semester harus Gasal atau Genap.',
        ]);

        if ($this->periodExists($validated['name'], $validated['semester'], (int) $period->id)) {
            return redirect()->route('masterdata.periods.index')
                ->with('error', 'Periode ' . $validated['name'] . ' ' . $validated['semester'] . ' sudah terdaftar.');
        }

        $isActive = $request->has('is_active') ? '1' : '0';

        DB::transaction(function () use ($period, $validated, $isActive) {
            if ($isActive === '1') {
                Period::query()->where('id', '!=', $period->id)->update(['is_active' => '0']);
            }

            $period->update([
                'name' => $validated['name'],
                'semester' => $validated['semester'],
                'is_active' => $isActive,
            ]);
        });

        return redirect()->route('masterdata.periods.index')
            ->with('success', 'Tahun akademik ' . $validated['name'] . ' ' . $validated['semester'] . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $period = Period::findOrFail($id);
        $proposalCount = DB::table('trx_exam_proposals')->where('period_id', $id)->count();

        if ($proposalCount > 0) {
            return redirect()->route('masterdata.periods.index')
                ->with('error', 'Periode tidak dapat dihapus karena masih digunakan oleh ' . $proposalCount . ' pengajuan ujian.');
        }

        $label = trim($period->name . ' ' . $period->semester);
        $period->delete();

        return redirect()->route('masterdata.periods.index')
            ->with('success', 'Tahun akademik ' . $label . ' berhasil dihapus!');
    }

    private function periodExists(string $name, string $semester, ?int $exceptId = null): bool
    {
        $query = Period::query()
            ->where('name', $name)
            ->where('semester', $semester);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }
}