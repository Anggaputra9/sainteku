<div x-data="{ openCreate: false }"
    @open-create-modal.window="openCreate = true"
    x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

    <div @click.away="openCreate = false"
        class="relative w-full max-w-3xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Unit Baru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan struktur organisasi baru seperti fakultas, prodi, atau lembaga.</p>
            </div>
            <button type="button" @click="openCreate = false"
                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>

        <form action="<?php echo e(route('masterdata.units.store')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Nama Unit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="unit_name" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Contoh: Fakultas Sains dan Teknologi" value="<?php echo e(old('unit_name')); ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tipe Unit <span class="text-red-500">*</span>
                    </label>
                    <select name="unit_type_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Level Unit --</option>
                        <?php $__currentLoopData = $unitTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>" <?php echo e(old('unit_type_id') == $type->id ? 'selected' : ''); ?>>
                                <?php echo e($type->description ?? 'Tipe ' . $type->id); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Induk Unit (Opsional)</label>
                    <select name="unit_parent"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Tidak Ada (Sebagai Induk Tertinggi) --</option>
                        <?php $__currentLoopData = $parentUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($parent->id); ?>" <?php echo e(old('unit_parent') == $parent->id ? 'selected' : ''); ?>>
                                <?php echo e($parent->id); ?> - <?php echo e($parent->unit_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="md:col-span-2 flex items-end pb-2">
                    <label class="relative inline-flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" class="peer sr-only" checked>
                        <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700"></div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Aktifkan Unit Sekarang</span>
                    </label>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i>
                    Simpan Unit
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/units/modal-create.blade.php ENDPATH**/ ?>