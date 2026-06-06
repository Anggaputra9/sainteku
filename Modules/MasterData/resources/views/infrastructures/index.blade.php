@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="infrastructuresApp()" x-init="initData()" x-cloak>

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 pb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
            <div>
                <h2 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-boxes-stacked text-indigo-500"></i> Manajemen Infrastruktur
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                        <li>Master Data /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">Infrastruktur</li>
                    </ol>
                </nav>
            </div>
            <button type="button" @click="window.dispatchEvent(new CustomEvent('open-create-modal', { bubbles: true }))"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> Tambah
            </button>
        </div>

        {{-- ALERTS --}}
        <template x-if="alert.message">
            <div class="flex items-center gap-3 p-4 border-l-4 rounded-r-lg shadow-sm"
                :class="alert.type === 'error' ? 'border-red-500 bg-red-50 text-red-700' : 'border-green-500 bg-green-50 text-green-700'">
                <i class="fa-solid" :class="alert.type === 'error' ? 'fa-circle-xmark' : 'fa-check-circle'"></i>
                <span class="text-sm font-bold" x-text="alert.message"></span>
            </div>
        </template>

        {{-- TOOLBAR --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex flex-nowrap items-center gap-3">
                <div class="relative min-w-0 flex-1">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="searchQuery" @input.debounce.400ms="fetchItems()"
                        placeholder="Cari nama, kode, merk, atau unit..."
                        class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-11 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <span class="hidden text-xs font-semibold text-gray-500 dark:text-gray-400 sm:inline">Tampilkan</span>
                    <select x-model="perPageFilter" @change="fetchItems(1)" title="Jumlah data per halaman"
                        class="w-24 rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="150">150</option>
                        <option value="250">250</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                    <thead class="text-xs uppercase bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Info Barang</th>
                            <th class="px-6 py-4 font-semibold min-w-[150px]">Tipe & Unit</th>
                            <th class="px-6 py-4 font-semibold text-center">Stok & Harga</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr x-show="isLoading">
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-2 text-indigo-600"></i>
                                <p class="text-sm font-semibold text-indigo-800 dark:text-indigo-400">Memuat data...</p>
                            </td>
                        </tr>

                        <tr x-show="itemsList.length === 0 && !isLoading">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="mb-3 text-3xl opacity-50 fa-solid fa-boxes-stacked"></i><br>
                                Data infrastruktur tidak ditemukan.
                            </td>
                        </tr>

                        <template x-for="item in itemsList" :key="item.id">
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <template x-if="item.photo_url">
                                            <button type="button" @click="openImage(item)"
                                                class="group relative flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full ring-2 ring-indigo-100 dark:ring-indigo-900/50">
                                                <img :src="item.photo_url" :alt="item.item_name" class="h-full w-full object-cover transition group-hover:scale-110">
                                            </button>
                                        </template>
                                        <template x-if="!item.photo_url">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400"
                                                x-text="item.initial"></div>
                                        </template>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white" x-text="item.item_name"></div>
                                            <div class="text-xs font-mono text-gray-500 dark:text-gray-400" x-text="item.id"></div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                <i class="fa-solid fa-tag text-[10px]"></i>
                                                <span x-text="item.brand || 'Tanpa Merk'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-md bg-purple-50 px-2.5 py-0.5 text-xs font-medium text-purple-700 border border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800"
                                        x-text="item.type_description"></span>
                                    <div class="mt-1.5 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fa-regular fa-building"></i>
                                        <span x-text="item.unit_name || 'Universitas (Umum)'"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex h-7 items-center justify-center rounded-full bg-gray-100 px-3 text-sm font-bold text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        <span x-text="item.stock"></span>
                                        <span class="ml-1 text-[10px] font-medium text-gray-500" x-text="item.unit_measure || 'PCS'"></span>
                                    </span>
                                    <div class="mt-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                        Rp <span x-text="item.price_formatted"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-show="item.status == '1'"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                        <span class="w-1.5 h-1.5 bg-green-600 rounded-full dark:bg-green-400"></span> Baik
                                    </span>
                                    <span x-show="item.status != '1'"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                        <span class="w-1.5 h-1.5 bg-red-600 rounded-full dark:bg-red-400"></span> Rusak
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <button type="button" @click="openDetail(item)"
                                        class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 border border-blue-200 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800 dark:hover:bg-blue-800/50 transition shadow-sm">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-2"
            x-show="pagination.total > 0 && !isLoading">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Menampilkan <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.from"></span>
                – <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.to"></span>
                dari <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.total"></span> data
            </span>
            <div class="flex gap-2">
                <button type="button" @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url"
                    class="inline-flex items-center gap-1 rounded-xl bg-gray-200 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    <i class="fa-solid fa-chevron-left"></i> Prev
                </button>
                <button type="button" @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url"
                    class="inline-flex items-center gap-1 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

        @include('masterdata::infrastructures.modal-create')
        @include('masterdata::infrastructures.modal-detail')
        @include('masterdata::infrastructures.delete-modal')

        {{-- FAB Filter --}}
        <template x-teleport="body">
        <div class="fixed z-[9990] flex flex-col items-end gap-3"
            style="bottom: 1.5rem; right: 1.5rem; left: auto;"
            @click.away="filterFabOpen = false">
            <div x-show="filterFabOpen" x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-3 scale-95"
                class="w-72 max-w-[calc(100vw-3rem)] rounded-2xl border border-gray-200 bg-white p-4 shadow-2xl ring-1 ring-gray-900/5 dark:border-gray-700 dark:bg-[#1e293b]">

                <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-filter text-indigo-500"></i> Filter
                    </h3>
                    <button type="button" x-show="activeFilterCount > 0" @click="resetFilters()"
                        class="text-[11px] font-bold uppercase tracking-wide text-red-600 hover:text-red-700 dark:text-red-400">
                        Reset
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Urutkan</label>
                        <select x-model="sortFilter" @change="fetchItems(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="name_asc">Nama A-Z</option>
                            <option value="name_desc">Nama Z-A</option>
                            <option value="code_asc">Kode A-Z</option>
                            <option value="code_desc">Kode Z-A</option>
                            <option value="stock_desc">Stok Terbanyak</option>
                            <option value="stock_asc">Stok Terendah</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Kategori Tipe</label>
                        <select x-model="typeFilter" @change="fetchItems(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua tipe</option>
                            @foreach ($inventoryTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</label>
                        <select x-model="statusFilter" @change="fetchItems(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua</option>
                            <option value="1">Baik / Aktif</option>
                            <option value="0">Rusak / Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="button" @click="filterFabOpen = !filterFabOpen"
                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 outline-none transition hover:bg-indigo-700 hover:shadow-xl"
                :title="activeFilterCount > 0 && !filterFabOpen
                    ? activeFilterCount + ' filter aktif'
                    : (filterFabOpen ? 'Tutup filter' : 'Buka filter')">
                <span class="relative inline-block leading-none">
                    <i class="fa-solid text-lg transition-transform duration-200"
                        :class="filterFabOpen ? 'fa-xmark' : 'fa-sliders'"></i>
                    <span x-show="activeFilterCount > 0 && !filterFabOpen" x-cloak
                        style="position:absolute;top:-3px;right:-6px;width:8px;height:8px;border-radius:9999px;background:#ef4444;border:2px solid #fff;box-shadow:0 1px 2px rgba(0,0,0,.25);pointer-events:none;"
                        aria-hidden="true"></span>
                </span>
            </button>
        </div>
        </template>

        {{-- Modal Preview Gambar --}}
        <template x-teleport="#modal-root">
            <div x-show="imagePreview.open"
                class="app-modal-overlay fixed inset-0 flex items-center justify-center bg-black/80 p-4 overflow-y-auto backdrop-blur-sm z-[10000]"
                x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>
                <div @click.away="imagePreview.open = false"
                    class="relative flex w-full max-w-4xl flex-col overflow-hidden rounded-2xl transition-all max-h-[90vh]">
                    <div class="absolute right-0 top-0 z-10 flex w-full items-center justify-between bg-gradient-to-b from-black/70 to-transparent p-4">
                        <h3 class="text-lg font-bold text-white drop-shadow-md" x-text="imagePreview.title"></h3>
                        <button type="button" @click="imagePreview.open = false"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md transition hover:bg-red-500">
                            <i class="fas fa-xmark text-lg"></i>
                        </button>
                    </div>
                    <div class="relative flex flex-1 items-center justify-center p-2">
                        <img :src="imagePreview.url" :alt="imagePreview.title" class="max-h-[85vh] max-w-full rounded-lg object-contain drop-shadow-2xl">
                    </div>
                </div>
            </div>
        </template>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('infrastructuresApp', () => ({
                searchQuery: '',
                perPageFilter: '50',
                sortFilter: 'newest',
                typeFilter: '',
                statusFilter: '',
                filterFabOpen: false,
                itemsList: [],
                isLoading: false,
                pagination: {},
                alert: { type: '', message: '' },
                imagePreview: { open: false, url: '', title: '' },

                get activeFilterCount() {
                    let count = 0;
                    if (this.sortFilter !== 'newest') count++;
                    if (this.typeFilter !== '') count++;
                    if (this.statusFilter !== '') count++;
                    return count;
                },

                initData() {
                    @if(session('success'))
                        this.flash('success', @js(session('success')));
                    @endif
                    @if(session('error'))
                        this.flash('error', @js(session('error')));
                    @endif
                    window.addEventListener('open-image-from-detail', (e) => {
                        this.imagePreview = { open: true, url: e.detail.url, title: e.detail.title };
                    });
                    this.fetchItems();
                },

                flash(type, message) {
                    this.alert = { type, message };
                    setTimeout(() => { this.alert.message = ''; }, 4000);
                },

                openImage(item) {
                    this.imagePreview = { open: true, url: item.photo_url, title: item.item_name };
                },

                async fetchItems(page = 1) {
                    this.isLoading = true;
                    this.itemsList = [];

                    const params = new URLSearchParams({
                        page: page,
                        per_page: this.perPageFilter,
                        search: this.searchQuery,
                        sort: this.sortFilter,
                        inventory_type: this.typeFilter,
                        status: this.statusFilter,
                    });

                    try {
                        const response = await fetch(`{{ route('masterdata.infrastructures.api.data') }}?${params.toString()}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) throw new Error('Network response was not ok');

                        const result = await response.json();
                        this.itemsList = result.data || [];
                        this.pagination = {
                            current_page: result.current_page,
                            from: result.from || 0,
                            to: result.to || 0,
                            total: result.total || 0,
                            prev_page_url: result.prev_page_url,
                            next_page_url: result.next_page_url,
                        };
                    } catch (error) {
                        console.error('Gagal memuat infrastruktur', error);
                        this.pagination = { total: 0, from: 0, to: 0 };
                    } finally {
                        this.isLoading = false;
                    }
                },

                changePage(page) {
                    this.fetchItems(page);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                resetFilters() {
                    this.sortFilter = 'newest';
                    this.typeFilter = '';
                    this.statusFilter = '';
                    this.fetchItems(1);
                },

                openDetail(item) {
                    window.dispatchEvent(new CustomEvent('open-detail-modal', {
                        bubbles: true,
                        detail: {
                            url: item.update_url,
                            deleteUrl: item.delete_url,
                            itemName: item.item_name,
                            canDelete: item.can_delete,
                            itemData: {
                                id: item.id,
                                item_name: item.item_name,
                                inventory_type: String(item.inventory_type),
                                type_description: item.type_description,
                                brand: item.brand || '',
                                unit_measure: item.unit_measure || '',
                                stock: item.stock,
                                price: item.price,
                                price_formatted: item.price_formatted,
                                status: String(item.status),
                                unit_id: item.unit_id || '',
                                unit_name: item.unit_name,
                                description: item.description || '',
                                photo_url: item.photo_url,
                                loan_count: item.loan_count,
                            },
                        },
                    }));
                },
            }));
        });
    </script>
@endsection