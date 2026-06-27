<div x-data="{
    openEdit: false,
    url: '',
    roleData: { code: '', name: '', active: true }
}"
    @open-edit-modal.window="
        openEdit = true; 
        url = $event.detail.url;
        roleData = $event.detail;
    "
    x-show="openEdit"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

    <div @click.away="openEdit = false"
        class="relative w-full max-w-2xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all overflow-hidden">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Role</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Memperbarui hak akses: <span class="font-semibold text-amber-600 dark:text-amber-400" x-text="roleData.name"></span></p>
            </div>
            <button type="button" @click="openEdit = false"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form :action="url" method="POST" class="flex flex-col min-h-full">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Kode Role <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="role_code" x-model="roleData.code" required maxlength="5"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 uppercase dark:bg-gray-900 dark:text-white dark:border-gray-600">
                        <p class="mt-1.5 text-xs text-gray-500">Maksimal 5 karakter huruf kapital.</p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                            Nama Jabatan / Role <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="role_name" x-model="roleData.name" required maxlength="30"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:border-gray-600">
                    </div>

                    <div class="md:col-span-2 flex items-end pb-2">
                        <label class="relative inline-flex cursor-pointer items-center gap-3">
                            <input type="checkbox" name="is_active" value="1" class="peer sr-only" :checked="roleData.active">
                            <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700"></div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Role Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="shrink-0 flex flex-col sm:flex-row justify-end items-center border-t border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700 gap-3">
                <button type="button" @click="openEdit = false"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-amber-500 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-amber-600 transition">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MasterData\resources/views/roles/modal-edit.blade.php ENDPATH**/ ?>