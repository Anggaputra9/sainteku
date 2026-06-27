<div x-data="{
    openPerm: false,
    url: '',
    roleName: '',
    assigned: [],
    isChecked(modulId, permId) {
        return this.assigned.includes(modulId + '-' + permId);
    }
}"
    @open-perm-modal.window="
        openPerm = true;
        url = $event.detail.url;
        roleName = $event.detail.name;
        assigned = $event.detail.assigned;
    "
    x-show="openPerm"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openPerm = false"
        class="relative w-full max-w-5xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all overflow-hidden">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Matriks Hak Akses</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Atur perizinan untuk: <span class="font-bold text-purple-600 dark:text-purple-400" x-text="roleName"></span></p>
            </div>
            <button type="button" @click="openPerm = false"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form :action="url" method="POST" class="flex flex-col min-h-full">
            <?php echo csrf_field(); ?>

            <div class="flex-1 overflow-y-auto p-6">
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-4 font-semibold border-b dark:border-gray-700">Modul / Fitur</th>
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="px-2 py-4 text-center font-semibold border-b dark:border-gray-700" title="<?php echo e($perm->permission_name); ?>">
                                        <?php echo e($perm->permission_code); ?>

                                        <div class="text-[10px] text-gray-400 font-normal mt-1"><?php echo e($perm->permission_name); ?></div>
                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white border-r dark:border-gray-700">
                                        <?php echo e($modul->description); ?>

                                        <div class="text-xs text-gray-500 font-normal"><?php echo e($modul->module_code); ?></div>
                                    </td>
                                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="px-2 py-3 text-center border-r border-gray-100 dark:border-gray-700/50">
                                            <label class="flex justify-center cursor-pointer">
                                                <input type="checkbox" name="permissions[<?php echo e($modul->id); ?>][]"
                                                    value="<?php echo e($perm->id); ?>"
                                                    :checked="isChecked('<?php echo e($modul->id); ?>', '<?php echo e($perm->id); ?>')"
                                                    class="h-5 w-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700">
                                            </label>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="shrink-0 flex flex-col sm:flex-row justify-end items-center border-t border-gray-200 bg-white px-6 py-4 z-20 dark:bg-gray-800 dark:border-gray-700 gap-3">
                <button type="button" @click="openPerm = false"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-purple-600 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-purple-700 transition">
                    <i class="fas fa-save"></i> Simpan Hak Akses
                </button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MasterData\resources/views/roles/modal-permissions.blade.php ENDPATH**/ ?>