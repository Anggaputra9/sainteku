<div x-show="openBankSoal"
    class="fixed inset-0 z-[9999999] flex items-center justify-center overflow-y-auto bg-black/60 p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">

    <div
        class="relative w-full max-w-4xl transform rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all flex flex-col max-h-[85vh]">

        <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-archive text-indigo-500"></i> Repositori Bank Soal
                </h3>
                <p class="text-sm text-gray-500 mt-1" x-text="courseId + ' - ' + courseName"></p>
            </div>
            {{-- Tombol X Dihapus --}}
        </div>

        <div class="mb-4">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari kata kunci butir soal..."
                    class="block w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:placeholder-gray-400">
            </div>
        </div>

        <div class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar">
            <div x-show="isLoading" class="py-12 text-center text-gray-500">
                <i class="fas fa-circle-notch fa-spin text-3xl mb-3 text-indigo-500"></i>
                <p class="text-sm font-medium">Mencari soal yang telah di-Approve...</p>
            </div>

            <div x-show="!isLoading && filteredBankSoal.length === 0"
                class="py-12 text-center text-gray-500 dark:text-gray-400" x-cloak>
                <div
                    class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 mb-3 dark:bg-gray-700">
                    <i class="fas fa-box-open text-2xl opacity-60"></i>
                </div>
                <p class="font-medium">Tidak ada soal matang yang tersedia.</p>
                <p class="text-sm mt-1 opacity-70">Pastikan sudah ada pengajuan sebelumnya yang di-Approve.</p>
            </div>

            <template x-for="q in filteredBankSoal" :key="q.id">
                <div
                    class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm hover:border-indigo-300 hover:shadow-md transition dark:border-gray-700 dark:bg-gray-800/50 flex flex-col sm:flex-row justify-between items-start gap-4">
                    <div class="flex-1">
                        <span
                            class="inline-flex items-center rounded bg-indigo-50 px-2 py-1 text-[11px] font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 mb-2 uppercase tracking-wide">
                            <i class="fa-solid fa-bullseye mr-1 opacity-70"></i> CPMK: <span x-text="q.cpmk_id"
                                class="ml-1"></span>
                        </span>
                        <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed font-medium"
                            x-text="q.question_text"></p>
                    </div>
                    <button type="button" @click="useQuestion(q)"
                        class="shrink-0 w-full sm:w-auto inline-flex justify-center items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition shadow-sm focus:ring-4 focus:ring-indigo-100">
                        Pilih & Gunakan <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </template>
        </div>

        <div class="mt-4 flex justify-end border-t border-gray-100 pt-4 dark:border-gray-700">
            <button @click="openBankSoal = false" type="button"
                class="inline-flex justify-center items-center gap-2 rounded-lg bg-yellow-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>

    </div>
</div>