@extends('layouts.app')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Unit / Prodi</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola struktur fakultas, jurusan, dan program studi di
                lingkungan Sainteku.</p>
        </div>
        <div>
            <button @click="$dispatch('open-create-modal')"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800">
                <i class="fas fa-plus"></i>
                Tambah Unit
            </button>
        </div>
    </div>

    @if (session('success'))
        <div
            class="mb-6 rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-3 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-400">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-red-800 shadow-sm dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
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
                                            class="text-blue-600 dark:text-blue-400">{{ $parentName }}</span>
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
    </div>

    <div class="mt-6">
        {{ $units->links() }}
    </div>

    {{-- Pemanggilan file modal (Sama seperti di Roles) --}}
    @include('masterdata::units.modal-create')
    @include('masterdata::units.modal-edit')
    @include('masterdata::units.delete-modal')
@endsection
