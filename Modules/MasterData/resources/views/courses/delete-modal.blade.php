<div x-data="{
        openDelete: false,
        isSubmitting: false,
        deleteUrl: '',
        deleteName: ''
    }" @open-delete-modal.window="
        openDelete = true;
        deleteUrl = $event.detail.url;
        deleteName = $event.detail.name;
    " x-show="openDelete"
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

        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-4 dark:bg-red-900/30">
            <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 dark:text-red-400"></i>
        </div>

        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Mata Kuliah?</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Apakah Anda yakin ingin menghapus MK <strong class="text-gray-800 dark:text-gray-200" x-text="deleteName"></strong>?
        </p>

        <div class="flex flex-col sm:flex-row gap-3">
            <button type="button" @click="openDelete = false"
                class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                <i class="fas fa-times"></i> Batal
            </button>

            <form :action="deleteUrl" method="POST" @submit="isSubmitting = true" class="m-0 p-0 w-full">
                @csrf
                @method('DELETE')
                <button type="submit" x-bind:disabled="isSubmitting"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-red-700 transition disabled:opacity-50">
                    <span x-show="!isSubmitting"><i class="fa-solid fa-trash"></i> Ya, Hapus Data</span>
                    <span x-show="isSubmitting"><i class="fa-solid fa-circle-notch fa-spin"></i> Menghapus...</span>
                </button>
            </form>
        </div>
    </div>
</div>