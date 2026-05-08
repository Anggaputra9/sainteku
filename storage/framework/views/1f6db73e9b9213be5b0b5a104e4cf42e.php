<div x-data="{ openDelete: false, url: '', itemName: '' }"
    @open-delete-modal.window="
        openDelete = true;
        url = $event.detail.url;
        itemName = $event.detail.name;
    "
    x-show="openDelete"
    class="fixed inset-0 z-[9999999] flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak style="display: none;">

    <div @click.away="openDelete = false" x-show="openDelete"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-md transform rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all text-center">

        
        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-600 dark:text-red-400"></i>
        </div>

        
        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Hapus Infrastruktur?</h3>
        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            Apakah Anda yakin ingin menghapus data <strong class="text-gray-800 dark:text-gray-200" x-text="itemName"></strong>? <br> Tindakan ini bersifat permanen dan tidak dapat dibatalkan.
        </p>

        
        <form :action="url" method="POST" class="flex flex-col gap-3 sm:flex-row sm:justify-center">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            
            <button type="button" @click="openDelete = false"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 shadow-sm transition hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 sm:w-auto">
                Batal
            </button>
            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-red-700 focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 sm:w-auto">
                <i class="fa-solid fa-trash"></i> Ya, Hapus Data
            </button>
        </form>
    </div>
</div><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/infrastructures/delete-modal.blade.php ENDPATH**/ ?>