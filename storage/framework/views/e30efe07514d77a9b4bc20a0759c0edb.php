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
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">
    <div @click.outside="openDelete = false"
        class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-800 text-center">

        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-4 dark:bg-red-900/30">
            <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 dark:text-red-400"></i>
        </div>

        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Mata Kuliah?</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Apakah Anda yakin ingin menghapus MK <strong class="text-gray-800 dark:text-gray-200"
                x-text="deleteName"></strong>?
        </p>

        <form :action="deleteUrl" method="POST" @submit="isSubmitting = true">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="flex justify-center gap-3">
                <button type="button" @click="openDelete = false"
                    class="rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Batal</button>

                <button type="submit" x-bind:disabled="isSubmitting"
                    class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition disabled:opacity-50 flex items-center gap-2">
                    <span x-show="!isSubmitting">Ya, Hapus Data</span>
                    <span x-show="isSubmitting" style="display: none;"><i class="fa-solid fa-circle-notch fa-spin"></i>
                        Menghapus...</span>
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/courses/delete-modal.blade.php ENDPATH**/ ?>