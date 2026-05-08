<?php $__env->startSection('content'); ?>
    <div class="mx-auto">
        <div class="space-y-6">

            
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Data Infrastruktur & Inventaris
                    </h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Master Data /</li>
                            <li class="text-blue-600 dark:text-blue-400">Infrastruktur</li>
                        </ol>
                    </nav>
                </div>

                
                <div x-data="{ openCreate: false }">
                    <button @click="$dispatch('open-create-modal')"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fas fa-plus"></i>
                        Tambah Infrastruktur
                    </button>

                    <?php echo $__env->make('masterdata::infrastructures.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div
                    class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                    <div
                        class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-green-800 dark:text-green-400">Sukses!</h5>
                        <p class="text-sm text-green-700 dark:text-green-500"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div
                    class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                    <div
                        class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-red-800 dark:text-red-400">Peringatan!</h5>
                        <p class="text-sm text-red-700 dark:text-red-500"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="rounded-lg">
                <form method="GET" class="flex flex-wrap items-center justify-between gap-3">

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        
                        <div class="relative w-full sm:max-w-xs">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 dark:text-gray-500">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                placeholder="Cari nama atau kode..."
                                class="w-full rounded-md border border-gray-300 bg-gray-50 py-1.5 pl-9 pr-3 text-sm text-gray-900 focus:border-teal-500 focus:ring-teal-500 outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition">
                        </div>
                        
                        <select name="per_page" onchange="this.form.submit()"
                            class="rounded-md border border-gray-300 bg-gray-50 py-1.5 pl-3 pr-8 text-sm text-gray-900 focus:border-teal-500 focus:ring-teal-500 outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white transition cursor-pointer shadow-sm">
                            <option value="10" <?php echo e(request('per_page') == 10 ? 'selected' : ''); ?>>10 Baris</option>
                            <option value="25" <?php echo e(request('per_page') == 25 ? 'selected' : ''); ?>>25 Baris</option>
                            <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>50 Baris</option>
                            <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100 Baris</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition shadow-sm">
                            <i class="fa-solid fa-filter text-xs"></i> Filter
                        </button>

                        
                        <a href="<?php echo e(route('masterdata.infrastructures.index')); ?>"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-md bg-teal-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-teal-700 focus:ring-4 focus:ring-teal-300 dark:focus:ring-teal-800 transition shadow-sm">
                            <i class="fa-solid fa-rotate text-xs"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50/50 text-left text-sm dark:bg-gray-700/30">
                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-gray-900 dark:text-white">Kode</th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-gray-900 dark:text-white">
                            Nama & Merk </th>
                        <th class="px-6 py-4 font-semibold uppercase tracking-wider text-gray-900 dark:text-white">Tipe &
                            Unit</th>
                        <th
                            class="px-6 py-4 text-center font-semibold uppercase tracking-wider text-gray-900 dark:text-white">
                            Stok & Harga</th>
                        <th
                            class="px-6 py-4 text-center font-semibold uppercase tracking-wider text-gray-900 dark:text-white">
                            Status</th>
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
                                <div class="flex items-center gap-4">
                                    
                                    <div class="h-12 w-12 flex-shrink-0">
                                        <?php if(!empty($item->photo)): ?>
                                            <button type="button" 
                                                @click="$dispatch('open-image-modal', { url: '<?php echo e(asset('storage/' . $item->photo)); ?>', title: '<?php echo e(addslashes($item->item_name)); ?>' })"
                                                class="group relative block h-12 w-12 overflow-hidden rounded-lg shadow-sm ring-1 ring-gray-200 transition focus:outline-none focus:ring-2 focus:ring-blue-500 dark:ring-gray-700"
                                                title="Lihat Gambar Penuh">
                                                <img src="<?php echo e(asset('storage/' . $item->photo)); ?>" alt="Foto Barang" class="h-full w-full object-cover transition duration-300 group-hover:scale-110 group-hover:opacity-75">
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 transition-opacity duration-300 group-hover:opacity-100 bg-black/30">
                                                    <i class="fa-solid fa-magnifying-glass text-white drop-shadow-md"></i>
                                                </div>
                                            </button>
                                        <?php else: ?>
                                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                                                <i class="fa-solid fa-box-open text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                            <?php echo e($item->item_name); ?>

                                        </div>
                                        <div
                                            class="mt-1 flex items-center gap-1.5 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                            <i class="fa-solid fa-tag"></i>
                                            <?php echo e($item->brand ?: 'Tanpa Merk'); ?>

                                        </div>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-6 py-4 text-sm">
                                <span
                                    class="inline-flex mb-1.5 rounded-md bg-purple-50 px-2 py-1 text-[11px] font-semibold text-purple-700 ring-1 ring-inset ring-purple-700/10 dark:bg-purple-900/30 dark:text-purple-300">
                                    <?php echo e($item->type_description ?? 'Tipe ' . $item->inventory_type); ?>

                                </span>
                                <div
                                    class="flex items-center gap-1.5 text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                    <i class="fa-regular fa-building"></i>
                                    <?php echo e($item->unit_name ?: 'Universitas (Umum)'); ?>

                                </div>
                            </td>

                            
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex h-7 items-center justify-center rounded-full bg-gray-100 px-3 text-sm font-bold text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    <?php echo e($item->stock); ?> <span
                                        class="ml-1 text-[10px] font-medium text-gray-500"><?php echo e($item->unit_measure ?: 'PCS'); ?></span>
                                </span>
                                <div class="mt-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                                    Rp <?php echo e(number_format($item->price ?? 0, 0, ',', '.')); ?>

                                </div>
                            </td>

                            
                            <td class="px-6 py-4 text-center text-sm">
                                <?php if($item->status == 1): ?>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Baik
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/30 dark:text-red-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Rusak
                                    </span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                        @click="$dispatch('open-edit-modal', { 
                                            url: '<?php echo e(route('masterdata.infrastructures.update', $item->id)); ?>',
                                            id: '<?php echo e($item->id); ?>',
                                            item_name: '<?php echo e(addslashes($item->item_name)); ?>',
                                            type: '<?php echo e($item->inventory_type); ?>',
                                            brand: '<?php echo e(addslashes($item->brand ?? '')); ?>',
                                            unit_measure: '<?php echo e($item->unit_measure ?? ''); ?>',
                                            stock: '<?php echo e($item->stock); ?>',
                                            price: '<?php echo e($item->price ?? 0); ?>',
                                            status: '<?php echo e($item->status ?? 1); ?>',
                                            unit_id: '<?php echo e($item->unit_id ?? ''); ?>',
                                            description: '<?php echo e(addslashes($item->description ?? '')); ?>'
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                                        title="Ubah Data">
                                        <i class="fa-solid fa-pencil"></i> Ubah
                                    </button>

                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', { 
                                            url: '<?php echo e(route('masterdata.infrastructures.destroy', $item->id)); ?>',
                                            name: '<?php echo e(addslashes($item->item_name)); ?>'
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
                            
                            <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
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
            <?php echo e($infrastructures->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php echo $__env->make('masterdata::infrastructures.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('masterdata::infrastructures.delete-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div x-data="{ openImage: false, imageUrl: '', imageTitle: '' }"
        @open-image-modal.window="openImage = true; imageUrl = $event.detail.url; imageTitle = $event.detail.title"
        x-show="openImage"
        class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak style="display: none;">

        <div @click.away="openImage = false" x-show="openImage"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="relative flex w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-transparent transition-all max-h-[90vh]">
            
            
            <div class="absolute right-0 top-0 z-10 flex w-full items-center justify-between bg-gradient-to-b from-black/70 to-transparent p-4">
                <h3 class="text-lg font-bold text-white drop-shadow-md" x-text="imageTitle"></h3>
                <button type="button" @click="openImage = false"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md transition hover:bg-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>

            
            <div class="relative flex flex-1 items-center justify-center p-2">
                <img :src="imageUrl" :alt="imageTitle" class="max-h-[85vh] max-w-full rounded-lg object-contain drop-shadow-2xl">
            </div>
            
            
            <div class="absolute bottom-4 left-0 w-full text-center">
                <span class="rounded-full bg-black/50 px-3 py-1 text-xs font-medium text-white/80 backdrop-blur-md">
                    Klik di luar gambar untuk menutup
                </span>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/infrastructures/index.blade.php ENDPATH**/ ?>