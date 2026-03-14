@extends('layouts.app')

@section('content')
    {{-- Header & Tombol Aksi --}}
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Data Infrastruktur & Inventaris</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kelola data fasilitas fisik seperti ruangan, gedung, dan barang inventaris di lingkungan Sainteku.
            </p>
        </div>
        <div>
            <button @click="$dispatch('open-create-modal')"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 sm:w-auto">
                <i class="fas fa-plus"></i>
                Tambah Data
            </button>
        </div>
    </div>

    {{-- Alert Notifikasi (Akan muncul jika ada sesi 'success' atau 'error') --}}
    @if (session('success'))
        <div
            class="mb-6 rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-3 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-red-800 shadow-sm dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Tabel Data --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50/50 text-left text-sm dark:bg-gray-700/30">
                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-gray-900 dark:text-white">Kode / ID
                        </th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-gray-900 dark:text-white">Nama
                            Ruangan / Barang</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-gray-900 dark:text-white">Kategori
                            Tipe</th>
                        <th
                            class="px-6 py-4 text-center font-semibold uppercase tracking-wider text-gray-900 dark:text-white">
                            Kuantitas</th>
                        <th
                            class="px-6 py-4 text-center font-semibold uppercase tracking-wider text-gray-900 dark:text-white">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    {{-- Kita gunakan $infrastructures ?? [] agar tidak error saat diuji sebelum Controller siap --}}
                    @forelse($infrastructures ?? [] as $item)
                        <tr class="bg-transparent transition hover:bg-gray-50 dark:hover:bg-gray-700/20">
                            {{-- Kolom ID --}}
                            <td class="px-6 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                                #{{ $item->id }}
                            </td>

                            {{-- Kolom Nama --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $item->description }}
                                </div>
                                <div
                                    class="mt-1 flex items-center gap-1.5 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                    <i class="fa-regular fa-clock"></i>
                                    Ditambahkan: {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y') : '-' }}
                                </div>
                            </td>

                            {{-- Kolom Tipe --}}
                            <td class="px-6 py-4 text-sm">
                                <span
                                    class="inline-flex rounded-md bg-purple-50 px-2.5 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/30 dark:text-purple-300">
                                    {{ $item->type_description ?? 'Tipe ' . $item->inventory_type }}
                                </span>
                            </td>

                            {{-- Kolom Kuantitas --}}
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex h-8 min-w-[32px] items-center justify-center rounded-full bg-gray-100 px-2.5 text-sm font-bold text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $item->quantity }}
                                </span>
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                        @click="$dispatch('open-edit-modal', { 
                                            url: '{{ route('masterdata.infrastructures.update', $item->id) }}',
                                            id: '{{ $item->id }}',
                                            description: '{{ addslashes($item->description) }}',
                                            type: '{{ $item->inventory_type }}',
                                            quantity: '{{ $item->quantity }}'
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                                        title="Ubah Data">
                                        <i class="fa-solid fa-pencil"></i> Ubah
                                    </button>

                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', { 
                                            url: '{{ route('masterdata.infrastructures.destroy', $item->id) }}',
                                            name: '{{ addslashes($item->description) }}'
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                                        title="Hapus Data">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Tampilan saat data kosong --}}
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                        <i class="fas fa-boxes-stacked text-3xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">Belum Ada Data
                                        Infrastruktur</h4>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan data
                                        ruangan atau inventaris pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination (Akan muncul otomatis jika data dari Controller dipaginate) --}}
    @if (isset($infrastructures) && $infrastructures->hasPages())
        <div class="mt-6">
            {{ $infrastructures->links() }}
        </div>
    @endif

    {{-- Nanti Anda bisa include modal create/edit di sini --}}
    @include('masterdata::infrastructures.modal-create')
    @include('masterdata::infrastructures.modal-edit')
    @include('masterdata::infrastructures.delete-modal')
@endsection
