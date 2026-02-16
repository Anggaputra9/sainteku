<x-masterdata::layouts.master>
  <h3 class="text-xl font-semibold">Edit Unit</h3>

  <form action="{{ route('masterdata.units.update', $unit->id) }}" method="POST" class="mt-4 max-w-lg">
    @csrf
    @method('PUT')
    <div class="mb-4">
      <label class="block mb-1 text-sm">ID</label>
      <input name="id" class="w-full rounded border px-3 py-2" value="{{ $unit->id }}" readonly />
    </div>

    <div class="mb-4">
      <label class="block mb-1 text-sm">Nama Unit</label>
      <input name="unit_name" class="w-full rounded border px-3 py-2" value="{{ old('unit_name', $unit->unit_name) }}" />
    </div>

    <div class="mb-4">
      <label class="block mb-1 text-sm">Tipe</label>
      <input name="unit_type_id" class="w-full rounded border px-3 py-2" value="{{ old('unit_type_id', $unit->unit_type_id) }}" />
    </div>

    <div class="mb-4">
      <label class="block mb-1 text-sm">Aktif</label>
      <select name="is_active" class="w-full rounded border px-3 py-2">
        <option value="1" {{ $unit->is_active == '1' ? 'selected' : '' }}>Ya</option>
        <option value="0" {{ $unit->is_active == '0' ? 'selected' : '' }}>Tidak</option>
      </select>
    </div>

    <div>
      <button class="btn btn-primary">Perbarui</button>
      <a href="{{ route('masterdata.units.index') }}" class="ml-2">Batal</a>
    </div>
  </form>
</x-masterdata::layouts.master>
