@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="tashihApp()" x-init="initData()"
        @buka-modal-matkul.window="openSelectCourse = true; courseId = ''; courseName = '';"
        @lanjut-bikin-soal.window="
            courseId = $event.detail.id;
            courseName = $event.detail.name;
            openCreate = true;
            setTimeout(() => { if(typeof initCreateModal === 'function') initCreateModal(courseId) }, 100);
        " x-cloak>

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 pb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
            <div>
                <h2 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-file-circle-check text-indigo-500"></i> Tashih Soal
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                        <li>Monev Akademik /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">Tashih Soal</li>
                    </ol>
                </nav>
            </div>
            @if(Auth::user()->hasPermission(3, 'C'))
            <button type="button" @click="$dispatch('buka-modal-matkul')"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> Buat Pengajuan
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

        {{-- TABS --}}
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" @click="switchTab('saya')"
                :class="activeTab === 'saya' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700'"
                class="px-5 py-2 text-sm font-semibold rounded-full transition flex items-center gap-2">
                <i class="fa-solid fa-folder-open"></i> Riwayat Pengajuan Saya
            </button>
            @if($isReviewer)
            <button type="button" @click="switchTab('review')"
                :class="activeTab === 'review' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50'"
                class="px-5 py-2 text-sm font-semibold rounded-full transition flex items-center gap-2">
                <i class="fa-solid fa-clipboard-check"></i> Antrean Review
                <span class="ml-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full"
                    :class="activeTab === 'review' ? 'bg-white text-indigo-600' : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'"
                    x-text="reviewQueueCount"></span>
            </button>
            @endif
        </div>

        {{-- TOOLBAR --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex flex-nowrap items-center gap-3">
                <div class="relative min-w-0 flex-1">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="searchQuery" @input.debounce.400ms="fetchList(1)"
                        placeholder="Cari mata kuliah, jenis ujian, periode, atau pengaju..."
                        class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-11 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <span class="hidden text-xs font-semibold text-gray-500 dark:text-gray-400 sm:inline">Tampilkan</span>
                    <select x-model="perPageFilter" @change="fetchList(1)" title="Jumlah data per halaman"
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

        {{-- TAB: RIWAYAT SAYA --}}
        <div x-show="activeTab === 'saya'">
            <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                                <th class="px-6 py-4 font-semibold text-center">Jenis & Periode</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr x-show="isLoading">
                                <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-2 text-indigo-600"></i>
                                    <p class="text-sm font-semibold text-indigo-800 dark:text-indigo-400">Memuat data...</p>
                                </td>
                            </tr>
                            <tr x-show="proposalsList.length === 0 && !isLoading">
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <i class="mb-3 text-3xl opacity-50 fa-solid fa-folder-open"></i><br>
                                    Belum ada riwayat pengajuan.
                                </td>
                            </tr>
                            <template x-for="prop in proposalsList" :key="prop.uuid">
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 font-bold text-indigo-600 bg-indigo-100 rounded-full dark:bg-indigo-900/40 dark:text-indigo-400"
                                                x-text="prop.initial"></div>
                                            <div class="font-medium text-gray-900 dark:text-white" x-text="prop.course_name"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-bold text-gray-700 dark:text-gray-300" x-text="prop.exam_type"></span>
                                        <div class="text-xs text-gray-500 mt-0.5" x-text="prop.period_name"></div>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border"
                                            :class="{
                                                'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400': prop.status === 'APPROVED',
                                                'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-900/30 dark:text-orange-400': prop.status === 'REVISED',
                                                'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400': prop.status !== 'APPROVED' && prop.status !== 'REVISED'
                                            }"
                                            x-text="prop.status"></span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <button type="button" @click="viewDetail(prop.uuid, 'saya')"
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
        </div>

        {{-- TAB: ANTREAN REVIEW --}}
        @if($isReviewer)
        <div x-show="activeTab === 'review'" x-cloak>
            <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Dosen Pengaju</th>
                                <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                                <th class="px-6 py-4 font-semibold text-center">Jenis</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr x-show="isLoading">
                                <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-2 text-indigo-600"></i>
                                    <p class="text-sm font-semibold text-indigo-800 dark:text-indigo-400">Memuat data...</p>
                                </td>
                            </tr>
                            <tr x-show="proposalsList.length === 0 && !isLoading">
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada antrean review.
                                </td>
                            </tr>
                            <template x-for="prop in proposalsList" :key="prop.uuid">
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4 font-semibold" x-text="prop.creator_name"></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 font-bold text-indigo-600 bg-indigo-100 rounded-full dark:bg-indigo-900/40 dark:text-indigo-400"
                                                x-text="prop.initial"></div>
                                            <div class="font-medium text-gray-900 dark:text-white" x-text="prop.course_name"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold" x-text="prop.exam_type"></td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <button type="button" @click="viewDetail(prop.uuid, 'review')"
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
        </div>
        @endif

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

        @include('monevakademik::tashih.partials.modal-select-course')
        @include('monevakademik::tashih.partials.modal-create-workspace')
        @include('monevakademik::tashih.partials.modal-bank-soal')
        @include('monevakademik::tashih.partials.modal-detail')

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
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Urutkan</label>
                        <select x-model="sortFilter" @change="fetchList(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="course_asc">Mata Kuliah A-Z</option>
                            <option value="course_desc">Mata Kuliah Z-A</option>
                            <option value="exam_asc">Jenis Ujian A-Z</option>
                            <option value="exam_desc">Jenis Ujian Z-A</option>
                        </select>
                    </div>

                    <div x-show="activeTab === 'saya'">
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</label>
                        <select x-model="statusFilter" @change="fetchList(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua</option>
                            <option value="SUBMITTED">Menunggu Review</option>
                            <option value="APPROVED">Disetujui</option>
                            <option value="REVISED">Perlu Revisi</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Jenis Ujian</label>
                        <select x-model="examTypeFilter" @change="fetchList(1)"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua</option>
                            <option value="UTS">UTS</option>
                            <option value="UAS">UAS</option>
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
            Alpine.data('tashihApp', () => ({
                activeTab: 'saya',
                searchQuery: '',
                perPageFilter: '50',
                sortFilter: 'newest',
                statusFilter: '',
                examTypeFilter: '',
                filterFabOpen: false,
                proposalsList: [],
                isLoading: false,
                pagination: {},
                alert: { type: '', message: '' },
                reviewQueueCount: {{ $reviewQueueCount ?? 0 }},

                openSelectCourse: false,
                openCreate: false,
                openBankSoal: false,
                openDetail: false,
                openApprove: false,
                openRevise: false,
                openDelete: false,
                openSignature: false,

                courseId: '',
                courseName: '',
                isEditMode: false,
                editUuid: '',

                bankSoalList: [],
                searchQueryBank: '',
                bankViewMode: 'courses',
                facultiesList: [],
                prodisList: [],
                coursesList: [],
                filterFakultas: '',
                filterProdi: '',
                selectedBankCourseName: '',
                searchCourseQuery: '',

                selectedProposal: null,
                detailSource: 'saya',
                userId: '{{ Auth::id() ?? 0 }}',
                isReviewer: {{ $isReviewer ? 'true' : 'false' }},

                get activeFilterCount() {
                    let count = 0;
                    if (this.sortFilter !== 'newest') count++;
                    if (this.activeTab === 'saya' && this.statusFilter !== '') count++;
                    if (this.examTypeFilter !== '') count++;
                    return count;
                },

                initData() {
                    @if(session('success'))
                        this.flash('success', @js(session('success')));
                    @endif
                    @if(session('error'))
                        this.flash('error', @js(session('error')));
                    @endif
                    this.fetchList();

                    const urlParams = new URLSearchParams(window.location.search);
                    const openModalUuid = urlParams.get('open_modal');
                    if (openModalUuid) {
                        setTimeout(() => {
                            this.activeTab = 'review';
                            this.viewDetail(openModalUuid, 'review');
                        }, 300);
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                },

                flash(type, message) {
                    this.alert = { type, message };
                    setTimeout(() => { this.alert.message = ''; }, 4000);
                },

                switchTab(tab) {
                    this.activeTab = tab;
                    this.fetchList(1);
                },

                async fetchList(page = 1) {
                    this.isLoading = true;
                    this.proposalsList = [];

                    const params = new URLSearchParams({
                        page: page,
                        per_page: this.perPageFilter,
                        search: this.searchQuery,
                        sort: this.sortFilter,
                        exam_type: this.examTypeFilter,
                    });

                    if (this.activeTab === 'saya') {
                        params.append('status', this.statusFilter);
                    }

                    const url = this.activeTab === 'review'
                        ? `{{ route('monevakademik.tashih.api.review.data') }}?${params}`
                        : `{{ route('monevakademik.tashih.api.data') }}?${params}`;

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });
                        if (!response.ok) throw new Error('Network error');

                        const result = await response.json();
                        this.proposalsList = result.data || [];
                        this.pagination = {
                            current_page: result.current_page,
                            from: result.from || 0,
                            to: result.to || 0,
                            total: result.total || 0,
                            prev_page_url: result.prev_page_url,
                            next_page_url: result.next_page_url,
                        };

                        if (this.activeTab === 'review') {
                            this.reviewQueueCount = result.total || 0;
                        }
                    } catch (error) {
                        console.error('Gagal memuat pengajuan', error);
                        this.pagination = { total: 0, from: 0, to: 0 };
                    } finally {
                        this.isLoading = false;
                    }
                },

                changePage(page) {
                    this.fetchList(page);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                resetFilters() {
                    this.sortFilter = 'newest';
                    this.statusFilter = '';
                    this.examTypeFilter = '';
                    this.fetchList(1);
                },

                openBankSoalModal() {
                    this.openBankSoal = true;
                    this.bankViewMode = 'courses';
                    this.filterFakultas = '';
                    this.filterProdi = '';
                    this.searchCourseQuery = '';
                    this.searchQueryBank = '';
                    this.fetchFaculties();
                    this.fetchCourses();
                },

                fetchFaculties() {
                    fetch("{{ url('monev-akademik/tashih/api/units') }}")
                        .then(res => res.json())
                        .then(data => { this.facultiesList = data; })
                        .catch(err => console.error('Gagal load fakultas', err));
                },

                fetchProdis() {
                    this.filterProdi = '';
                    this.prodisList = [];
                    if (!this.filterFakultas) return;
                    fetch(`{{ url('monev-akademik/tashih/api/units') }}?faculty_id=${this.filterFakultas}`)
                        .then(res => res.json())
                        .then(data => { this.prodisList = data; })
                        .catch(err => console.error('Gagal load prodi', err));
                },

                fetchCourses() {
                    this.isLoading = true;
                    this.coursesList = [];
                    let url = `{{ url('monev-akademik/tashih/api/approved-courses') }}?`;
                    if (this.filterFakultas) url += `faculty_id=${this.filterFakultas}&`;
                    if (this.filterProdi) url += `prodi_id=${this.filterProdi}&`;
                    if (this.searchCourseQuery) url += `search=${encodeURIComponent(this.searchCourseQuery)}`;

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            this.coursesList = data.data !== undefined ? data.data : data;
                            this.isLoading = false;
                        })
                        .catch(err => {
                            console.error('Gagal load matkul', err);
                            this.isLoading = false;
                        });
                },

                openCourseQuestions(course) {
                    this.selectedBankCourseName = course.course_name;
                    this.bankViewMode = 'questions';
                    this.isLoading = true;
                    this.bankSoalList = [];
                    this.searchQueryBank = '';

                    fetch(`{{ url('monev-akademik/tashih/api/bank-soal') }}/${course.id}`)
                        .then(res => {
                            if (!res.ok) throw new Error('Network response was not ok');
                            return res.json();
                        })
                        .then(data => {
                            this.bankSoalList = data;
                            this.isLoading = false;
                        })
                        .catch(err => {
                            console.error('Gagal fetch soal', err);
                            this.isLoading = false;
                            if (typeof toastr !== 'undefined') toastr.error('Gagal memuat detail soal');
                        });
                },

                get filteredBankSoal() {
                    if (!this.bankSoalList || this.bankSoalList.length === 0) return [];
                    if (this.searchQueryBank === '') return this.bankSoalList;
                    return this.bankSoalList.filter(q =>
                        q.question_text && q.question_text.toLowerCase().includes(this.searchQueryBank.toLowerCase())
                    );
                },

                useQuestion(q) {
                    if (typeof addQuestionCard === 'function') {
                        addQuestionCard({
                            text: q.question_text,
                            cpmk: q.cpmk_id,
                            weight: '',
                            image_path: q.image_path || '',
                        });
                    }
                    this.openBankSoal = false;
                    this.searchQueryBank = '';
                    setTimeout(() => {
                        const mb = document.getElementById('modal-body-scroll');
                        if (mb) mb.scrollTo({ top: mb.scrollHeight, behavior: 'smooth' });
                    }, 100);
                },

                saveComment(orderNo) {
                    const commentInput = document.getElementById('comment-' + orderNo);
                    const message = commentInput.value;
                    if (!message) return;

                    fetch("{{ route('monevakademik.tashih.comment') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            proposal_id: this.selectedProposal.id,
                            order_no: orderNo,
                            message: message,
                        }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (!this.selectedProposal.logs) this.selectedProposal.logs = [];
                                this.selectedProposal.logs.push(data.log);
                                commentInput.value = '';
                                if (typeof toastr !== 'undefined') toastr.success('Catatan berhasil disimpan');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (typeof toastr !== 'undefined') toastr.error('Gagal menyimpan catatan');
                        });
                },

                async viewDetail(uuid, source) {
                    this.detailSource = source;
                    try {
                        const response = await fetch(`{{ url('monev-akademik/tashih/api/detail') }}/${uuid}`, {
                            headers: { 'Accept': 'application/json' },
                        });
                        if (!response.ok) throw new Error('Gagal memuat detail');
                        this.selectedProposal = await response.json();
                        this.openDetail = true;
                    } catch (error) {
                        console.error(error);
                        if (typeof toastr !== 'undefined') toastr.error('Gagal memuat detail pengajuan');
                    }
                },

                openEditModal() {
                    this.openDetail = false;
                    this.isEditMode = true;
                    this.editUuid = this.selectedProposal.uuid;
                    this.courseId = this.selectedProposal.course_id;
                    this.courseName = this.selectedProposal.course ? this.selectedProposal.course.course_name : '';

                    const questions = this.selectedProposal.exam_questions || this.selectedProposal.examQuestions || [];
                    let existingQuestions = questions.map(eq => ({
                        text: eq.question ? eq.question.question_text : '',
                        cpmk: eq.question ? eq.question.cpmk_id : [],
                        weight: eq.weight,
                        image_path: eq.question ? eq.question.image_path : '',
                    }));

                    this.openCreate = true;
                    setTimeout(() => {
                        if (typeof initCreateModal === 'function') {
                            initCreateModal(this.courseId, existingQuestions);
                        }
                    }, 100);
                },
            }));
        });
    </script>
@endsection