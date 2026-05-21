<div x-data="{ openDelete: false, url: '', roleName: '' }"
    @open-delete-modal.window="openDelete = true; url = $event.detail.url; roleName = $event.detail.name"
    x-show="openDelete"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openDelete = false"
        class="relative w-full max-w-md transform rounded-2xl bg-white p-6 text-center shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        <button @click="openDelete = false"
            class="absolute right-4 top-4 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/30">
            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
        </div>

        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            Apakah Anda yakin ingin menghapus role <span class="font-bold text-gray-900 dark:text-white" x-text="roleName"></span>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div class="flex flex-col sm:flex-row gap-3">
            <button type="button" @click="openDelete = false"
                class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                <i class="fas fa-times"></i> Batal
            </button>

            <form :action="url" method="POST" class="m-0 p-0 w-full">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-red-700 transition">
                    <i class="fa-solid fa-trash-can"></i> Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>