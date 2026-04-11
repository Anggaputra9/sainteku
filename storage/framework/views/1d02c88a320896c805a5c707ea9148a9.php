<div x-show="openBankSoal"
    class="fixed inset-0 z-[9999999] flex items-center justify-center overflow-y-auto bg-black/60 p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">

    <div
        class="relative w-full max-w-5xl transform rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all flex flex-col max-h-[90vh]">

        
        <div class="mb-5 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-indigo-500"></i> Repositori Bank Soal Universal
                </h3>
                <p class="text-sm font-medium text-gray-500 mt-1"
                    x-text="bankViewMode === 'courses' ? 'Jelajahi mata kuliah dari berbagai program studi' : 'Pilih butir soal dari ' + selectedBankCourseName">
                </p>
            </div>
            <button @click="openBankSoal = false"
                class="text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 rounded-lg p-2 transition dark:bg-gray-700 dark:hover:bg-red-900/30">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        
        <div class="mb-5 space-y-4">

            
            <template x-if="bankViewMode === 'questions'">
                <button @click="bankViewMode = 'courses'"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-50 px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-100 hover:text-indigo-600 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:text-indigo-400">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Mata Kuliah
                </button>
            </template>

            
            <template x-if="bankViewMode === 'courses'">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-800/50 p-5 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Fakultas</label>
                        
                        <select x-model="filterFakultas" @change="fetchProdis(); fetchCourses()"
                            class="w-full rounded-lg border-[1.5px] border-gray-300 bg-white px-4 py-2.5 font-medium outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                            <option value="">-- Semua Fakultas --</option>
                            <template x-for="fak in facultiesList" :key="fak.id">
                                <option :value="fak.id" x-text="fak.unit_name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
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
            </template>

        </div>

        
        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">

            
            <div x-show="isLoading" class="py-16 text-center text-gray-500">
                <i class="fas fa-circle-notch fa-spin text-4xl mb-3 text-indigo-500"></i>
                <p class="font-medium text-sm">Mengambil data dari server...</p>
            </div>

            
            <template x-if="!isLoading && bankViewMode === 'courses'">
                <div class="space-y-5">

                    
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
                            <p class="font-medium">Belum ada soal ter-Approve atau mata kuliah tidak ditemukan.</p>
                        </div>

                        <template x-for="course in coursesList" :key="course.id">
                            <div @click="openCourseQuestions(course)"
                                class="cursor-pointer group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-indigo-400 hover:shadow-md transition-all dark:border-gray-700 dark:bg-gray-800">
                                <div
                                    class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                                <span
                                    class="inline-block px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-md mb-3 uppercase tracking-wide dark:bg-gray-700 dark:text-gray-300"
                                    x-text="course.unit_name"></span>
                                <h4 class="font-bold text-gray-900 dark:text-white text-base group-hover:text-indigo-600 transition leading-tight"
                                    x-text="course.course_name"></h4>
                                <p class="text-xs text-gray-500 mt-4 font-bold flex items-center gap-1">
                                    Lihat Koleksi Soal <i
                                        class="fas fa-arrow-right ml-auto group-hover:translate-x-1 text-indigo-500 transition-transform"></i>
                                </p>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            
            <template x-if="!isLoading && bankViewMode === 'questions'">
                <div class="space-y-4">

                    
                    <div class="relative mb-6">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" x-model="searchQuery" placeholder="Cari isi pertanyaan..."
                            class="w-full rounded-xl border-[1.5px] border-gray-300 bg-gray-50 pl-11 pr-4 py-3 font-medium outline-none focus:border-indigo-500 focus:bg-white transition dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                    </div>

                    <div x-show="filteredBankSoal.length === 0"
                        class="py-12 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300 dark:bg-gray-800/50 dark:border-gray-700">
                        <i class="fas fa-search-minus text-4xl mb-3 opacity-40"></i>
                        <p class="font-medium">Tidak ada soal yang cocok dengan pencarian.</p>
                    </div>

                    <template x-for="q in filteredBankSoal" :key="q.id">
                        <div
                            class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-indigo-300 transition dark:border-gray-700 dark:bg-gray-800 flex flex-col sm:flex-row justify-between items-start gap-5">
                            <div class="flex-1">
                                <span
                                    class="inline-flex items-center rounded-md bg-indigo-50 px-2.5 py-1 text-[11px] font-bold text-indigo-700 mb-3 uppercase dark:bg-indigo-900/30 dark:text-indigo-400">
                                    <i class="fa-solid fa-bullseye mr-1.5"></i> CPMK: <span
                                        x-text="q.cpmk ? q.cpmk.name : q.cpmk_id" class="ml-1"></span>
                                </span>
                                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed font-medium"
                                    x-text="q.question_text"></p>
                                <template x-if="q.image_path">
                                    <span
                                        class="inline-flex items-center gap-1.5 mt-3 px-2 py-1 bg-gray-100 rounded text-[10px] font-bold text-gray-600 uppercase tracking-wide dark:bg-gray-700 dark:text-gray-300">
                                        <i class="fas fa-image text-indigo-500"></i> Terdapat Lampiran Gambar
                                    </span>
                                </template>
                            </div>
                            <button type="button" @click="useQuestion(q)"
                                class="shrink-0 w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 transition shadow-sm">
                                Gunakan <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </template>

        </div>
    </div>
</div><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MonevAkademik\resources/views/tashih/partials/modal-bank-soal.blade.php ENDPATH**/ ?>