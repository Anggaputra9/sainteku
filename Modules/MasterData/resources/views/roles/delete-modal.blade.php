<template x-teleport="#modal-root">
    <div x-data="{ openDelete: false, url: '', roleName: '' }"
        @open-delete-modal.window="openDelete = true; url = $event.detail.url; roleName = $event.detail.name"
        x-show="openDelete"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/50"
        x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

        <div @click.away="openDelete = false"
            class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 overflow-hidden">

            <div class="border-b border-gray-200 bg-white px-4 py-3 sm:px-6 sm:py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-red-100 bg-red-50 dark:border-red-800/50 dark:bg-red-900/30">
                        <i class="fa-solid fa-triangle-exclamation text-sm text-red-600 dark:text-red-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-lg">Konfirmasi Hapus</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="roleName"></p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus role <span class="font-bold text-gray-900 dark:text-white" x-text="roleName"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <p class="text-xs text-amber-700 dark:text-amber-400" x-show="false"></p>

                <div class="flex flex-row flex-nowrap items-center justify-end gap-2 pt-2">
                    <button type="button" @click="openDelete = false"
                        class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-5 sm:py-2.5 sm:text-sm transition">
                        Batal
                    </button>
                    <form :action="url" method="POST" class="m-0 shrink-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-red-700 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>