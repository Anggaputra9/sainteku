@extends('layouts.app') {{-- Sesuaikan dengan extend layout utama lu --}}

@section('content')
    <div x-data="bankSoalController()" x-cloak>

        {{-- Header Halaman --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-indigo-500"></i> Repositori Bank Soal
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-1"
                    x-text="viewMode === 'courses' ? 'Jelajahi mata kuliah dari berbagai program studi' : 'Arsip Paket Soal Ujian untuk ' + selectedCourseName">
                </p>
            </div>
        </div>

        {{-- Main Card Container --}}
        <div
            class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 min-h-[70vh] flex flex-col">

            {{-- AREA NAVIGASI & FILTER --}}
            <div class="mb-6 space-y-4">
                {{-- Tombol Back (Hanya muncul kalau lagi liat proposal) --}}
                <template x-if="viewMode === 'proposals'">
                    <button @click="viewMode = 'courses'"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-50 px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-100 hover:text-indigo-600 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:text-indigo-400">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Mata Kuliah
                    </button>
                </template>

                {{-- Filter Area (Hanya muncul di list Matkul) --}}
                <template x-if="viewMode === 'courses'">
                    <div class="bg-gray-50 dark:bg-gray-800/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">
                            Filter Unit / Program Studi
                        </label>
                        <select x-model="filterUnit" @change="fetchCourses()"
                            class="w-full md:w-1/2 rounded-lg border-[1.5px] border-gray-300 bg-white px-4 py-2.5 font-medium outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                            <option value="">-- Semua Unit / Program Studi --</option>
                            <template x-for="unit in unitsList" :key="unit.id">
                                <option :value="unit.id" x-text="unit.unit_name"></option>
                            </template>
                        </select>
                    </div>
                </template>
            </div>

            {{-- AREA KONTEN --}}
            <div class="flex-1">

                {{-- Loading State Global --}}
                <div x-show="isLoading" class="py-20 text-center text-gray-500">
                    <i class="fas fa-circle-notch fa-spin text-4xl mb-3 text-indigo-500"></i>
                    <p class="font-medium text-sm">Mengambil data dari server...</p>
                </div>

                {{-- MODE 1: LIST MATA KULIAH --}}
                <template x-if="!isLoading && viewMode === 'courses'">
                    <div class="space-y-5">
                        {{-- Pencarian Server-Side Matkul --}}
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" x-model="searchCourseQuery" @input.debounce.500ms="fetchCourses()"
                                placeholder="Cari nama mata kuliah..."
                                class="w-full rounded-xl border-[1.5px] border-gray-300 bg-gray-50 pl-11 pr-4 py-3 font-medium outline-none focus:border-indigo-500 focus:bg-white transition dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div x-show="coursesList.length === 0"
                                class="col-span-full py-12 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300 dark:bg-gray-800/50 dark:border-gray-700">
                                <i class="fas fa-box-open text-4xl mb-3 opacity-40"></i>
                                <p class="font-medium">Mata kuliah tidak ditemukan atau belum ada soal terdaftar.</p>
                            </div>

                            <template x-for="course in coursesList" :key="course.id">
                                <div @click="openCourseQuestions(course)"
                                    class="cursor-pointer group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-indigo-400 hover:shadow-md transition-all dark:border-gray-700 dark:bg-gray-800 flex flex-col h-full">
                                    <div
                                        class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                    </div>
                                    <span
                                        class="inline-block self-start px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-md mb-3 uppercase tracking-wide dark:bg-gray-700 dark:text-gray-300"
                                        x-text="course.unit_name"></span>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-base group-hover:text-indigo-600 transition leading-tight flex-1"
                                        x-text="course.course_name"></h4>
                                    <p class="text-xs text-gray-500 mt-4 font-bold flex items-center gap-1">
                                        Lihat Arsip Soal <i
                                            class="fas fa-arrow-right ml-auto group-hover:translate-x-1 text-indigo-500 transition-transform"></i>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- MODE 2: LIST PAKET SOAL (PROPOSAL) YANG UDAH DI ACC --}}
                <template x-if="!isLoading && viewMode === 'proposals'">
                    <div class="space-y-5">

                        <div x-show="proposalsList.length === 0"
                            class="py-12 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300 dark:bg-gray-800/50 dark:border-gray-700">
                            <i class="fas fa-file-excel text-4xl mb-3 opacity-40"></i>
                            <p class="font-medium">Belum ada paket soal yang disetujui untuk mata kuliah ini.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-for="(prop, index) in proposalsList" :key="prop.id">
                                <div
                                    class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-indigo-300 transition dark:border-gray-700 dark:bg-gray-800 flex flex-col justify-between h-full gap-4">

                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span
                                                class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-[11px] font-bold text-green-700 uppercase tracking-wide dark:bg-green-900/30 dark:text-green-400">
                                                <i class="fas fa-check-circle mr-1.5"></i> APPROVED
                                            </span>
                                            <span
                                                class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded dark:bg-gray-700 dark:text-gray-300"
                                                x-text="prop.exam_type"></span>
                                        </div>

                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                                            Arsip Soal <span x-text="prop.exam_type"></span>
                                        </h4>

                                        <div class="mt-3 space-y-1.5 text-sm text-gray-600 dark:text-gray-400 font-medium">
                                            <p><i class="fas fa-calendar-alt w-5 text-gray-400"></i> Periode: <span
                                                    x-text="prop.period ? prop.period.name : '-'"></span></p>
                                            <p><i class="fas fa-user-edit w-5 text-gray-400"></i> Dosen: <span
                                                    x-text="prop.creator ? prop.creator.name : '-'"></span></p>
                                        </div>
                                    </div>

                                    {{-- TOMBOL PRINT / DOWNLOAD PDF --}}
                                    <button type="button" @click="printPdf(prop.uuid)"
                                        class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 transition shadow-sm">
                                        <i class="fas fa-print"></i> Cetak / Buka PDF
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bankSoalController', () => ({
                viewMode: 'courses', // 'courses' atau 'proposals'
                isLoading: false,

                // Data Filter
                filterUnit: '',
                searchCourseQuery: '',

                // DATA ASLI DARI DATABASE VIA LARAVEL
                allCourses: @json($courses),
                unitsList: @json($units),

                coursesList: [],

                // Data State Proposal
                selectedCourseName: '',
                selectedCourseId: '',
                proposalsList: [],

                init() {
                    // Pas pertama kali buka, tampilkan semua matkul
                    this.fetchCourses();
                },

                // Fungsi buat Filter Matkul di Client Side (Biar cepet gak usah loading)
                fetchCourses() {
                    let filtered = this.allCourses;

                    // Filter berdasarkan pencarian nama matkul
                    if (this.searchCourseQuery.trim() !== '') {
                        const query = this.searchCourseQuery.toLowerCase();
                        filtered = filtered.filter(c =>
                            (c.course_name && c.course_name.toLowerCase().includes(query)) ||
                            (c.id && c.id.toLowerCase().includes(query))
                        );
                    }

                    // Filter berdasarkan dropdown Prodi/Unit
                    if (this.filterUnit !== '') {
                        filtered = filtered.filter(c => c.unit_id == this.filterUnit);
                    }

                    this.coursesList = filtered;
                },

                // TEMBAK DATA DATABASE BENERAN PAS MATKUL DIKLIK
                async openCourseQuestions(course) {
                    this.selectedCourseName = course.course_name;
                    this.selectedCourseId = course.id;
                    this.viewMode = 'proposals';
                    this.isLoading = true;
                    this.proposalsList = [];

                    try {
                        // Panggil Route API Proposal
                        const response = await fetch(`/monev-akademik/bank-soal/api/proposals/${course.id}`);
                        if (!response.ok) throw new Error('Gagal mengambil data dari server');

                        const data = await response.json();
                        this.proposalsList = data;
                    } catch (error) {
                        console.error("Error fetching proposals:", error);
                        alert("Terjadi kesalahan saat memuat arsip soal.");
                    } finally {
                        this.isLoading = false;
                    }
                },

                printPdf(uuid) {
                    // Otomatis buka tab baru ke route PDF Tashih
                    window.open(`/monev-akademik/tashih/print/${uuid}`, '_blank');
                }
            }));
        });
    </script>
@endsection