@extends('layouts.app')

@section('content')
    <div class="mx-auto" x-cloak>

        {{-- Header & Breadcrumb --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Data Infrastruktur & Inventaris</h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Master Data /</li>
                        <li class="text-blue-600 dark:text-blue-400">Infrastruktur</li>
                    </ol>
                </nav>
            </div>

            <div x-data="{ openCreate: false }">
                <button @click="$dispatch('open-create-modal')" type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                    <i class="fas fa-plus"></i> Tambah Infrastruktur
                </button>
                @include('masterdata::infrastructures.modal-create')
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg mb-6">
                <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg mb-6">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Main Container --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 min-h-[60vh] flex flex-col">

            {{-- AREA FILTER --}}
            <div class="mb-6 space-y-4 bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                <form method="GET" class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        {{-- Input Cari --}}
                        <div class="relative w-full sm:max-w-xs">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 dark:text-gray-500">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama atau kode..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pl-9 text-sm text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:placeholder-gray-400 transition">
                        </div>
                        {{-- Dropdown Jumlah Data --}}
                        <select name="per_page" onchange="this.form.submit()"
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-white transition cursor-pointer">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        {{-- Tombol Filter --}}
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition shadow-sm">
                            <i class="fa-solid fa-filter text-xs"></i> Filter
                        </button>

                        {{-- Tombol Muat Ulang --}}
                        <a href="{{ route('masterdata.infrastructures.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition shadow-sm">
                            <i class="fa-solid fa-rotate text-xs"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- AREA KONTEN (TABEL) --}}
            <div class="flex-1 relative rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Kode</th>
                                <th class="px-6 py-4 font-semibold min-w-[200px]">Nama & Merk</th>
                                <th class="px-6 py-4 font-semibold min-w-[150px]">Tipe & Unit</th>
                                <th class="px-6 py-4 font-semibold text-center">Stok & Harga</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($infrastructures ?? [] as $item)
                                <tr class="bg-transparent transition hover:bg-gray-50 dark:hover:bg-gray-700/20">
                                    {{-- Kolom 1: Kode / ID --}}
                                    <td class="px-6 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                                        #{{ $item->id }}
                                    </td>

                                    {{-- Kolom 2: Foto & Nama Barang --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            {{-- Thumbnail Foto --}}
                                            <div class="h-12 w-12 flex-shrink-0">
                                                @if(!empty($item->photo))
                                                    <button
                                                        type="button"
                                                        @click="$dispatch('open-image-modal', { url: '{{ asset('storage/' . $item->photo) }}', title: '{{ addslashes($item->item_name) }}' })"
                                                        class="group relative block h-12 w-12 overflow-hidden rounded-lg shadow-sm ring-1 ring-gray-200 transition focus:outline-none focus:ring-2 focus:ring-blue-500 dark:ring-gray-700"
                                                        title="Lihat Gambar Penuh">
                                                        <img src="{{ asset('storage/' . $item->photo) }}" alt="Foto Barang" class="h-full w-full object-cover transition duration-300 group-hover:scale-110 group-hover:opacity-75">
                                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 transition-opacity duration-300 group-hover:opacity-100 bg-black/30">
                                                            <i class="fa-solid fa-magnifying-glass text-white drop-shadow-md"></i>
                                                        </div>
                                                    </button>
                                                @else
                                                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                                                        <i class="fa-solid fa-box-open text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Teks Info --}}
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $item->item_name }}
                                                </div>
                                                <div class="mt-1 flex items-center gap-1.5 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                                    <i class="fa-solid fa-tag"></i>
                                                    {{ $item->brand ?: 'Tanpa Merk' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom 3: Tipe & Unit Pemilik --}}
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex mb-1.5 rounded-md bg-purple-50 px-2 py-1 text-[11px] font-semibold text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/30 dark:text-purple-300">
                                            {{ $item->type_description ?? 'Tipe ' . $item->inventory_type }}
                                        </span>
                                        <div class="flex items-center gap-1.5 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                            <i class="fa-regular fa-building"></i>
                                            {{ $item->unit_name ?: 'Universitas (Umum)' }}
                                        </div>
                                    </td>

                                    {{-- Kolom 4: Stok & Harga --}}
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex h-7 items-center justify-center rounded-full bg-gray-100 px-3 text-sm font-bold text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ $item->stock }} <span class="ml-1 text-[10px] font-medium text-gray-500">{{ $item->unit_measure ?: 'PCS' }}</span>
                                        </span>
                                        <div class="mt-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                                            Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}
                                        </div>
                                    </td>

                                    {{-- Kolom 5: Status Kondisi --}}
                                    <td class="px-6 py-4 text-center text-sm">
                                        @if ($item->status == 1)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Baik
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/30 dark:text-red-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Rusak
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Kolom 6: Aksi --}}
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center justify-center gap-2">
                                            <button
                                                type="button"
                                                @click="$dispatch('open-edit-modal', {
                                                    url: '{{ route('masterdata.infrastructures.update', $item->id) }}',
                                                    id: '{{ $item->id }}',
                                                    item_name: '{{ addslashes($item->item_name) }}',
                                                    type: '{{ $item->inventory_type }}',
                                                    brand: '{{ addslashes($item->brand ?? '') }}',
                                                    unit_measure: '{{ $item->unit_measure ?? '' }}',
                                                    stock: '{{ $item->stock }}',
                                                    price: '{{ $item->price ?? 0 }}',
                                                    status: '{{ $item->status ?? 1 }}',
                                                    unit_id: '{{ $item->unit_id ?? '' }}',
                                                    description: '{{ addslashes($item->description ?? '') }}'
                                                })"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                                                title="Ubah Data">
                                                <i class="fa-solid fa-pencil"></i> Ubah
                                            </button>

                                            <button
                                                type="button"
                                                @click="$dispatch('open-delete-modal', {
                                                    url: '{{ route('masterdata.infrastructures.destroy', $item->id) }}',
                                                    name: '{{ addslashes($item->item_name) }}'
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
                                    <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                                <i class="fas fa-boxes-stacked text-3xl text-gray-400 dark:text-gray-500"></i>
                                            </div>
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">Belum Ada Data Infrastruktur</h4>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan data ruangan atau inventaris pertama Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- End of Table Container --}}

            {{-- Pagination --}}
            @if (isset($infrastructures) && $infrastructures->hasPages())
                <div class="mt-6">
                    {{ $infrastructures->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
        {{-- End of Main Container --}}

    </div>
    {{-- End of Wrapper --}}

    {{-- Panggil Modal --}}
    @include('masterdata::infrastructures.modal-edit')
    @include('masterdata::infrastructures.delete-modal')

    {{-- Modal Preview Gambar Penuh --}}
    <div x-data="{ openImage: false, imageUrl: '', imageTitle: '' }"
        @open-image-modal.window="openImage = true; imageUrl = $event.detail.url; imageTitle = $event.detail.title"
        x-show="openImage"
        class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak style="display: none;">

        <div @click.away="openImage = false" x-show="openImage"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="relative flex w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-transparent transition-all max-h-[90vh]">
            
            {{-- Header Modal (Melayang) --}}
            <div class="absolute right-0 top-0 z-10 flex w-full items-center justify-between bg-gradient-to-b from-black/70 to-transparent p-4">
                <h3 class="text-lg font-bold text-white drop-shadow-md" x-text="imageTitle"></h3>
                <button type="button" @click="openImage = false"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md transition hover:bg-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Area Gambar Penuh --}}
            <div class="relative flex flex-1 items-center justify-center p-2">
                <img :src="imageUrl" :alt="imageTitle" class="max-h-[85vh] max-w-full rounded-lg object-contain drop-shadow-2xl">
            </div>
            
            {{-- Bantuan Teks Bawah --}}
            <div class="absolute bottom-4 left-0 w-full text-center">
                <span class="rounded-full bg-black/50 px-3 py-1 text-xs font-medium text-white/80 backdrop-blur-md">
                    Klik di luar gambar untuk menutup
                </span>
            </div>
        </div>
    </div>
@endsection
