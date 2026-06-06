@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="bankSoalApp()" x-init="initData()" x-cloak>

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 pb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
            <div>
                <h2 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-layer-group text-indigo-500"></i> Bank Soal
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                        <li>Monev Akademik /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">Bank Soal</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- TOOLBAR --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex flex-nowrap items-center gap-3">
                <div class="relative min-w-0 flex-1">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="searchCourseQuery" @input.debounce.400ms="fetchCourses(1)"
                        placeholder="Cari nama mata kuliah..."
                        class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-11 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <span class="hidden text-xs font-semibold text-gray-500 dark:text-gray-400 sm:inline">Tampilkan</span>
                    <select x-model="perPageFilter" @change="fetchCourses(1)" title="Jumlah data per halaman"
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
                            <th class="px-6 py-4 font-semibold min-w-[200px]">Mata Kuliah</th>
                            <th class="px-6 py-4 font-semibold min-w-[150px]">Prodi Pengampu</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr x-show="isLoading">
                            <td colspan="3" class="px-6 py-16 text-center text-gray-500">
                                <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-2 text-indigo-600"></i>
                                <p class="text-sm font-semibold text-indigo-800 dark:text-indigo-400">Memuat data...</p>
                            </td>
                        </tr>

                        <tr x-show="coursesList.length === 0 && !isLoading">
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                <i class="mb-3 text-3xl opacity-50 fa-solid fa-box-open"></i><br>
                                Mata kuliah tidak ditemukan.
                            </td>
                        </tr>

                        <template x-for="course in coursesList" :key="course.id">
                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 font-bold text-indigo-600 bg-indigo-100 rounded-full dark:bg-indigo-900/40 dark:text-indigo-400"
                                            x-text="(course.course_name || 'M').charAt(0).toUpperCase()"></div>
                                        <div class="font-medium text-gray-900 dark:text-white" x-text="course.course_name"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800"
                                        x-text="course.unit_name || 'UNIVERSAL'"></span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <button type="button" @click="openCourseProposals(course)"
                                        class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 border border-blue-200 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800 transition shadow-sm">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-2"
            x-show="pagination.total > 0 && !isLoading">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Menampilkan <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.from"></span>
                – <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.to"></span>
                dari <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.total"></span> data
            </span>
            <div class="flex gap-2">
                <button type="button" @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url"
                    class="inline-flex items-center gap-1 rounded-xl bg-gray-200 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-700 dark:text-gray-200">
                    <i class="fa-solid fa-chevron-left"></i> Prev
                </button>
                <button type="button" @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url"
                    class="inline-flex items-center gap-1 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

        @include('monevakademik::bank-soal.modal-proposals')

        {{-- FAB FILTER --}}
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
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Fakultas</label>
                        <select x-model="filterFakultas" @change="fetchProdis(); fetchCourses(1);"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua Fakultas</option>
                            <template x-for="fak in facultiesList" :key="fak.id">
                                <option :value="fak.id" x-text="fak.unit_name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Program Studi</label>
                        <select x-model="filterProdi" @change="fetchCourses(1)" :disabled="!filterFakultas"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 disabled:opacity-60 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua Prodi</option>
                            <template x-for="prd in prodisList" :key="prd.id">
                                <option :value="prd.id" x-text="prd.unit_name"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            <button type="button" @click="filterFabOpen = !filterFabOpen"
                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 outline-none transition hover:bg-indigo-700 hover:shadow-xl"
                :title="activeFilterCount > 0 && !filterFabOpen ? activeFilterCount + ' filter aktif' : (filterFabOpen ? 'Tutup filter' : 'Buka filter')">
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
            Alpine.data('bankSoalApp', () => ({
                isLoading: false,
                filterFabOpen: false,
                filterFakultas: '',
                filterProdi: '',
                searchCourseQuery: '',
                perPageFilter: '50',
                facultiesList: [],
                prodisList: [],
                coursesList: [],
                pagination: {},

                isModalOpen: false,
                isModalLoading: false,
                selectedCourseId: null,
                selectedCourseName: '',
                filterExamType: '',
                filterPeriod: '',
                periodsList: [],
                proposalsList: [],

                get activeFilterCount() {
                    let count = 0;
                    if (this.filterFakultas !== '') count++;
                    if (this.filterProdi !== '') count++;
                    return count;
                },

                initData() {
                    this.fetchFaculties();
                    this.fetchCourses();
                    this.fetchPeriods();
                },

                resetFilters() {
                    this.filterFakultas = '';
                    this.filterProdi = '';
                    this.prodisList = [];
                    this.fetchCourses(1);
                },

                async fetchFaculties() {
                    try {
                        const res = await fetch(`{{ url('monev-akademik/tashih/api/units') }}`);
                        if (res.ok) this.facultiesList = await res.json();
                    } catch (e) { console.error(e); }
                },

                async fetchProdis() {
                    this.filterProdi = '';
                    this.prodisList = [];
                    if (!this.filterFakultas) return;
                    try {
                        const res = await fetch(`{{ url('monev-akademik/tashih/api/units') }}?faculty_id=${this.filterFakultas}`);
                        if (res.ok) this.prodisList = await res.json();
                    } catch (e) { console.error(e); }
                },

                async fetchCourses(page = 1) {
                    this.isLoading = true;
                    this.coursesList = [];

                    const url = new URL(`{{ url('monev-akademik/tashih/api/approved-courses') }}`);
                    url.searchParams.append('page', page);
                    url.searchParams.append('per_page', this.perPageFilter);
                    if (this.filterFakultas) url.searchParams.append('faculty_id', this.filterFakultas);
                    if (this.filterProdi) url.searchParams.append('prodi_id', this.filterProdi);
                    if (this.searchCourseQuery.trim()) url.searchParams.append('search', this.searchCourseQuery.trim());

                    try {
                        const res = await fetch(url);
                        if (!res.ok) throw new Error('Error');
                        const result = await res.json();
                        this.coursesList = result.data || [];
                        this.pagination = {
                            current_page: result.current_page,
                            from: result.from || 0,
                            to: result.to || 0,
                            total: result.total || 0,
                            prev_page_url: result.prev_page_url,
                            next_page_url: result.next_page_url,
                        };
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.isLoading = false;
                    }
                },

                changePage(page) {
                    this.fetchCourses(page);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                async fetchPeriods() {
                    try {
                        const res = await fetch(`{{ url('monev-akademik/bank-soal/api/periods') }}`);
                        if (res.ok) this.periodsList = await res.json();
                    } catch (e) { console.error(e); }
                },

                openCourseProposals(course) {
                    this.selectedCourseId = course.id;
                    this.selectedCourseName = course.course_name;
                    this.filterExamType = '';
                    this.filterPeriod = '';
                    this.isModalOpen = true;
                    this.fetchProposals();
                },

                async fetchProposals() {
                    if (!this.selectedCourseId) return;
                    this.isModalLoading = true;
                    this.proposalsList = [];

                    try {
                        const url = new URL(`{{ url('monev-akademik/bank-soal/api/proposals') }}/${this.selectedCourseId}`);
                        if (this.filterExamType) url.searchParams.append('exam_type', this.filterExamType);
                        if (this.filterPeriod) url.searchParams.append('period_id', this.filterPeriod);

                        const response = await fetch(url);
                        if (!response.ok) throw new Error('Gagal mengambil data');
                        this.proposalsList = await response.json();
                    } catch (error) {
                        console.error(error);
                        if (typeof toastr !== 'undefined') toastr.error('Gagal memuat arsip soal');
                    } finally {
                        this.isModalLoading = false;
                    }
                },

                printPdf(uuid) {
                    if (!uuid) return;
                    window.open(`{{ url('monev-akademik/tashih/print') }}/${uuid}`, '_blank');
                },
            }));
        });
    </script>
@endsection