<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\Entities\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('id')->paginate(15);
        return view('masterdata::units.index', compact('units'));
    }

    public function create()
    {
        return view('masterdata::units.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|max:4',
            'unit_name' => 'required|string|max:100',
            'unit_parent' => 'nullable|string|max:4',
            'unit_type_id' => 'nullable|integer',
            'is_active' => 'required|in:0,1',
        ]);

        $data['created_at'] = now();

        Unit::create($data);

        return redirect()->route('masterdata.units.index')->with('success', 'Unit berhasil dibuat');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('masterdata::units.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $data = $request->validate([
            'unit_name' => 'required|string|max:100',
            'unit_parent' => 'nullable|string|max:4',
            'unit_type_id' => 'nullable|integer',
            'is_active' => 'required|in:0,1',
        ]);

        $unit->update($data);

        return redirect()->route('masterdata.units.index')->with('success', 'Unit berhasil diperbarui');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('masterdata.units.index')->with('success', 'Unit berhasil dihapus');
    }
}
