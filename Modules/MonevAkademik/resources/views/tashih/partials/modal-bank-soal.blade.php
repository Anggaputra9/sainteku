<template x-teleport="#modal-root">
    <div x-show="openBankSoal"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

        <div @click.away="openBankSoal = false; bankFilterFabOpen = false"
            class="relative w-full max-w-5xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

            {{-- HEADER --}}
            <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                        <i class="fa-solid fa-layer-group text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">Repositori Bank Soal Universal</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm"
                            x-text="bankViewMode === 'courses' ? 'Jelajahi mata kuliah dari berbagai program studi' : 'Pilih butir soal dari ' + selectedBankCourseName"></p>
                    </div>
                </div>
                <div class="shrink-0 w-8 sm:w-9"></div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a]">

                {{-- NAV: Kembali ke daftar matkul --}}
                <template x-if="bankViewMode === 'questions'">
                    <button type="button" @click="bankViewMode = 'courses'"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-600 shadow-sm hover:border-indigo-300 hover:text-indigo-600 transition dark:border-gray-700 dark:bg-[#1e293b] dark:text-gray-300 dark:hover:text-indigo-400">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Mata Kuliah
                    </button>
                </template>

                {{-- TOOLBAR (mode matkul) --}}
                <template x-if="bankViewMode === 'courses'">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="flex flex-nowrap items-center gap-3">
                            <div class="relative min-w-0 flex-1">
                                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" x-model="searchCourseQuery" @input.debounce.400ms="fetchCourses()"
                                    placeholder="Cari nama mata kuliah..."
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-11 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                    </div>
                </template>

                {{-- LOADING --}}
                <div x-show="isLoading" class="rounded-xl border border-gray-200 bg-white py-16 text-center shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <i class="fas fa-circle-notch fa-spin text-4xl mb-3 text-indigo-500"></i>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Mengambil data dari server...</p>
                </div>

                {{-- MODE: DAFTAR MATA KULIAH --}}
                <div x-show="!isLoading && bankViewMode === 'courses'" class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-indigo-500"></i> Daftar Mata Kuliah
                        </h4>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400" x-show="coursesList.length > 0">
                            <span x-text="coursesList.length"></span> matkul
                        </span>
                    </div>

                    <div class="p-5">
                        <div x-show="coursesList.length === 0" class="py-12 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-3 opacity-30"></i>
                            <p class="text-sm font-medium">Belum ada soal ter-approve atau mata kuliah tidak ditemukan.</p>
                        </div>

                        <div x-show="coursesList.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <template x-for="course in coursesList" :key="course.id">
                                <button type="button" @click="openCourseQuestions(course)"
                                    class="text-left rounded-xl border-2 border-gray-200 bg-white p-4 transition-all relative flex flex-col h-full outline-none hover:border-indigo-300 hover:shadow-sm focus:ring-4 focus:ring-indigo-500/20 dark:border-gray-700 dark:bg-[#0f172a] dark:hover:border-indigo-700">

                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-bold text-indigo-600 bg-indigo-100 dark:bg-indigo-900/40 dark:text-indigo-400"
                                            x-text="(course.course_name || 'M').charAt(0).toUpperCase()"></div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-snug line-clamp-2"
                                                x-text="course.course_name"></h4>
                                        </div>
                                    </div>

                                    <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1 mt-auto pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-1.5 truncate">
                                            <i class="fas fa-building w-3 text-center text-indigo-400"></i>
                                            <span class="truncate" x-text="course.unit_name || 'UNIVERSAL'"></span>
                                        </div>
                                    </div>

                                    <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 mt-3 flex items-center gap-1">
                                        Lihat Koleksi Soal <i class="fas fa-arrow-right ml-auto"></i>
                                    </p>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- MODE: DAFTAR SOAL --}}
                <div x-show="!isLoading && bankViewMode === 'questions'" class="space-y-5">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="relative">
                            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" x-model="searchQueryBank" placeholder="Cari isi pertanyaan..."
                                class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-11 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-list-ol text-indigo-500"></i> Koleksi Soal
                            </h4>
                        </div>

                        <div class="p-5 space-y-3">
                            <div x-show="filteredBankSoal.length === 0" class="py-12 text-center text-gray-500">
                                <i class="fas fa-search-minus text-4xl mb-3 opacity-30"></i>
                                <p class="text-sm font-medium">Tidak ada soal yang cocok dengan pencarian.</p>
                            </div>

                            <template x-for="q in filteredBankSoal" :key="q.id">
                                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-[#0f172a] flex flex-col sm:flex-row justify-between items-start gap-4">
                                    <div class="flex-1 min-w-0">
                                        <span class="inline-flex items-center rounded-md bg-indigo-50 px-2.5 py-1 text-[10px] font-bold text-indigo-700 mb-2 uppercase dark:bg-indigo-900/30 dark:text-indigo-400">
                                            <i class="fa-solid fa-bullseye mr-1.5"></i>
                                            CPMK: <span x-text="q.cpmk ? q.cpmk.name : q.cpmk_id" class="ml-1"></span>
                                        </span>
                                        <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed" x-text="q.question_text"></p>
                                        <template x-if="q.image_path">
                                            <span class="inline-flex items-center gap-1.5 mt-2 px-2 py-1 bg-gray-100 rounded text-[10px] font-bold text-gray-600 uppercase dark:bg-gray-700 dark:text-gray-300">
                                                <i class="fas fa-image text-indigo-500"></i> Terdapat Lampiran Gambar
                                            </span>
                                        </template>
                                    </div>
                                    <button type="button" @click="useQuestion(q)"
                                        class="shrink-0 w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 transition shadow-sm">
                                        Gunakan <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-end gap-2 sm:gap-4">
                    <button type="button" @click="openBankSoal = false; bankFilterFabOpen = false"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- FAB FILTER (mode matkul) --}}
        <div x-show="openBankSoal && bankViewMode === 'courses'" x-cloak
            class="pointer-events-auto fixed z-[10000001] flex flex-col items-end gap-3"
            style="bottom: 1.5rem; right: 1.5rem; left: auto;"
            @click.stop
            @click.away="bankFilterFabOpen = false">
            <div x-show="bankFilterFabOpen" x-cloak
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
                    <button type="button" x-show="activeBankFilterCount > 0" @click="resetBankFilters()"
                        class="text-[11px] font-bold uppercase tracking-wide text-red-600 hover:text-red-700 dark:text-red-400">
                        Reset
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Fakultas</label>
                        <select x-model="filterFakultas" @change="fetchProdis(); fetchCourses()"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua Fakultas</option>
                            <template x-for="fak in facultiesList" :key="fak.id">
                                <option :value="fak.id" x-text="fak.unit_name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Program Studi</label>
                        <select x-model="filterProdi" @change="fetchCourses()" :disabled="!filterFakultas"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 disabled:opacity-60 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua Prodi</option>
                            <template x-for="prd in prodisList" :key="prd.id">
                                <option :value="prd.id" x-text="prd.unit_name"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            <button type="button" @click="bankFilterFabOpen = !bankFilterFabOpen"
                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 outline-none transition hover:bg-indigo-700 hover:shadow-xl"
                :title="activeBankFilterCount > 0 && !bankFilterFabOpen ? activeBankFilterCount + ' filter aktif' : (bankFilterFabOpen ? 'Tutup filter' : 'Buka filter')">
                <span class="relative inline-block leading-none">
                    <i class="fa-solid text-lg transition-transform duration-200"
                        :class="bankFilterFabOpen ? 'fa-xmark' : 'fa-sliders'"></i>
                    <span x-show="activeBankFilterCount > 0 && !bankFilterFabOpen" x-cloak
                        style="position:absolute;top:-3px;right:-6px;width:8px;height:8px;border-radius:9999px;background:#ef4444;border:2px solid #fff;box-shadow:0 1px 2px rgba(0,0,0,.25);pointer-events:none;"
                        aria-hidden="true"></span>
                </span>
            </button>
        </div>
    </div>
</template>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
</style>