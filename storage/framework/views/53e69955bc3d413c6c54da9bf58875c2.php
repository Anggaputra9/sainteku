<div x-show="isModalOpen"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">

    <div @click.away="isModalOpen = false"
        class="flex flex-col w-full max-w-5xl max-h-[90vh] rounded-2xl bg-white shadow-2xl dark:bg-gray-800 overflow-hidden">

        
        <div
            class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Arsip Soal: <span
                        class="text-indigo-600 dark:text-indigo-400" x-text="selectedCourseName"></span></h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menampilkan daftar paket soal yang telah
                    disetujui.</p>
            </div>

            
            <button @click="isModalOpen = false" type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </button>
        </div>

        
        <div class="flex-1 overflow-y-auto p-6 relative">

            
            <div
                class="mb-6 flex flex-col sm:flex-row gap-4 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="w-full sm:w-1/2">
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Jenis
                        Ujian</label>
                    <select x-model="filterExamType" @change="fetchProposals()"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium outline-none focus:border-indigo-500 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="">Semua Jenis Ujian</option>
                        <option value="UTS">UTS</option>
                        <option value="UAS">UAS</option>
                    </select>
                </div>
                <div class="w-full sm:w-1/2">
                    <label
                        class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 dark:text-gray-400">Periode
                        Akademik</label>
                    <select x-model="filterPeriod" @change="fetchProposals()"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium outline-none focus:border-indigo-500 transition dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="">Semua Periode</option>
                        <template x-for="per in periodsList" :key="per.id">
                            <option :value="per.id" x-text="per.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            
            <div x-show="isModalLoading"
                class="py-16 text-center text-gray-500 flex flex-col justify-center items-center">
                <i class="fas fa-circle-notch fa-spin text-4xl mb-3 text-indigo-600 dark:text-indigo-400"></i>
                <p class="font-bold text-sm uppercase">Mencari Arsip...</p>
            </div>

            
            <div x-show="!isModalLoading"
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead
                            class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Arsip Ujian</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Periode</th>
                                <th class="px-6 py-4 font-semibold min-w-[150px]">Dosen Pengampu</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">

                            
                            <tr x-show="proposalsList.length === 0">
                                <td colspan="5"
                                    class="px-6 py-16 text-center text-gray-500 bg-gray-50/50 dark:bg-gray-800/50">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-file-circle-xmark text-4xl mb-3 opacity-30"></i>
                                        <p class="text-base font-medium italic">Tidak ada arsip soal yang sesuai dengan
                                            filter.</p>
                                    </div>
                                </td>
                            </tr>

                            
                            <template x-for="prop in proposalsList" :key="prop.id">
                                <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-900 dark:text-white text-base">Arsip <span
                                                x-text="prop.exam_type"></span></span>
                                    </td>
                                    <td class="px-6 py-4 font-medium" x-text="prop.period ? prop.period.name : '-'">
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium"
                                        x-text="prop.creator ? prop.creator.name : '-'"></td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-bold text-green-700 uppercase tracking-widest dark:bg-green-900/30 dark:text-green-400">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button" @click="printPdf(prop.uuid)"
                                            class="inline-flex items-center gap-1.5 rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">
                                            <i class="fas fa-file-pdf"></i> Cetak
                                        </button>
                                    </td>
                                </tr>
                            </template>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MonevAkademik\resources/views/bank-soal/modal-proposals.blade.php ENDPATH**/ ?>