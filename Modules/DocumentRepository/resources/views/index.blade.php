@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .flatpickr-calendar { z-index: 9999999 !important; }
    </style>

    <div class="space-y-6" x-data="documentsApp()" x-init="initData()" x-cloak>

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 pb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
            <div>
                <h2 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-folder-open text-indigo-500"></i> Repositori Dokumen
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                        <li>Manajemen Dokumen /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">Daftar Dokumen</li>
                    </ol>
                </nav>
            </div>
            @if(Auth::user()->hasPermission(1, 'C'))
            <button type="button" @click="window.dispatchEvent(new CustomEvent('open-create-modal', { bubbles: true }))"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700">
                <i class="fa-solid fa-upload"></i> Unggah Dokumen
            </button>
            @endif
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
                    <input type="text" x-model="searchQuery" @input.debounce.400ms="fetchDocuments()"
                        placeholder="Cari kode, judul, tipe, unit, atau pengunggah..."
                        class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-11 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <span class="hidden text-xs font-semibold text-gray-500 dark:text-gray-400 sm:inline">Tampilkan</span>
                    <select x-model="perPageFilter" @change="fetchDocuments(1)" title="Jumlah data per halaman"
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
                            <th class="px-6 py-4 font-semibold">Info Dokumen</th>
                            <th class="px-6 py-4 font-semibold">Tipe & Unit</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Versi</th>
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

                        <tr x-show="documentsList.length === 0 && !isLoading">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="mb-3 text-3xl opacity-50 fa-solid fa-folder-open"></i><br>
                                Dokumen tidak ditemukan.
                            </td>
                        </tr>

                        <template x-for="doc in documentsList" :key="doc.id">
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 mt-0.5 font-bold text-indigo-600 bg-indigo-100 rounded-full dark:bg-indigo-900/40 dark:text-indigo-400"
                                            x-text="doc.initial"></div>
                                        <div>
                                            <div class="font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400" x-text="doc.document_id"></div>
                                            <div class="font-medium text-gray-900 dark:text-white" x-text="doc.document_title"></div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                <i class="fa-regular fa-user mr-1"></i>
                                                <span x-text="doc.creator_name"></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-indigo-600 dark:text-indigo-400" x-text="doc.type_name"></div>
                                    <div class="text-xs text-gray-500 mt-0.5" x-text="doc.unit_name"></div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-bold border"
                                        :class="{
                                            'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50': doc.status == 3,
                                            'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50': doc.status == 4,
                                            'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50': doc.status == 1 || doc.status == 2,
                                            'bg-gray-100 text-gray-800 border-gray-200': doc.status != 1 && doc.status != 2 && doc.status != 3 && doc.status != 4
                                        }"
                                        x-text="doc.status_label"></span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50">
                                        v<span x-text="doc.version"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <button type="button" @click="openDetail(doc)"
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

        @include('documentrepository::modal-create')
        @include('documentrepository::modal-detail')
        @include('documentrepository::modal-revise')

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
                        <select x-model="sortFilter" @change="fetchDocuments(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="title_asc">Judul A-Z</option>
                            <option value="title_desc">Judul Z-A</option>
                            <option value="code_asc">Kode A-Z</option>
                            <option value="code_desc">Kode Z-A</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</label>
                        <select x-model="statusFilter" @change="fetchDocuments(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua</option>
                            <option value="pending">Menunggu Review</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Perlu Revisi</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tipe Dokumen</label>
                        <select x-model="typeFilter" @change="fetchDocuments(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua Tipe</option>
                            @foreach ($documentTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->description }}</option>
                            @endforeach
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

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('documentsApp', () => ({
                searchQuery: '',
                perPageFilter: '50',
                sortFilter: 'newest',
                statusFilter: '',
                typeFilter: '',
                filterFabOpen: false,
                documentsList: [],
                isLoading: false,
                pagination: {},
                alert: { type: '', message: '' },

                get activeFilterCount() {
                    let count = 0;
                    if (this.sortFilter !== 'newest') count++;
                    if (this.statusFilter !== '') count++;
                    if (this.typeFilter !== '') count++;
                    return count;
                },

                initData() {
                    @if(session('success'))
                        this.flash('success', @js(session('success')));
                    @endif
                    @if(session('error'))
                        this.flash('error', @js(session('error')));
                    @endif
                    @if($errors->any())
                        this.flash('error', @js($errors->first() ?? 'Gagal menyimpan data.'));
                    @endif
                    this.fetchDocuments();
                },

                flash(type, message) {
                    this.alert = { type, message };
                    setTimeout(() => { this.alert.message = ''; }, 4000);
                },

                async fetchDocuments(page = 1) {
                    this.isLoading = true;
                    this.documentsList = [];

                    const params = new URLSearchParams({
                        page: page,
                        per_page: this.perPageFilter,
                        search: this.searchQuery,
                        sort: this.sortFilter,
                        status: this.statusFilter,
                        document_type_id: this.typeFilter,
                    });

                    try {
                        const response = await fetch(`{{ route('DocumentRepository.api.data') }}?${params.toString()}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) throw new Error('Network response was not ok');

                        const result = await response.json();
                        this.documentsList = result.data || [];
                        this.pagination = {
                            current_page: result.current_page,
                            from: result.from || 0,
                            to: result.to || 0,
                            total: result.total || 0,
                            prev_page_url: result.prev_page_url,
                            next_page_url: result.next_page_url,
                        };
                    } catch (error) {
                        console.error('Gagal memuat dokumen', error);
                        this.pagination = { total: 0, from: 0, to: 0 };
                    } finally {
                        this.isLoading = false;
                    }
                },

                changePage(page) {
                    this.fetchDocuments(page);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                resetFilters() {
                    this.sortFilter = 'newest';
                    this.statusFilter = '';
                    this.typeFilter = '';
                    this.fetchDocuments(1);
                },

                openDetail(doc) {
                    window.dispatchEvent(new CustomEvent('open-detail-modal', {
                        bubbles: true,
                        detail: { doc, context: 'repository' },
                    }));
                },
            }));
        });
    </script>
@endsection