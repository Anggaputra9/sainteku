<x-masterdata::layouts.master>
  <div class="flex items-center justify-between">
    <h3 class="text-xl font-semibold">Daftar Unit</h3>
    <a href="{{ route('masterdata.units.create') }}" class="btn btn-primary">Tambah Unit</a>
  </div>

  @if(session('success'))
    <div class="mt-4 rounded border border-green-300 bg-green-50 px-4 py-2 text-green-800">{{ session('success') }}</div>
  @endif

  <div class="mt-8 rounded-lg overflow-hidden rounded border">
    <table class="w-full table-auto">
      <thead class="bg-gray-50 text-left text-sm">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Nama Unit</th>
          <th class="px-4 py-2">Tipe</th>
          <th class="px-4 py-2">Aktif</th>
          <th class="px-4 py-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($units as $unit)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $unit->id }}</td>
            <td class="px-4 py-2">{{ $unit->unit_name }}</td>
            <td class="px-4 py-2">{{ $unit->unit_type_id }}</td>
            <td class="px-4 py-2">{{ $unit->is_active }}</td>
            <td class="px-4 py-2">
              <a href="{{ route('masterdata.units.edit', $unit->id) }}" class="text-blue-600">Edit</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $units->links() }}</div>
</x-masterdata::layouts.master>
