

<?php $__env->startSection('content'); ?>
    
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Data Infrastruktur & Inventaris</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kelola data fasilitas fisik seperti ruangan, gedung, dan barang inventaris di lingkungan Sainteku.
            </p>
        </div>
        <div>
            <button @click="$dispatch('open-create-modal')"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 sm:w-auto">
                <i class="fas fa-plus"></i>
                Tambah Data
            </button>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div
            class="mb-6 rounded-lg border border-emerald-100 bg-emerald-50 px-4 py-3 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <p class="text-sm font-medium"><?php echo e(session('success')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div
            class="mb-6 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-red-800 shadow-sm dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                <p class="text-sm font-bold"><?php echo e(session('error')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50/50 text-left text-sm dark:bg-gray-700/30">
                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-gray-900 dark:text-white">Kode / ID
                        </th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-gray-900 dark:text-white">Nama
                            Ruangan / Barang</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-gray-900 dark:text-white">Kategori
                            Tipe</th>
                        <th
                            class="px-6 py-4 text-center font-semibold uppercase tracking-wider text-gray-900 dark:text-white">
                            Kuantitas</th>
                        <th
                            class="px-6 py-4 text-center font-semibold uppercase tracking-wider text-gray-900 dark:text-white">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    
                    <?php $__empty_1 = true; $__currentLoopData = $infrastructures ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="bg-transparent transition hover:bg-gray-50 dark:hover:bg-gray-700/20">
                            
                            <td class="px-6 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                                #<?php echo e($item->id); ?>

                            </td>

                            
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    <?php echo e($item->description); ?>

                                </div>
                                <div
                                    class="mt-1 flex items-center gap-1.5 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                    <i class="fa-regular fa-clock"></i>
                                    Ditambahkan: <?php echo e($item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d M Y') : '-'); ?>

                                </div>
                            </td>

                            
                            <td class="px-6 py-4 text-sm">
                                <span
                                    class="inline-flex rounded-md bg-purple-50 px-2.5 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/30 dark:text-purple-300">
                                    <?php echo e($item->type_description ?? 'Tipe ' . $item->inventory_type); ?>

                                </span>
                            </td>

                            
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex h-8 min-w-[32px] items-center justify-center rounded-full bg-gray-100 px-2.5 text-sm font-bold text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    <?php echo e($item->quantity); ?>

                                </span>
                            </td>

                            
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                        @click="$dispatch('open-edit-modal', { 
                                            url: '<?php echo e(route('masterdata.infrastructures.update', $item->id)); ?>',
                                            id: '<?php echo e($item->id); ?>',
                                            description: '<?php echo e(addslashes($item->description)); ?>',
                                            type: '<?php echo e($item->inventory_type); ?>',
                                            quantity: '<?php echo e($item->quantity); ?>'
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                                        title="Ubah Data">
                                        <i class="fa-solid fa-pencil"></i> Ubah
                                    </button>

                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', { 
                                            url: '<?php echo e(route('masterdata.infrastructures.destroy', $item->id)); ?>',
                                            name: '<?php echo e(addslashes($item->description)); ?>'
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                                        title="Hapus Data">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                        <i class="fas fa-boxes-stacked text-3xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">Belum Ada Data
                                        Infrastruktur</h4>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan data
                                        ruangan atau inventaris pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <?php if(isset($infrastructures) && $infrastructures->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($infrastructures->links()); ?>

        </div>
    <?php endif; ?>

    
    <?php echo $__env->make('masterdata::infrastructures.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('masterdata::infrastructures.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('masterdata::infrastructures.delete-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MasterData\resources/views/infrastructures/index.blade.php ENDPATH**/ ?>