<?php $__env->startSection('content'); ?>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="bankSoalApp()" x-init="init()" x-cloak>
        <div class="space-y-6">
            
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

            
            <div
                class="bg-blue-50 border-l-4 border-blue-500 p-4 dark:bg-gray-800 dark:border-blue-400 rounded-r-lg shadow-sm">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-info text-blue-500 text-xl mr-3"></i>
                    <p class="text-sm font-bold text-blue-700 dark:text-blue-400"
                        x-text="viewMode === 'courses' ? 'Jelajahi mata kuliah berdasarkan fakultas dan program studi.' : 'Menampilkan daftar paket soal (Proposal) yang telah disetujui untuk ' + selectedCourseName">
                    </p>
                </div>
            </div>

            
            <div
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 min-h-[60vh] flex flex-col">

                
                <div class="mb-6 space-y-4">
                    
                    <template x-if="viewMode === 'proposals'">
                        
                        <div class="flex w-full justify-end">
                            <button @click="viewMode = 'courses'"
                                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </button>
                        </div>
                    </template>

                    
                    <template x-if="viewMode === 'courses'">
                        <div
                            class="space-y-4 bg-gray-50 dark:bg-gray-700/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <div class="w-full">
                                    <label
                                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Fakultas</label>
                                    <select x-model="filterFakultas" @change="fetchProdis(); fetchCourses();"
                                        class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white px-4 py-2.5 font-medium outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                                        <option value="">-- Pilih Fakultas --</option>
                                        <template x-for="fak in facultiesList" :key="fak.id">
                                            <option :value="fak.id" x-text="fak.unit_name"></option>
                                        </template>
                                    </select>
                                </div>

                                
                                <div class="w-full">
                                    <label
                                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Program
                                        Studi</label>
                                    <select x-model="filterProdi" @change="fetchCourses()" :disabled="!filterFakultas"
                                        class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white px-4 py-2.5 font-medium outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:bg-gray-100 disabled:opacity-60 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:disabled:bg-gray-800">
                                        <option value="">-- Semua Prodi --</option>
                                        <template x-for="prd in prodisList" :key="prd.id">
                                            <option :value="prd.id" x-text="prd.unit_name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" x-model="searchCourseQuery" @input.debounce.500ms="fetchCourses()"
                                    placeholder="Cari nama mata kuliah..."
                                    class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white pl-11 pr-4 py-2.5 font-medium outline-none focus:border-indigo-500 transition dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                    </template>
                </div>

                
                <div class="flex-1">
                    
                    <div x-show="isLoading" class="py-20 text-center text-gray-500">
                        <i class="fas fa-circle-notch fa-spin text-4xl mb-3 text-indigo-500"></i>
                        <p class="font-medium text-sm tracking-widest uppercase">Memuat Data...</p>
                    </div>

                    
                    <template x-if="!isLoading && viewMode === 'courses'">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div x-show="coursesList.length === 0"
                                class="col-span-full py-20 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300 dark:bg-gray-800/50 dark:border-gray-700">
                                <i class="fas fa-box-open text-5xl mb-4 opacity-30"></i>
                                <p class="text-lg font-semibold italic">Mata kuliah tidak ditemukan.</p>
                            </div>

                            <template x-for="course in coursesList" :key="course.id">
                                <div @click="openCourseProposals(course)"
                                    class="cursor-pointer group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:border-indigo-400 hover:shadow-md transition-all dark:border-gray-700 dark:bg-gray-800 flex flex-col h-full border-l-4 border-l-transparent hover:border-l-indigo-500">
                                    <span
                                        class="inline-block self-start px-2.5 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-md mb-3 uppercase tracking-widest dark:bg-indigo-900/30 dark:text-indigo-400"
                                        x-text="course.unit_name || 'UNIVERSAL'"></span>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-lg group-hover:text-indigo-600 transition leading-tight flex-1"
                                        x-text="course.course_name"></h4>
                                    <div
                                        class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-400 uppercase" x-text="course.id"></span>
                                        <p class="text-xs text-indigo-500 font-bold flex items-center gap-1">
                                            Lihat Arsip <i
                                                class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    
                    <template x-if="!isLoading && viewMode === 'proposals'">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div x-show="proposalsList.length === 0"
                                class="col-span-full py-20 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300 dark:bg-gray-800/50 dark:border-gray-700">
                                <i class="fas fa-file-circle-xmark text-5xl mb-4 opacity-30"></i>
                                <p class="text-lg font-semibold italic">Belum ada arsip soal yang disetujui untuk mata
                                    kuliah ini.</p>
                            </div>

                            <template x-for="prop in proposalsList" :key="prop.id">
                                <div
                                    class="rounded-xl border-t-4 border-t-green-500 border border-gray-200 bg-white p-6 shadow-md hover:shadow-lg transition dark:border-gray-700 dark:bg-gray-800 flex flex-col justify-between h-full gap-6">
                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-[11px] font-bold text-green-700 uppercase tracking-widest dark:bg-green-900/30 dark:text-green-400">
                                                <i class="fas fa-check-circle mr-1.5"></i> APPROVED
                                            </span>
                                            <span
                                                class="text-xs font-black text-white bg-gray-800 px-3 py-1 rounded-full dark:bg-gray-200 dark:text-gray-900"
                                                x-text="prop.exam_type"></span>
                                        </div>
                                        <h4 class="text-xl font-black text-gray-900 dark:text-white leading-tight">Arsip
                                            Soal Ujian <span x-text="prop.exam_type"></span></h4>
                                        <div
                                            class="mt-4 space-y-3 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg border border-gray-100 dark:border-gray-600">
                                            <div
                                                class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-calendar-check w-6 text-indigo-500"></i>
                                                <span class="text-gray-400 mr-2">Periode:</span>
                                                <span x-text="prop.period ? prop.period.name : '-'"></span>
                                            </div>
                                            <div
                                                class="flex items-center text-sm font-medium text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-user-tie w-6 text-indigo-500"></i>
                                                <span class="text-gray-400 mr-2">Dosen Pengampu:</span>
                                                
                                                <span class="sm:hidden"
                                                    x-text="prop.creator ? prop.creator.name.split(' ')[0] : '-'"></span>

                                                
                                                <span class="hidden sm:inline"
                                                    x-text="prop.creator ? prop.creator.name : '-'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="printPdf(prop.uuid)"
                                        class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 text-sm font-bold text-white hover:bg-indigo-700 transition shadow-lg active:scale-95">
                                        <i class="fas fa-file-pdf text-lg"></i> Cetak / Buka PDF
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bankSoalApp', () => ({
                viewMode: 'courses',
                isLoading: false,

                // Filter State
                filterFakultas: '',
                filterProdi: '',
                searchCourseQuery: '',

                // Data Store API
                facultiesList: [],
                prodisList: [],
                coursesList: [],
                proposalsList: [],

                selectedCourseName: '',

                init() {
                    // Saat pertama load, tarik semua Fakultas dan Matkul Default
                    this.fetchFaculties();
                    this.fetchCourses();
                },

                // 1. Tarik Data Fakultas
                async fetchFaculties() {
                    try {
                        const res = await fetch(`<?php echo e(url('monev-akademik/tashih/api/units')); ?>`);
                        if (!res.ok) throw new Error(`Fakultas API error: ${res.status}`);
                        this.facultiesList = await res.json();
                    } catch (error) {
                        console.error('Error fetching faculties:', error);
                        this.facultiesList = [];
                    }
                },

                // 2. Tarik Data Prodi Berdasarkan Fakultas
                async fetchProdis() {
                    this.filterProdi = '';
                    this.prodisList = [];

                    if (!this.filterFakultas) {
                        this.prodisList = [];
                        return;
                    }

                    try {
                        const res = await fetch(`<?php echo e(url('monev-akademik/tashih/api/units')); ?>?faculty_id=${this.filterFakultas}`);
                        if (!res.ok) throw new Error(`Prodi API error: ${res.status}`);
                        this.prodisList = await res.json();
                    } catch (error) {
                        console.error('Error fetching prodis:', error);
                        this.prodisList = [];
                    }
                },

                // 3. Tarik Data Mata Kuliah Server-Side
                async fetchCourses() {
                    this.isLoading = true;
                    this.coursesList = [];

                    const url = new URL(`<?php echo e(url('monev-akademik/tashih/api/approved-courses')); ?>`);
                    if (this.filterFakultas) url.searchParams.append('faculty_id', this.filterFakultas);
                    if (this.filterProdi) url.searchParams.append('prodi_id', this.filterProdi);
                    if (this.searchCourseQuery.trim()) url.searchParams.append('search', this.searchCourseQuery.trim());

                    try {
                        const res = await fetch(url);
                        if (!res.ok) throw new Error(`Approved courses API error: ${res.status}`);
                        this.coursesList = await res.json();
                    } catch (error) {
                        console.error('Error fetching courses:', error);
                        this.coursesList = [];
                    } finally {
                        this.isLoading = false;
                    }
                },

                // 4. Tarik Paket Soal (Proposals)
                async openCourseProposals(course) {
                    this.selectedCourseName = course.course_name;
                    this.viewMode = 'proposals';
                    this.isLoading = true;
                    this.proposalsList = [];

                    try {
                        // FIX URL: Disamakan dengan route Bank Soal lu
                        const apiUrl = `<?php echo e(url('monev-akademik/bank-soal/api/proposals')); ?>/${course.id}`;
                        const response = await fetch(apiUrl);

                        if (!response.ok) throw new Error('Gagal mengambil data server');

                        this.proposalsList = await response.json();

                    } catch (error) {
                        console.error("Error fetching proposals:", error);
                        if (typeof toastr !== 'undefined') toastr.error('Gagal memuat arsip soal');
                    } finally {
                        this.isLoading = false;
                    }
                },

                // 5. Fungsi Cetak PDF
                printPdf(uuid) {
                    if (!uuid) {
                        console.error("UUID tidak ditemukan pada proposal ini!");
                        return;
                    }
                    // FIX URL PRINT: Menggunakan helper URL Laravel
                    window.open(`<?php echo e(url('monev-akademik/tashih/print')); ?>/${uuid}`, '_blank');
                }
            }));
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MonevAkademik\resources/views/bank-soal/index.blade.php ENDPATH**/ ?>