<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('courseSelector', () => ({
            openSelectCourse: false,
            courseId: '',
            courseName: '',
            courses: @json($myCourses),
            search: '',
            currentPage: 1,
            perPage: '6',

            init() {
                this.$watch('search', () => this.currentPage = 1);
                this.$watch('perPage', () => this.currentPage = 1);
            },

            get filteredCourses() {
                return this.courses
                    .filter(c => c.course_name.toLowerCase().includes(this.search.toLowerCase()))
                    .sort((a, b) => (a.course_name || '').localeCompare(b.course_name || ''));
            },

            get perPageNumber() {
                return parseInt(this.perPage, 10) || 6;
            },

            get paginatedCourses() {
                let start = (this.currentPage - 1) * this.perPageNumber;
                return this.filteredCourses.slice(start, start + this.perPageNumber);
            },

            get totalPages() {
                return Math.ceil(this.filteredCourses.length / this.perPageNumber) || 1;
            },

            get paginationFrom() {
                if (this.filteredCourses.length === 0) return 0;
                return (this.currentPage - 1) * this.perPageNumber + 1;
            },

            get paginationTo() {
                return Math.min(this.currentPage * this.perPageNumber, this.filteredCourses.length);
            },

            closeModal() {
                this.openSelectCourse = false;
                this.$dispatch('tutup-modal-matkul');
            },

            resetModal() {
                this.courseId = '';
                this.courseName = '';
                this.search = '';
                this.currentPage = 1;
                this.perPage = '6';
            },

            selectCourse(course) {
                this.courseId = course.id;
                this.courseName = course.course_name;
            },

            continueToWorkspace() {
                if (!this.courseId) return;
                this.closeModal();
                this.$dispatch('lanjut-bikin-soal', { id: this.courseId, name: this.courseName });
            },
        }));
    });
</script>

<template x-teleport="#modal-root">
    <div x-data="courseSelector" @buka-modal-matkul.window="resetModal(); openSelectCourse = true;"
        x-show="openSelectCourse"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

        <div @click.away="closeModal()"
            class="relative w-full max-w-5xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

            {{-- HEADER --}}
            <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                        <i class="fa-solid fa-book text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">Pilih Mata Kuliah</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm">Langkah 1 — pilih mata kuliah untuk pengajuan soal ujian</p>
                    </div>
                </div>
                <div class="shrink-0 w-8 sm:w-9"></div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                {{-- TOOLBAR --}}
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="flex flex-nowrap items-center gap-3">
                        <div class="relative min-w-0 flex-1">
                            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" x-model="search" placeholder="Cari nama mata kuliah..."
                                class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-11 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <span class="hidden text-xs font-semibold text-gray-500 dark:text-gray-400 sm:inline">Tampilkan</span>
                            <select x-model="perPage" title="Jumlah data per halaman"
                                class="w-24 rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                <option value="6">6</option>
                                <option value="9">9</option>
                                <option value="12">12</option>
                                <option value="18">18</option>
                                <option value="24">24</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- COURSE GRID --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-indigo-500"></i> Daftar Mata Kuliah
                        </h4>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400" x-show="filteredCourses.length > 0">
                            <span x-text="filteredCourses.length"></span> matkul
                        </span>
                    </div>

                    <div class="p-5">
                        <div x-show="filteredCourses.length === 0" class="py-12 text-center text-gray-500">
                            <i class="fas fa-folder-open text-4xl mb-3 opacity-30"></i>
                            <p class="text-sm font-medium">Tidak ada mata kuliah yang cocok dengan filter.</p>
                        </div>

                        <div x-show="filteredCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <template x-for="course in paginatedCourses" :key="course.id">
                                <button type="button" @click="selectCourse(course)"
                                    class="text-left rounded-xl border p-4 transition-shadow relative flex flex-col h-full outline-none"
                                    :class="courseId === course.id
                                        ? 'border-indigo-300 bg-indigo-50/80 shadow-sm dark:bg-indigo-900/20 dark:border-indigo-700'
                                        : 'border-gray-200 bg-white hover:shadow-md dark:border-gray-700 dark:bg-[#0f172a]'">

                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-bold text-indigo-600 bg-indigo-100 dark:bg-indigo-900/40 dark:text-indigo-400"
                                            x-text="(course.course_name || 'M').charAt(0).toUpperCase()"></div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-snug line-clamp-2"
                                                :class="courseId === course.id ? 'text-indigo-700 dark:text-indigo-300' : ''"
                                                x-text="course.course_name"></h4>
                                        </div>
                                        <span x-show="courseId === course.id" class="shrink-0 text-indigo-500">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    </div>

                                    <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1 mt-auto pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-1.5 truncate">
                                            <i class="fas fa-building w-3 text-center text-indigo-400"></i>
                                            <span class="truncate" x-text="course.fakultas_name || '-'"></span>
                                        </div>
                                        <div class="flex items-center gap-1.5 truncate">
                                            <i class="fas fa-graduation-cap w-3 text-center text-indigo-400"></i>
                                            <span class="truncate" x-text="course.prodi_name || '-'"></span>
                                        </div>
                                    </div>
                                </button>
                            </template>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mt-5 pt-4 border-t border-gray-100 dark:border-gray-700"
                            x-show="filteredCourses.length > 0">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                Menampilkan <span class="font-bold text-gray-900 dark:text-white" x-text="paginationFrom"></span>
                                – <span class="font-bold text-gray-900 dark:text-white" x-text="paginationTo"></span>
                                dari <span class="font-bold text-gray-900 dark:text-white" x-text="filteredCourses.length"></span> matkul
                            </span>
                            <div class="flex gap-2" x-show="totalPages > 1">
                                <button type="button" @click="currentPage--" :disabled="currentPage === 1"
                                    class="inline-flex items-center gap-1 rounded-xl bg-gray-200 px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-700 dark:text-gray-200">
                                    <i class="fas fa-chevron-left"></i> Prev
                                </button>
                                <button type="button" @click="currentPage++" :disabled="currentPage === totalPages"
                                    class="inline-flex items-center gap-1 rounded-xl bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                    <button type="button" @click="closeModal()"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" :disabled="!courseId" @click="continueToWorkspace()"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                        Lanjutkan <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
</style>