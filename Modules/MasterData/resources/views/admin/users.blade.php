<x-masterdata::layouts.master>
  <div class="flex items-center justify-between">
    <h3 class="text-xl font-semibold">Admin — Manajemen Role Pengguna</h3>
  </div>

  @if(session('success'))
    <div class="mt-4 rounded border border-green-300 bg-green-50 px-4 py-2 text-green-800">{{ session('success') }}</div>
  @endif

  <div class="mt-4 overflow-hidden rounded border">
    <table class="w-full table-auto">
      <thead class="bg-gray-50 text-left text-sm">
        <tr>
          <th class="px-4 py-2">Nama</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Role Saat Ini</th>
          <th class="px-4 py-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $user->name }}</td>
            <td class="px-4 py-2">{{ $user->email }}</td>
            <td class="px-4 py-2">
              {{ isset($mappings[$user->id]) ? ($roles->where('id', $mappings[$user->id])->first()->role_name ?? '-') : '-' }}
            </td>
            <td class="px-4 py-2">
              <form method="POST" action="{{ route('masterdata.admin.users.assign', $user->id) }}">
                @csrf
                <div class="flex items-center gap-2">
                  <select name="role_id" class="rounded border px-2 py-1">
                    @foreach($roles as $role)
                      <option value="{{ $role->id }}" {{ (isset($mappings[$user->id]) && $mappings[$user->id] == $role->id) ? 'selected' : '' }}>{{ $role->role_name }}</option>
                    @endforeach
                  </select>
                  <button class="btn btn-sm btn-primary">Assign</button>
                </div>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</x-masterdata::layouts.master>
