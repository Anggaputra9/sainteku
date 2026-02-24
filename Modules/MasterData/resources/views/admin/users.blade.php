<x-masterdata::layouts.master>
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold">Admin — Manajemen Pengguna</h3>
        <a href="{{ route('masterdata.admin.users.create') }}"
            class="inline-flex items-center gap-2 rounded !bg-green-600 px-4 py-2 font-medium !text-white hover:!bg-green-700 transition">
            <i class="fas fa-plus"></i>
            Tambah User
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div
        class="overflow-hidden rounded-lg border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50 text-left text-sm dark:bg-gray-800">
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Nama</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">Role Saat Ini</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr
                            class="border-b border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700">
                            <td class="px-4 py-3 text-sm">{{ $user->id }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if (isset($userRoles[$user->id]) && count($userRoles[$user->id]) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($userRoles[$user->id] as $roleId)
                                            @php $role = $roles->where('id', $roleId)->first(); @endphp
                                            @if ($role)
                                                <span
                                                    class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-800">
                                                    {{ $role->role_name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if ($user->is_active)
                                    <span
                                        class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800"><i
                                            class="fas fa-check mr-1"></i>Aktif</span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800"><i
                                            class="fas fa-times mr-1"></i>Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('masterdata.admin.users.edit', $user->id) }}"
                                        class="inline-flex items-center gap-1 rounded bg-amber-500 px-3 py-1 text-white hover:bg-amber-600 transition text-xs font-medium">
                                        <i class="fas fa-pencil"></i>
                                        Ubah
                                    </a>
                                    <form method="POST"
                                        action="{{ route('masterdata.admin.users.destroy', $user->id) }}" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded bg-red-500 px-3 py-1 text-white hover:bg-red-600 transition text-xs font-medium">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox mr-2"></i>Tidak ada data user
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-masterdata::layouts.master>
