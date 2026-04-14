<div x-data="{
    openPerm: false,
    url: '',
    roleName: '',
    assigned: [], // Menyimpan array seperti ['1-1', '1-2', '2-1']
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
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

    <div @click.away="openPerm = false"
        class="relative my-auto w-full max-w-5xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Matriks Hak Akses</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Atur perizinan (CRUD-AVE) untuk jabatan: <span
                        class="font-bold text-purple-600 dark:text-purple-400" x-text="roleName"></span></p>
            </div>
            <button type="button" @click="openPerm = false"
                class="inline-flex items-center gap-2 rounded-lg bg-yellow-400 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-yellow-500 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>

        <form :action="url" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>
            

            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-4 font-semibold border-b dark:border-gray-700">Modul / Fitur</th>
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="px-2 py-4 text-center font-semibold border-b dark:border-gray-700"
                                    title="<?php echo e($perm->permission_name); ?>">
                                    <?php echo e($perm->permission_code); ?>

                                    <div class="text-[10px] text-gray-400 font-normal mt-1"><?php echo e($perm->permission_name); ?>

                                    </div>
                                </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td
                                    class="px-4 py-3 font-medium text-gray-900 dark:text-white border-r dark:border-gray-700">
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

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition dark:focus:ring-blue-900">
                    <i class="fas fa-save"></i>
                    Simpan Hak Akses
                </button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/roles/modal-permissions.blade.php ENDPATH**/ ?>