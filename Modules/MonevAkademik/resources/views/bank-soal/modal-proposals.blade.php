<template x-teleport="#modal-root">
    <div x-show="isModalOpen"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

        <div @click.away="isModalOpen = false"
            class="relative w-full max-w-5xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

            <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                        <i class="fa-solid fa-folder-open text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">Arsip Bank Soal</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="selectedCourseName"></p>
                    </div>
                </div>
                <div class="shrink-0 w-8 sm:w-9"></div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-filter text-indigo-500"></i> Filter Arsip
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Jenis Ujian</label>
                            <select x-model="filterExamType" @change="fetchProposals()"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                <option value="">Semua Jenis</option>
                                <option value="UTS">UTS</option>
                                <option value="UAS">UAS</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Periode Akademik</label>
                            <select x-model="filterPeriod" @change="fetchProposals()"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                                <option value="">Semua Periode</option>
                                <template x-for="per in periodsList" :key="per.id">
                                    <option :value="per.id" x-text="per.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <div x-show="isModalLoading" class="py-16 text-center text-gray-500">
                    <i class="fas fa-circle-notch fa-spin text-4xl mb-3 text-indigo-600"></i>
                    <p class="text-sm font-semibold text-indigo-800 dark:text-indigo-400">Mencari arsip...</p>
                </div>

                <div x-show="!isModalLoading" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                            <thead class="text-xs uppercase bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Arsip Ujian</th>
                                    <th class="px-6 py-4 font-semibold">Periode</th>
                                    <th class="px-6 py-4 font-semibold">Dosen Pengampu</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr x-show="proposalsList.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-file-circle-xmark text-3xl mb-3 opacity-30"></i><br>
                                        Tidak ada arsip soal yang sesuai filter.
                                    </td>
                                </tr>
                                <template x-for="prop in proposalsList" :key="prop.id">
                                    <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/30">
                                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                            Arsip <span x-text="prop.exam_type"></span>
                                        </td>
                                        <td class="px-6 py-4 font-medium" x-text="prop.period ? prop.period.name : '-'"></td>
                                        <td class="px-6 py-4 font-medium" x-text="prop.creator ? prop.creator.name : '-'"></td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Disetujui
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <button type="button" @click="printPdf(prop.uuid)"
                                                class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-indigo-700 transition">
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

            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                <div class="flex justify-end">
                    <button type="button" @click="isModalOpen = false"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 transition">
                        <i class="fa-solid fa-times"></i> Tutup
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