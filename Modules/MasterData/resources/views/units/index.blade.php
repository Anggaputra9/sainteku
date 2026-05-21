@extends('layouts.app')

@section('content')
    <div class="mx-auto">
        <div class="space-y-6">

            {{-- Header & Breadcrumb --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Unit / Prodi</h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Master Data /</li>
                            <li class="text-blue-600 dark:text-blue-400">Unit</li>
                        </ol>
                    </nav>
                </div>

                {{-- Tombol Tambah (Green, fa-plus) --}}
                <div x-data="{ openCreate: false }">
                    <button @click="$dispatch('open-create-modal')"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fas fa-plus"></i>
                        Tambah Unit
                    </button>

                    @include('masterdata::units.modal-create')
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
                                placeholder="Cari nama unit..."
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
                        <a href="{{ route('masterdata.units.index') }}"
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
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Nama Unit
                        </th>
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Tipe</th>
                        <th
                            class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider text-center">
                            Status</th>
                        <th
                            class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($units as $unit)
                        <tr class="bg-transparent hover:bg-gray-50 dark:hover:bg-gray-700/20 transition">
                            <td class="px-4 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">#{{ $unit->id }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $unit->unit_name }}
                                </div>

                                @if ($unit->unit_parent)
                                    @php
                                        // Mencari nama induk dari koleksi $parentUnits berdasarkan ID-nya
                                        $parent = $parentUnits->firstWhere('id', $unit->unit_parent);
                                        $parentName = $parent ? $parent->unit_name : $unit->unit_parent;
                                    @endphp
                                    <div
                                        class="mt-1 flex items-center gap-1.5 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                        <i class="fa-solid fa-turn-up fa-rotate-90 text-gray-400"></i>
                                        Induk: <span
                                            class="font-bold text-blue-600 dark:text-blue-200">{{ $parentName }}</span>
                                    </div>
                                @else
                                    <div
                                        class="mt-1 flex items-center gap-1.5 text-[11px] font-medium text-emerald-600 dark:text-emerald-400">
                                        <i class="fa-solid fa-sitemap"></i>
                                        Induk Universitas (Pusat)
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-sm">
                                @php
                                    // Menggunakan $type->description sesuai dengan nama kolom di database
                                    $type = $unitTypes->firstWhere('id', $unit->unit_type_id);
                                    $typeName = $type
                                        ? $type->description ?? 'Level ' . $unit->unit_type_id
                                        : 'Level ' . $unit->unit_type_id;
                                @endphp
                                <span
                                    class="inline-flex rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/30 dark:text-purple-300">
                                    {{ $typeName }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-center">
                                @if ($unit->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Non-aktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                        @click="$dispatch('open-edit-modal', { 
                                            url: '{{ route('masterdata.units.update', $unit->id) }}',
                                            id: '{{ $unit->id }}',
                                            name: '{{ $unit->unit_name }}',
                                            type: '{{ $unit->unit_type_id }}',
                                            parent: '{{ $unit->unit_parent }}',
                                            active: {{ $unit->is_active == '1' ? 'true' : 'false' }}
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                                        title="Ubah Unit">
                                        <i class="fa-solid fa-pencil"></i> Ubah
                                    </button>

                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', { 
                                            url: '{{ route('masterdata.units.destroy', $unit->id) }}',
                                            name: '{{ $unit->unit_name }}'
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                                        title="Hapus Unit">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-building text-3xl mb-2 opacity-20"></i>
                                <p>Belum ada data unit yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

                <div class="mt-6">
                    {{ $units->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Pemanggilan file modal (Sama seperti di Roles) --}}
    @include('masterdata::units.modal-edit')
    @include('masterdata::units.delete-modal')
@endsection
