@extends('layouts.app')

@section('content')
    <div class="mx-auto">
        <div class="space-y-6">

            {{-- Header & Breadcrumb --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Role</h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Master Data /</li>
                            <li class="text-blue-600 dark:text-blue-400">Role</li>
                        </ol>
                    </nav>
                </div>

                {{-- Tombol Tambah (Green, fa-plus) --}}
                <div x-data="{ openCreate: false }">
                    <button @click="$dispatch('open-create-modal')"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fas fa-plus"></i>
                        Tambah Role
                    </button>

                    @include('masterdata::roles.modal-create')
                </div>
            </div>

            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                    <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-sm font-bold text-green-700 dark:text-green-400">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3"></i>
                    <p class="text-sm font-bold text-red-700 dark:text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Filter & Search --}}
            <div class="rounded-lg">
                <form method="GET" class="flex flex-wrap items-center justify-between gap-3">

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        {{-- Input Cari --}}
                        <div class="relative w-full sm:max-w-xs">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 dark:text-gray-500">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama role..."
                                class="w-full rounded-md border border-gray-300 bg-gray-50 py-1.5 pl-9 pr-3 text-sm text-gray-900 focus:border-teal-500 focus:ring-teal-500 outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition">
                        </div>
                        {{-- Dropdown Jumlah Data --}}
                        <select name="per_page" onchange="this.form.submit()"
                            class="rounded-md border border-gray-300 bg-gray-50 py-1.5 pl-3 pr-8 text-sm text-gray-900 focus:border-teal-500 focus:ring-teal-500 outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white transition cursor-pointer shadow-sm">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        {{-- Tombol Filter --}}
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition shadow-sm">
                            <i class="fa-solid fa-filter text-xs"></i> Filter
                        </button>

                        {{-- Tombol Muat Ulang --}}
                        <a href="{{ route('masterdata.roles.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition shadow-sm">
                            <i class="fa-solid fa-rotate text-xs"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Card --}}
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
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
                                    @php
                                        // Siapkan array izin khusus untuk role ini agar bisa dibaca oleh Alpine.js
                                        // Formatnya: "modul_id-permission_id" (Contoh: "1-2" berarti Modul 1 punya Izin 2)
                                        $perms = isset($rolePermissions[$role->id])
                                            ? $rolePermissions[$role->id]
                                            : collect();
                                        $assignedPerms = $perms
                                            ->map(function ($p) {
                                                return $p->modul_id . '-' . $p->permission_id;
                                            })
                                            ->values()
                                            ->toJson();
                                    @endphp

                                    {{-- Tombol Hak Akses (Purple) --}}
                                    <button type="button"
                                        @click="$dispatch('open-perm-modal', { 
                                            url: '{{ route('masterdata.roles.permissions.update', $role->id ?? 0) }}',
                                            name: '{{ $role->role_name }}',
                                            assigned: {{ $assignedPerms }} 
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-purple-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-900"
                                        title="Atur Hak Akses">
                                        <i class="fa-solid fa-shield-halved"></i> Akses
                                    </button>

                                    {{-- Tombol Ubah (Amber / Kuning) --}}
                                    <button type="button"
                                        @click="$dispatch('open-edit-modal', { 
                                            url: '{{ route('masterdata.roles.update', $role->id) }}',
                                            code: '{{ $role->role_code }}',
                                            name: '{{ $role->role_name }}',
                                            active: {{ $role->is_active == '1' ? 'true' : 'false' }}
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                                        title="Edit Role">
                                        <i class="fa-solid fa-pencil"></i> Ubah
                                    </button>

                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', { 
                                            url: '{{ route('masterdata.roles.destroy', $role->id) }}',
                                            name: '{{ $role->role_name }}'
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
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
        
        {{-- Pagination --}}
        @if (isset($roles) && $roles->hasPages())
            <div class="mt-6">
                {{ $roles->appends(request()->query())->links() }}
            </div>
        @endif
            </div>
        </div>
    </div>

    {{-- Modal --}}
    @include('masterdata::roles.modal-create')
    @include('masterdata::roles.modal-permissions')
    @include('masterdata::roles.modal-edit')
    @include('masterdata::roles.delete-modal')
@endsection
