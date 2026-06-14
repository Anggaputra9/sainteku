<template x-teleport="#modal-root">
    <div x-show="testingId"
        class="app-modal-overlay fixed inset-0 z-[10000001] flex items-center justify-center p-4 sm:p-6 bg-gray-900/50 backdrop-blur-sm"
        x-transition.opacity
        x-cloak>

        <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-[#0f172a] ring-1 ring-gray-900/5 dark:ring-gray-700"
            @click.stop>

            <div class="flex items-center gap-3 border-b border-gray-200 bg-white px-5 py-4 dark:border-gray-700 dark:bg-[#1e293b]">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50 dark:border-indigo-800/50 dark:bg-indigo-900/30">
                    <i class="fa-solid fa-brain text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-base">Mengetes Koneksi AI</h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="testingLabel || 'Konfigurasi AI'"></p>
                </div>
            </div>

            <div class="space-y-4 bg-slate-50 px-5 py-6 text-center dark:bg-[#0f172a]">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border-2 border-indigo-100 dark:border-indigo-800/60">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Sedang menghubungi provider...</p>
                    <p class="text-xs leading-relaxed text-gray-500 dark:text-gray-400">
                        Model reasoning bisa butuh beberapa detik. Mohon tunggu hingga proses selesai.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>