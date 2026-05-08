<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('courseSelector', () => ({
            openSelectCourse: false,
            courseId: '',
            courseName: '',

            // Data Matkul dari Controller
            courses: @json($myCourses),

            // State Filter & Search
            search: '',
            filterFakultas: '',
            filterProdi: '',

            // State Pagination
            currentPage: 1,
            perPage: 6, // Nampilin 6 matkul per halaman (bisa lu ganti)

            // Reset pagination kalau lagi ngetik search atau ganti filter
            init() {
                this.$watch('search', () => this.currentPage = 1);
                this.$watch('filterFakultas', () => {
                    this.filterProdi = ''; // Reset prodi kalau fakultas ganti
                    this.currentPage = 1;
                });
                this.$watch('filterProdi', () => this.currentPage = 1);
            },

            // Extract daftar Fakultas Unik buat Dropdown Filter
            get availableFakultas() {
                let faks = this.courses.map(c => ({ id: c.fakultas_id, name: c.fakultas_name }))
                    .filter(f => f.id != null);
                // Hapus duplikat
                return Array.from(new Map(faks.map(item => [item.id, item])).values());
            },

            // Extract daftar Prodi Unik (berdasarkan Fakultas yang dipilih)
            get availableProdi() {
                let prodis = this.courses.filter(c => this.filterFakultas === '' || c.fakultas_id === this.filterFakultas)
                    .map(c => ({ id: c.prodi_id, name: c.prodi_name }));
                return Array.from(new Map(prodis.map(item => [item.id, item])).values());
            },

            // Data matkul setelah difilter
            get filteredCourses() {
                return this.courses.filter(c => {
                    let matchSearch = c.course_name.toLowerCase().includes(this.search.toLowerCase()) ||
                        c.id.toLowerCase().includes(this.search.toLowerCase());
                    let matchFak = this.filterFakultas === '' || c.fakultas_id === this.filterFakultas;
                    let matchProdi = this.filterProdi === '' || c.prodi_id === this.filterProdi;

                    return matchSearch && matchFak && matchProdi;
                });
            },

            // Data matkul yang udah difilter + dipotong berdasarkan halaman
            get paginatedCourses() {
                let start = (this.currentPage - 1) * this.perPage;
                let end = start + this.perPage;
                return this.filteredCourses.slice(start, end);
            },

            get totalPages() {
                return Math.ceil(this.filteredCourses.length / this.perPage);
            }
        }))
    })
</script>


<div x-data="courseSelector" @buka-modal-matkul.window="openSelectCourse = true; courseId = ''; courseName = '';"
    x-show="openSelectCourse"
    class="fixed inset-0 z-[999998] flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div
        class="relative w-full max-w-5xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        <div
            class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Langkah 1: Pilih Mata Kuliah</h3>
                <p class="text-sm text-gray-500 mt-1">Pilih mata kuliah yang akan Anda buatkan soal ujiannya.</p>
            </div>
            <button @click="openSelectCourse = false" type="button"
                class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-yellow-400 px-5 py-2.5 text-sm font-bold text-yellow-900 shadow-sm hover:bg-yellow-500 transition-all dark:bg-yellow-500 dark:text-yellow-950 dark:hover:bg-yellow-400">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>

        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-3 rounded-xl bg-gray-50 p-4 dark:bg-gray-900/50">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i
                        class="fas fa-search"></i></span>
                <input type="text" x-model="search" placeholder="Cari kode/nama matkul..."
                    class="w-full rounded-lg border-0 py-2.5 pl-10 pr-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:ring-gray-600">
            </div>

            <div>
                <select x-model="filterFakultas"
                    class="w-full rounded-lg border-0 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:ring-gray-600">
                    <option value="">Semua Fakultas</option>
                    <template x-for="fak in availableFakultas" :key="fak.id">
                        <option :value="fak.id" x-text="fak.name"></option>
                    </template>
                </select>
            </div>

            <div>
                <select x-model="filterProdi"
                    class="w-full rounded-lg border-0 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:ring-gray-600">
                    <option value="">Semua Program Studi</option>
                    <template x-for="prodi in availableProdi" :key="prodi.id">
                        <option :value="prodi.id" x-text="prodi.name"></option>
                    </template>
                </select>
            </div>
        </div>

        <div class="min-h-[300px]">
            <div x-show="filteredCourses.length === 0"
                class="flex h-full flex-col items-center justify-center py-10 text-gray-400">
                <i class="fas fa-folder-open text-5xl mb-3 opacity-20"></i>
                <p>Tidak ada mata kuliah yang cocok dengan filter.</p>
            </div>

            <div x-show="filteredCourses.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 custom-scrollbar pr-2 mb-2">
                <template x-for="course in paginatedCourses" :key="course.id">
                    <div @click="courseId = course.id; courseName = course.course_name"
                        class="cursor-pointer rounded-xl border-2 p-5 transition-all relative flex flex-col h-full"
                        :class="courseId === course.id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30' : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-800'">

                        <div class="flex justify-between items-start mb-2">
                            <span
                                class="inline-flex rounded-md bg-gray-100 px-2 py-1 text-xs font-bold text-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                x-text="course.id"></span>

                            <span x-show="courseId === course.id" class="text-blue-500"><i
                                    class="fas fa-check-circle"></i></span>
                        </div>

                        <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-snug mb-3 flex-grow"
                            :class="courseId === course.id ? 'text-blue-700 dark:text-blue-400' : ''"
                            x-text="course.course_name">
                        </h4>

                        <div class="text-[11px] text-gray-500 dark:text-gray-400 space-y-1">
                            <div class="flex items-center gap-1.5"><i class="fas fa-building w-3"></i> <span
                                    class="truncate" x-text="course.fakultas_name || '-'"></span></div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-graduation-cap w-3"></i> <span
                                    class="truncate" x-text="course.prodi_name || '-'"></span></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div
            class="flex flex-col sm:flex-row justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-700 gap-4">

            <div class="flex items-center gap-2 text-sm" x-show="totalPages > 1">
                <button @click="currentPage--" :disabled="currentPage === 1"
                    class="rounded border border-gray-300 px-3 py-1.5 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:hover:bg-gray-700">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span class="text-gray-600 dark:text-gray-400">
                    Halaman <span class="font-bold text-gray-900 dark:text-white" x-text="currentPage"></span> dari
                    <span x-text="totalPages"></span>
                </span>
                <button @click="currentPage++" :disabled="currentPage === totalPages"
                    class="rounded border border-gray-300 px-3 py-1.5 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:hover:bg-gray-700">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div x-show="totalPages <= 1"></div>

            <button :disabled="!courseId" type="button"
                @click="openSelectCourse = false; $dispatch('lanjut-bikin-soal', courseId);"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-bold text-white transition disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-700 shadow-md">
                Lanjutkan ke Form <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>