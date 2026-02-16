<x-masterdata::layouts.master>
  <div class="flex items-center justify-between">
    <h3 class="text-xl font-semibold">Daftar Role</h3>
  </div>

  <div class="mt-4 overflow-hidden rounded border">
    <table class="w-full table-auto">
      <thead class="bg-gray-50 text-left text-sm">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Nama Role</th>
          <th class="px-4 py-2">Deskripsi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($roles as $role)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $role->id }}</td>
            <td class="px-4 py-2">{{ $role->role_name }}</td>
            <td class="px-4 py-2">{{ $role->description }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</x-masterdata::layouts.master>
