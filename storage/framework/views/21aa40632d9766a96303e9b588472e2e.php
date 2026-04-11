<?php $__env->startSection('content'); ?>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="space-y-6">

            
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Pengajuan Peminjaman
                    </h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Manajemen Infrastruktur /</li>
                            <li class="text-blue-600 dark:text-blue-400">Peminjaman Fasilitas</li>
                        </ol>
                    </nav>
                </div>

                
                <div x-data="{ openCreate: false }">
                    <button @click="openCreate = true"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fa-solid fa-calendar-plus"></i>
                        Ajukan Peminjaman
                    </button>

                    
                    <?php echo $__env->make('manajementinfrastruktur::pengajuan.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                    <div class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-green-800 dark:text-green-400">Sukses!</h5>
                        <p class="text-sm text-green-700 dark:text-green-500"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if(session('error') || $errors->any()): ?>
                <div class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                    <div class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-red-800 dark:text-red-400">Peringatan!</h5>
                        <p class="text-sm text-red-700 dark:text-red-500">
                            <?php echo e(session('error') ?? 'Gagal memproses pengajuan. Periksa kembali form isian Anda.'); ?>

                        </p>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <a href="<?php echo e(route('manajementinfrastruktur.pengajuan.index')); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'all' ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'); ?>">
                    Semua Riwayat
                </a>
                <a href="<?php echo e(route('manajementinfrastruktur.pengajuan.index', ['status' => 'pending'])); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-amber-600 border border-amber-200 hover:bg-amber-50 dark:bg-gray-800 dark:border-amber-900/50 dark:hover:bg-amber-900/30'); ?>">
                    <i class="fa-solid fa-clock mr-1"></i> Menunggu
                </a>
                <a href="<?php echo e(route('manajementinfrastruktur.pengajuan.index', ['status' => 'approved'])); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'approved' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-green-600 border border-green-200 hover:bg-green-50 dark:bg-gray-800 dark:border-green-900/50 dark:hover:bg-green-900/30'); ?>">
                    <i class="fa-solid fa-check-circle mr-1"></i> Disetujui
                </a>
                <a href="<?php echo e(route('manajementinfrastruktur.pengajuan.index', ['status' => 'rejected'])); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'rejected' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50 dark:hover:bg-red-900/30'); ?>">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Ditolak
                </a>
                <a href="<?php echo e(route('manajementinfrastruktur.pengajuan.index', ['status' => 'returned'])); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'returned' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-blue-600 border border-blue-200 hover:bg-blue-50 dark:bg-gray-800 dark:border-blue-900/50 dark:hover:bg-blue-900/30'); ?>">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Dikembalikan
                </a>
            </div>

            
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4 font-semibold w-1/3">Kode & Barang</th>
                                <th class="px-6 py-4 font-semibold">Jadwal & Tujuan</th>
                                <th class="px-6 py-4 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition items-start">
                                    <td class="px-6 py-4 align-top">
                                        <div class="font-bold text-gray-900 dark:text-white text-base"><?php echo e($item->loan_code); ?></div>
                                        <div class="text-sm font-medium text-blue-600 dark:text-blue-400 mt-0.5"><?php echo e($item->item_name); ?></div>
                                        <div class="text-xs text-gray-500 mt-1.5"><i class="fa-solid fa-boxes-stacked mr-1"></i> Jumlah: <?php echo e($item->quantity); ?> Item</div>

                                        
                                        <?php if($item->status == 2 && $item->admin_note): ?>
                                            <div class="mt-4 w-full rounded-lg border border-red-200 bg-red-50/80 p-3.5 dark:border-red-500/30 dark:bg-red-500/10 shadow-sm">
                                                <div class="flex items-start gap-3">
                                                    <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 text-base"></i>
                                                    <div class="flex-col">
                                                        <span class="block text-[11px] font-bold text-red-700 dark:text-red-400 uppercase tracking-widest mb-1 opacity-80">Catatan Admin:</span>
                                                        <p class="text-sm font-medium text-red-800 dark:text-red-200 leading-relaxed"><?php echo e($item->admin_note); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex items-center gap-1.5 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <i class="fa-regular fa-calendar text-gray-400"></i>
                                            <?php echo e(\Carbon\Carbon::parse($item->start_date)->format('d M Y, H:i')); ?>

                                            <span class="text-gray-400 font-normal">s/d</span>
                                            <?php echo e(\Carbon\Carbon::parse($item->end_date)->format('d M Y, H:i')); ?>

                                        </div>
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700">
                                            <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Tujuan:</span>
                                            <?php echo e($item->purpose); ?>

                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center align-top">
                                        <?php
                                            $statusColor = 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50';
                                            $statusIcon = 'fa-clock';
                                            $statusText = 'Menunggu';
                                            
                                            if ($item->status == 1) {
                                                $statusColor = 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50';
                                                $statusIcon = 'fa-check-circle';
                                                $statusText = 'Disetujui';
                                            } elseif ($item->status == 2) {
                                                $statusColor = 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50';
                                                $statusIcon = 'fa-circle-exclamation';
                                                $statusText = 'Ditolak';
                                            } elseif ($item->status == 3) {
                                                $statusColor = 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50';
                                                $statusIcon = 'fa-rotate-left';
                                                $statusText = 'Dikembalikan';
                                            }
                                        ?>
                                        <span class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-bold border <?php echo e($statusColor); ?>">
                                            <i class="fa-solid <?php echo e($statusIcon); ?>"></i> <?php echo e($statusText); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                            <div class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                <i class="fa-solid fa-folder-open text-3xl"></i>
                                            </div>
                                            <p class="text-sm font-medium">Belum ada riwayat peminjaman.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sainteku\Modules/ManajementInfrastruktur\resources/views/pengajuan/index.blade.php ENDPATH**/ ?>