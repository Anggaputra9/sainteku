
<x-masterdata::layouts.master>
  <h3 class="text-lg font-semibold">Tambah Unit Baru</h3>

  <form action="{{ route('masterdata.units.store') }}" method="POST" class="mt-4 max-w-lg">
    @csrf
    <div class="mb-3">
      <label class="block text-sm">ID (4 karakter)</label>
      <input name="id" class="mt-1 w-full rounded border px-3 py-2" maxlength="4" required />
    </div>

    <div class="mb-3">
      <label class="block text-sm">Nama Unit</label>
      <input name="unit_name" class="mt-1 w-full rounded border px-3 py-2" required />
    </div>

    <div class="mb-3">
      <label class="block text-sm">Unit Parent</label>
      <input name="unit_parent" class="mt-1 w-full rounded border px-3 py-2" />
    </div>

    <div class="mb-3">
      <label class="block text-sm">Tipe Unit (ID)</label>
      <input name="unit_type_id" type="number" class="mt-1 w-full rounded border px-3 py-2" />
    </div>

    <div class="mb-3">
      <label class="block text-sm">Aktif</label>
      <select name="is_active" class="mt-1 w-full rounded border px-3 py-2">
        <option value="1">Ya</option>
        <option value="0">Tidak</option>
      </select>
    </div>

    <div class="mt-4 flex gap-2">
      <button class="btn btn-primary" type="submit">Simpan</button>
      <a href="{{ route('masterdata.units.index') }}" class="btn">Batal</a>
    </div>
  </form>
</x-masterdata::layouts.master>
