@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Role</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola hak akses dan tingkatan pengguna dalam sistem.</p>
        </div>

            <button @click="$dispatch('open-create-modal')"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800">
                <i class="fas fa-plus"></i>
                Tambah Role
            </button>
    </div>

    @if (session('success'))
        <div
            class="mb-4 rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-3 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-400">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50/50 text-left text-sm dark:bg-gray-700/30">
                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">ID</th>
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Nama Role
                        </th>
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Deskripsi
                        </th>
                        <th
                            class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($roles as $role)
                        <tr class="bg-transparent">
                            <td class="px-4 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                                #{{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-bold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    {{ strtoupper($role->role_name) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $role->description ?? 'Tidak ada deskripsi' }}
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Ubah (Amber / Kuning) --}}
                                    <button type="button"
                                        @click="$dispatch('open-edit-modal', { 
                                            url: '{{ route('masterdata.roles.update', $role->id) }}',
                                            code: '{{ $role->role_code }}',
                                            name: '{{ $role->role_name }}',
                                            active: {{ $role->is_active == '1' ? 'true' : 'false' }}
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                                        title="Edit Role">
                                        <i class="fa-solid fa-pencil"></i> Ubah
                                    </button>

                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', { 
                                            url: '{{ route('masterdata.roles.destroy', $role->id) }}',
                                            name: '{{ $role->role_name }}'
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                                        title="Hapus Role">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-shield-alt text-3xl mb-2 opacity-20"></i>
                                <p>Belum ada data role yang dikonfigurasi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    @include('masterdata::roles.modal-create')
    @include('masterdata::roles.modal-edit')
    @include('masterdata::roles.delete-modal')
@endsection
