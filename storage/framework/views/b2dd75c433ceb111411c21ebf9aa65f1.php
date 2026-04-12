<?php $__env->startSection('content'); ?>
    <div class="w-full max-w-full 2xl:p-10" x-data="bankSoalApp()" x-init="init()" x-cloak>
        <div class="space-y-6 flex flex-col min-h-[calc(100vh-8rem)]">

            
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-indigo-500"></i> Repositori Bank Soal
                    </h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Monev Akademik /</li>
                            <li class="text-indigo-600 dark:text-indigo-400">Bank Soal</li>
                        </ol>
                    </nav>
                </div>
            </div>

            
            <div class="min-h-[60vh] flex flex-col relative">

                
                <div class="mb-6 space-y-4">
                    <div
                        class="space-y-4 bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="w-full">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Fakultas</label>
                                <select x-model="filterFakultas" @change="fetchProdis(); fetchCourses(1);"
                                    class="w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 px-4 py-2.5 font-medium outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                                    <option value="">-- Semua Fakultas --</option>
                                    <template x-for="fak in facultiesList" :key="fak.id">
                                        <option :value="fak.id" x-text="fak.unit_name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="w-full">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Program
                                    Studi</label>
                                <select x-model="filterProdi" @change="fetchCourses(1)" :disabled="!filterFakultas"
                                    class="w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 px-4 py-2.5 font-medium outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-gray-100 disabled:opacity-60 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                                    <option value="">-- Semua Prodi --</option>
                                    <template x-for="prd in prodisList" :key="prd.id">
                                        <option :value="prd.id" x-text="prd.unit_name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <div class="relative w-full flex-1">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" x-model="searchCourseQuery" @input.debounce.500ms="fetchCourses(1)"
                                    placeholder="Cari kode atau nama mata kuliah..."
                                    class="w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 pl-11 pr-4 py-2.5 font-medium outline-none focus:border-indigo-500 transition dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                            </div>

                            <div class="w-full sm:w-auto shrink-0 flex items-center gap-3">
                                <label
                                    class="text-sm font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">Tampilkan:</label>
                                <select x-model="perPage" @change="fetchCourses(1)"
                                    class="w-full sm:w-auto rounded-lg border-[1.5px] border-gray-300 bg-gray-50 px-3 py-2.5 font-medium outline-none focus:border-indigo-500 transition cursor-pointer dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm">
                                    <option value="10">10 Baris</option>
                                    <option value="25">25 Baris</option>
                                    <option value="50">50 Baris</option>
                                    <option value="75">75 Baris</option>
                                    <option value="100">100 Baris</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                
                <div class="flex-1 relative">

                    <div x-show="isLoading"
                        class="py-20 text-center text-gray-500 absolute inset-0 z-10 bg-gray-50/70 dark:bg-gray-900/70 backdrop-blur-sm rounded-xl flex flex-col justify-center items-center">
                        <i class="fas fa-circle-notch fa-spin text-4xl mb-3 text-indigo-600 dark:text-indigo-400"></i>
                        <p class="font-bold text-sm tracking-widest text-indigo-800 dark:text-indigo-300 uppercase">Memuat
                            Data...</p>
                    </div>

                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                                <thead
                                    class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Kode MK</th>
                                        <th class="px-6 py-4 font-semibold min-w-[200px]">Nama Mata Kuliah</th>
                                        <th class="px-6 py-4 font-semibold min-w-[150px]">Prodi Pengampu</th>
                                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">

                                    <tr x-show="coursesList.length === 0 && !isLoading">
                                        <td colspan="4"
                                            class="px-6 py-16 text-center text-gray-500 bg-gray-50/50 dark:bg-gray-800/50">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-box-open text-4xl mb-3 opacity-30"></i>
                                                <p class="text-base font-medium italic">Mata kuliah tidak ditemukan.</p>
                                            </div>
                                        </td>
                                    </tr>

                                    <template x-for="course in coursesList" :key="course.id">
                                        <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/30">
                                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white whitespace-nowrap"
                                                x-text="course.id"></td>
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white leading-tight"
                                                x-text="course.course_name"></td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-block px-2.5 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-md uppercase tracking-widest dark:bg-indigo-900/30 dark:text-indigo-400"
                                                    x-text="course.unit_name || 'UNIVERSAL'"></span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <button type="button" @click="openCourseProposals(course)"
                                                    class="inline-flex items-center gap-1.5 rounded-md bg-indigo-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-600 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">
                                                    <i class="fas fa-folder-open"></i> Lihat
                                                </button>
                                            </td>
                                        </tr>
                                    </template>

                                </tbody>
                            </table>
                        </div>

                        
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex flex-col sm:flex-row justify-between items-center gap-4"
                            x-show="pagination.total > 0 && !isLoading">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Menampilkan <span class="font-bold text-gray-900 dark:text-white"
                                    x-text="pagination.from"></span>
                                - <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.to"></span>
                                dari <span class="font-bold text-gray-900 dark:text-white" x-text="pagination.total"></span>
                                data
                            </span>
                            <div class="flex gap-2 shrink-0">
                                <button @click="changePage(pagination.current_page - 1)"
                                    :disabled="!pagination.prev_page_url"
                                    class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                                <button @click="changePage(pagination.current_page + 1)"
                                    :disabled="!pagination.next_page_url"
                                    class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php echo $__env->make('monevakademik::bank-soal.modal-proposals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bankSoalApp', () => ({
                isLoading: false,

                // Filter Utama (Matkul)
                filterFakultas: '',
                filterProdi: '',
                searchCourseQuery: '',
                perPage: '10',

                // Data Store Utama
                facultiesList: [],
                prodisList: [],
                coursesList: [],
                pagination: {},

                // STATE MODAL PROPOSAL
                isModalOpen: false,
                isModalLoading: false,
                selectedCourseId: null,
                selectedCourseName: '',

                // Filter Modal
                filterExamType: '',
                filterPeriod: '',
                periodsList: [],
                proposalsList: [],

                init() {
                    this.fetchFaculties();
                    this.fetchCourses();
                    this.fetchPeriods(); // Tarik list periode di awal
                },

                async fetchFaculties() {
                    try {
                        const res = await fetch(`<?php echo e(url('monev-akademik/tashih/api/units')); ?>`);
                        if (res.ok) this.facultiesList = await res.json();
                    } catch (e) { console.error(e); }
                },

                async fetchProdis() {
                    this.filterProdi = '';
                    this.prodisList = [];
                    if (!this.filterFakultas) return;

                    try {
                        const res = await fetch(`<?php echo e(url('monev-akademik/tashih/api/units')); ?>?faculty_id=${this.filterFakultas}`);
                        if (res.ok) this.prodisList = await res.json();
                    } catch (e) { console.error(e); }
                },

                async fetchCourses(page = 1) {
                    this.isLoading = true;
                    this.coursesList = [];

                    const url = new URL(`<?php echo e(url('monev-akademik/tashih/api/approved-courses')); ?>`);
                    url.searchParams.append('page', page);
                    url.searchParams.append('per_page', this.perPage);

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
                            next_page_url: result.next_page_url
                        };
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.isLoading = false;
                    }
                },

                changePage(page) {
                    this.fetchCourses(page);
                    window.scrollTo({ top: 150, behavior: 'smooth' });
                },

                // ----------------------------------------------------
                // FUNGSI MODAL & FILTER ARSIP
                // ----------------------------------------------------
                async fetchPeriods() {
                    try {
                        // FIX: Panggil API getPeriods yang baru dibuat di controller
                        const res = await fetch(`<?php echo e(url('monev-akademik/bank-soal/api/periods')); ?>`);
                        if (res.ok) this.periodsList = await res.json();
                    } catch (e) { console.error('Gagal memuat list periode:', e); }
                },

                // Trigger Pas Klik Tombol "Lihat Arsip" di Tabel
                openCourseProposals(course) {
                    this.selectedCourseId = course.id;
                    this.selectedCourseName = course.course_name;

                    // Reset filter modal
                    this.filterExamType = '';
                    this.filterPeriod = '';

                    this.isModalOpen = true;
                    this.fetchProposals();
                },

                // Fungsi Tarik Data Arsip dengan Filter
                async fetchProposals() {
                    if (!this.selectedCourseId) return;

                    this.isModalLoading = true;
                    this.proposalsList = [];

                    try {
                        const url = new URL(`<?php echo e(url('monev-akademik/bank-soal/api/proposals')); ?>/${this.selectedCourseId}`);

                        // Masukin parameter filter ke URL API
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
                    window.open(`<?php echo e(url('monev-akademik/tashih/print')); ?>/${uuid}`, '_blank');
                }
            }));
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sainteku\Modules/MonevAkademik\resources/views/bank-soal/index.blade.php ENDPATH**/ ?>