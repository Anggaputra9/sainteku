@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="space-y-6">

            {{-- Breadcrumb & Title --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Manajemen Pengguna
                    </h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Master Data /</li>
                            <li class="text-blue-600 dark:text-blue-400">Users</li>
                        </ol>
                    </nav>
                </div>

                {{-- Tombol Tambah (Success: Hijau, fa-plus) --}}
                <div x-data="{ openCreate: false }">
                    <button @click="openCreate = true"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fas fa-plus"></i>
                        Tambah User
                    </button>

                    @include('masterdata::admin.modal-create')
                </div>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div
                    class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                    <div
                        class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-green-800 dark:text-green-400">Sukses!</h5>
                        <p class="text-sm text-green-700 dark:text-green-500">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Filter & Search --}}
            <div class="rounded-lg">
                <form method="GET" class="flex flex-wrap items-center justify-between gap-3">

                    {{-- Input Cari --}}
                    <div class="relative w-full sm:max-w-xs">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 dark:text-gray-500">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="w-full rounded-md border border-gray-300 bg-gray-50 py-1.5 pl-9 pr-3 text-sm text-gray-900 focus:border-teal-500 focus:ring-teal-500 outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition">
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        {{-- Tombol Filter --}}
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition shadow-sm">
                            <i class="fa-solid fa-filter text-xs"></i> Filter
                        </button>

                        {{-- Tombol Muat Ulang --}}
                        <a href="{{ route('masterdata.admin.users.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-md bg-teal-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-teal-700 focus:ring-4 focus:ring-teal-300 dark:focus:ring-teal-800 transition shadow-sm">
                            <i class="fa-solid fa-rotate text-xs"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table Card --}}
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4 font-semibold">ID</th>
                                <th class="px-6 py-4 font-semibold">Info User</th>
                                <th class="px-6 py-4 font-semibold">Role</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $user->id }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600 dark:bg-blue-900/40 dark:text-blue-400">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($user->roles as $role)
                                                <span
                                                    class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
                                                    {{ $role->role_name }}
                                                </span>
                                            @empty
                                                <span class="text-xs italic text-gray-400">No Role</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->is_active)
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                                <span
                                                    class="h-1.5 w-1.5 rounded-full bg-green-600 dark:bg-green-400"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-600 dark:bg-red-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- Tombol Ubah (Warning: Oranye, fa-pencil) --}}
                                            <button
                                                @click="$dispatch('open-edit-modal', { 
                                                url: '{{ route('masterdata.admin.users.update', $user->id) }}',
                                                name: '{{ $user->name }}',
                                                email: '{{ $user->email }}',
                                                identity: '{{ $user->identity_id }}',
                                                type: '{{ $user->user_type }}',
                                                active: {{ $user->is_active ? 'true' : 'false' }},
                                                roles: {{ json_encode($user->roles->pluck('id')) }}
                                                })"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800 transition shadow-sm"
                                                title="Ubah Data">
                                                <i class="fa-solid fa-pencil"></i> Ubah
                                            </button>
                                            @include('masterdata::admin.modal-edit')

                                            {{-- Tombol Hapus (Danger: Merah, fa-trash) --}}
                                            <button
                                                @click="$dispatch('open-delete-modal', { 
                                                url: '{{ route('masterdata.admin.users.destroy', $user->id) }}',
                                                name: '{{ $user->name }}'
                                                })"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900 transition shadow-sm"
                                                title="Hapus Data">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                            @include('masterdata::admin.delete-modal')
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-2 text-gray-500 dark:text-gray-400">
                                            <i class="fa-solid fa-folder-open text-4xl mb-2 opacity-50"></i>
                                            <p class="text-sm font-medium">Data user tidak ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Custom --}}
                @if ($users->hasPages())
                    <div class="border-t border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
