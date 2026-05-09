<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10 space-y-8">

    
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 pb-5 dark:border-gray-700">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Dashboard Infrastruktur</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                Pantau statistik peminjaman, periksa ketersediaan fasilitas, dan kelola persetujuan secara <span class="font-semibold text-blue-600 dark:text-blue-400">Real-time</span>.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3 mt-3 sm:mt-0">
            <?php if(Auth::user()->hasPermission(6, 'C')): ?>
            <a href="<?php echo e(route('manajementinfrastruktur.pengajuan.index')); ?>" 
               class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:text-blue-600 transition-all dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-700 dark:hover:text-blue-400">
                <i class="fa-solid fa-calendar-plus"></i> Buat Pengajuan
            </a>
            <?php endif; ?>
            <?php if(Auth::user()->hasPermission(6, 'A')): ?>
            <a href="<?php echo e(route('manajementinfrastruktur.persetujuan.index')); ?>" 
               class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-500/30 hover:from-blue-700 hover:to-indigo-700 hover:shadow-lg transition-all dark:shadow-none">
                <i class="fa-solid fa-clipboard-check"></i> Kelola Persetujuan
            </a>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        
        
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:ring-blue-300 dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-blue-600">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-blue-500 opacity-80"></div>
            <div class="flex items-center gap-5">
                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 transition-transform duration-300 group-hover:scale-110 dark:bg-blue-900/40">
                    <i class="fa-solid fa-boxes-stacked text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Fasilitas</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-900 dark:text-white"><?php echo e(number_format($totalBarang)); ?></p>
                </div>
            </div>
        </div>

        
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:ring-amber-300 dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-amber-600">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-amber-500 opacity-80"></div>
            <div class="flex items-center gap-5">
                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-amber-50 transition-transform duration-300 group-hover:scale-110 dark:bg-amber-900/40">
                    <i class="fa-solid fa-clock-rotate-left text-2xl text-amber-600 dark:text-amber-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Menunggu ACC</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-900 dark:text-white"><?php echo e(number_format($totalPending)); ?></p>
                </div>
            </div>
        </div>

        
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:ring-emerald-300 dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-emerald-600">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-emerald-500 opacity-80"></div>
            <div class="flex items-center gap-5">
                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-emerald-50 transition-transform duration-300 group-hover:scale-110 dark:bg-emerald-900/40">
                    <i class="fa-solid fa-hand-holding-hand text-2xl text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sedang Dipinjam</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-900 dark:text-white"><?php echo e(number_format($totalDipinjam)); ?></p>
                </div>
            </div>
        </div>

        
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:ring-indigo-300 dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-indigo-600">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-indigo-500 opacity-80"></div>
            <div class="flex items-center gap-5">
                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-indigo-50 transition-transform duration-300 group-hover:scale-110 dark:bg-indigo-900/40">
                    <i class="fa-solid fa-box-archive text-2xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dikembalikan</p>
                    <p class="mt-1 text-3xl font-extrabold text-gray-900 dark:text-white"><?php echo e(number_format($totalSelesai)); ?></p>
                </div>
            </div>
        </div>

    </div>

    
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
        
        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 p-5 sm:px-8 dark:border-gray-700 dark:bg-gray-800/80">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/50">
                    <i class="fa-solid fa-list-ul text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Aktivitas Peminjaman Terbaru</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Menampilkan 5 transaksi terakhir</p>
                </div>
            </div>
            <a href="<?php echo e(route('manajementinfrastruktur.persetujuan.index')); ?>" class="group inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                Lihat Semua <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
        
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="border-b border-gray-100 bg-white text-[11px] uppercase tracking-wider text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <tr>
                        <th class="px-8 py-4 font-bold">Informasi Peminjam</th>
                        <th class="px-8 py-4 font-bold">Fasilitas / Barang</th>
                        <th class="px-8 py-4 font-bold">Jadwal Mulai</th>
                        <th class="px-8 py-4 font-bold text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $transaksiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white"><?php echo e($trx->user_name); ?></div>
                                        <div class="text-[11px] font-medium text-gray-500">KODE: <?php echo e($trx->loan_code); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <div class="font-bold text-indigo-600 dark:text-indigo-400"><?php echo e($trx->item_name); ?></div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">Jumlah: <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo e($trx->quantity); ?> item</span></div>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                                    <i class="fa-regular fa-calendar-days text-gray-400"></i>
                                    <?php echo e(\Carbon\Carbon::parse($trx->start_date)->format('d M Y, H:i')); ?>

                                </div>
                            </td>
                            <td class="px-8 py-4 text-right whitespace-nowrap">
                                <?php
                                    $badgeColor = 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-400 dark:ring-amber-500/20';
                                    $badgeIcon = 'fa-clock';
                                    $badgeText = 'Menunggu';
                                    
                                    if($trx->status == 1) { 
                                        $badgeColor = 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-400 dark:ring-emerald-500/20'; 
                                        $badgeIcon = 'fa-check';
                                        $badgeText = 'Dipinjam'; 
                                    } elseif($trx->status == 2) { 
                                        $badgeColor = 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/30 dark:text-red-400 dark:ring-red-500/20'; 
                                        $badgeIcon = 'fa-xmark';
                                        $badgeText = 'Ditolak'; 
                                    } elseif($trx->status == 3) { 
                                        $badgeColor = 'bg-indigo-50 text-indigo-700 ring-indigo-600/20 dark:bg-indigo-900/30 dark:text-indigo-400 dark:ring-indigo-500/20'; 
                                        $badgeIcon = 'fa-rotate-left';
                                        $badgeText = 'Selesai'; 
                                    }
                                ?>
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-bold ring-1 ring-inset <?php echo e($badgeColor); ?>">
                                    <i class="fa-solid <?php echo e($badgeIcon); ?>"></i> <?php echo e($badgeText); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center">
                                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                    <i class="fa-solid fa-folder-open text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Belum Ada Aktivitas</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data transaksi peminjaman akan muncul di sini.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 p-6 sm:p-8 ring-1 ring-inset ring-blue-100/50 dark:from-gray-800 dark:to-gray-800/80 dark:ring-gray-700">
        
        <div class="absolute -right-10 -top-10 text-blue-500/5 dark:text-gray-700/30 pointer-events-none">
            <i class="fa-solid fa-layer-group text-9xl"></i>
        </div>
        
        <div class="relative z-10 flex flex-col sm:flex-row gap-6">
            <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 dark:bg-gray-700 dark:ring-gray-600">
                <i class="fa-solid fa-lightbulb text-3xl text-blue-500 dark:text-blue-400"></i>
            </div>
            <div>
                <h4 class="text-lg font-extrabold text-blue-900 dark:text-blue-300 tracking-tight">Panduan Modul Infrastruktur</h4>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm font-medium text-blue-800/80 dark:text-gray-300">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-check mt-1 text-blue-500 dark:text-blue-400 text-[10px]"></i>
                        <span>Menu <strong>Buat Pengajuan</strong> digunakan oleh Mahasiswa/Dosen untuk meminjam barang.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-check mt-1 text-blue-500 dark:text-blue-400 text-[10px]"></i>
                        <span>Menu <strong>Kelola Persetujuan</strong> digunakan oleh Admin untuk memberikan ACC.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-check mt-1 text-blue-500 dark:text-blue-400 text-[10px]"></i>
                        <span>Stok Master Data akan <strong class="text-red-500 dark:text-red-400">otomatis berkurang</strong> saat di-ACC.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-check mt-1 text-blue-500 dark:text-blue-400 text-[10px]"></i>
                        <span>Stok Master Data akan <strong class="text-green-500 dark:text-green-400">otomatis kembali</strong> saat ditandai Selesai.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sainteku\Modules/ManajementInfrastruktur\resources/views/index.blade.php ENDPATH**/ ?>