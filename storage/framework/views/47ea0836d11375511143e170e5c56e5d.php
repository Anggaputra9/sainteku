<template x-teleport="#modal-root">
    <div x-data="{ openCreate: false }" @open-create-modal.window="openCreate = true" x-show="openCreate"
        class="app-modal-overlay fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

        <div @click.away="openCreate = false"
            class="relative w-full max-w-2xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

            <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                        <i class="fa-solid fa-building text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                    </div>
                    <div class="min-w-0 leading-tight">
                        <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">Tambah Unit Baru</h3>
                        <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm">Daftarkan struktur organisasi seperti fakultas, prodi, atau lembaga</p>
                    </div>
                </div>
                <div class="shrink-0 w-8 sm:w-9"></div>
            </div>

            <form action="<?php echo e(route('masterdata.units.store')); ?>" method="POST" class="flex flex-col flex-1 overflow-hidden">
                <?php echo csrf_field(); ?>
                <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-building text-indigo-500"></i> Informasi Unit
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Nama Unit <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="unit_name" required maxlength="100"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                    placeholder="Contoh: Fakultas Sains dan Teknologi" value="<?php echo e(old('unit_name')); ?>">
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    Tipe Unit <span class="text-red-500">*</span>
                                </label>
                                <select name="unit_type_id" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Pilih Level Unit --</option>
                                    <?php $__currentLoopData = $unitTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->id); ?>" <?php echo e(old('unit_type_id') == $type->id ? 'selected' : ''); ?>>
                                            <?php echo e($type->description ?? 'Tipe ' . $type->id); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Induk Unit (Opsional)</label>
                                <select name="unit_parent"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Tidak Ada (Sebagai Induk Tertinggi) --</option>
                                    <?php $__currentLoopData = $parentUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($parent->id); ?>" <?php echo e(old('unit_parent') == $parent->id ? 'selected' : ''); ?>>
                                            <?php echo e($parent->id); ?> - <?php echo e($parent->unit_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="relative inline-flex cursor-pointer items-center gap-3">
                                    <input type="checkbox" name="is_active" value="1" class="peer sr-only" checked>
                                    <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700"></div>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Aktifkan Unit Sekarang</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                    <div class="flex flex-row flex-nowrap items-center justify-end gap-2 sm:gap-3">
                        <button type="button" @click="openCreate = false"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-indigo-700 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-save"></i> Simpan Unit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template><?php /**PATH /mnt/volume_sgp1_1781186006004/projects/sainteku/Modules/MasterData/resources/views/units/modal-create.blade.php ENDPATH**/ ?>