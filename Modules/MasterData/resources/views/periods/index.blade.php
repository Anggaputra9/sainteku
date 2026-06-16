@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="periodsApp()" x-init="initData()" x-cloak>

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 pb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
            <div>
                <h2 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-calendar-days text-indigo-500"></i> Manajemen Tahun Akademik
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                        <li>Master Data /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">Tahun Akademik</li>
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
                    <input type="text" x-model="searchQuery" @input.debounce.400ms="fetchPeriods()"
                        placeholder="Cari tahun akademik atau semester..."
                        class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-11 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <span class="hidden text-xs font-semibold text-gray-500 dark:text-gray-400 sm:inline">Tampilkan</span>
                    <select x-model="perPageFilter" @change="fetchPeriods(1)" title="Jumlah data per halaman"
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
                            <th class="px-6 py-4 font-semibold">Periode Akademik</th>
                            <th class="px-6 py-4 font-semibold">Semester</th>
                            <th class="px-6 py-4 font-semibold text-center">Pengajuan Ujian</th>
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

                        <tr x-show="periodsList.length === 0 && !isLoading">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="mb-3 text-3xl opacity-50 fa-solid fa-calendar-days"></i><br>
                                Tahun akademik tidak ditemukan.
                            </td>
                        </tr>

                        <template x-for="period in periodsList" :key="period.id">
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 font-bold text-sky-600 bg-sky-100 rounded-full dark:bg-sky-900/40 dark:text-sky-400"
                                            x-text="period.initial"></div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white" x-text="period.name"></div>
                                            <div class="text-xs font-mono text-gray-500 dark:text-gray-400">ID: <span x-text="period.id"></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-md bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800"
                                        x-text="period.semester"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300"
                                        x-text="period.proposal_count"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span x-show="period.is_active == '1'"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                        <span class="w-1.5 h-1.5 bg-green-600 rounded-full dark:bg-green-400"></span> Aktif
                                    </span>
                                    <span x-show="period.is_active != '1'"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                        <span class="w-1.5 h-1.5 bg-red-600 rounded-full dark:bg-red-400"></span> Nonaktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <button type="button" @click="openDetail(period)"
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

        @include('masterdata::periods.modal-create')
        @include('masterdata::periods.modal-detail')
        @include('masterdata::periods.delete-modal')

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
                        <select x-model="sortFilter" @change="fetchPeriods(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="name_asc">Tahun A-Z</option>
                            <option value="name_desc">Tahun Z-A</option>
                            <option value="semester_asc">Semester A-Z</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Semester</label>
                        <select x-model="semesterFilter" @change="fetchPeriods(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua</option>
                            <option value="Gasal">Gasal</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</label>
                        <select x-model="statusFilter" @change="fetchPeriods(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
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
            Alpine.data('periodsApp', () => ({
                searchQuery: '',
                perPageFilter: '50',
                sortFilter: 'newest',
                semesterFilter: '',
                statusFilter: '',
                filterFabOpen: false,
                periodsList: [],
                isLoading: false,
                pagination: {},
                alert: { type: '', message: '' },

                get activeFilterCount() {
                    let count = 0;
                    if (this.sortFilter !== 'newest') count++;
                    if (this.semesterFilter !== '') count++;
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
                    this.fetchPeriods();
                },

                flash(type, message) {
                    this.alert = { type, message };
                    setTimeout(() => { this.alert.message = ''; }, 4000);
                },

                async fetchPeriods(page = 1) {
                    this.isLoading = true;
                    this.periodsList = [];

                    const params = new URLSearchParams({
                        page: page,
                        per_page: this.perPageFilter,
                        search: this.searchQuery,
                        sort: this.sortFilter,
                        semester: this.semesterFilter,
                        status: this.statusFilter,
                    });

                    try {
                        const response = await fetch(`{{ route('masterdata.periods.api.data') }}?${params.toString()}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) throw new Error('Network response was not ok');

                        const result = await response.json();
                        this.periodsList = result.data || [];
                        this.pagination = {
                            current_page: result.current_page,
                            from: result.from || 0,
                            to: result.to || 0,
                            total: result.total || 0,
                            prev_page_url: result.prev_page_url,
                            next_page_url: result.next_page_url,
                        };
                    } catch (error) {
                        console.error('Gagal memuat tahun akademik', error);
                        this.pagination = { total: 0, from: 0, to: 0 };
                    } finally {
                        this.isLoading = false;
                    }
                },

                changePage(page) {
                    this.fetchPeriods(page);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                resetFilters() {
                    this.sortFilter = 'newest';
                    this.semesterFilter = '';
                    this.statusFilter = '';
                    this.fetchPeriods(1);
                },

                openDetail(period) {
                    window.dispatchEvent(new CustomEvent('open-detail-modal', {
                        bubbles: true,
                        detail: {
                            url: period.update_url,
                            deleteUrl: period.delete_url,
                            periodLabel: period.label,
                            canDelete: period.can_delete,
                            periodData: {
                                id: period.id,
                                name: period.name,
                                semester: period.semester,
                                active: period.is_active == '1',
                                proposal_count: period.proposal_count,
                            },
                        },
                    }));
                },
            }));
        });
    </script>
@endsection