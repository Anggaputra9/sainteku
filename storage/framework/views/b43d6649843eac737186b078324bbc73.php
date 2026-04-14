<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Manajemen Prestasi Mahasiswa
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Manajemen Achievement /</li>
                        <li class="text-blue-600 dark:text-blue-400">Admin Prestasi</li>
                    </ol>
                </nav>
            </div>
            <a href="<?php echo e(route('admin.achievements.pending')); ?>"
                class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-amber-600 transition">
                <i class="fas fa-clock mr-1"></i>
                Lihat Pending
            </a>
        </div>

        
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Prestasi</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($achievements->total()); ?></p>
                    </div>
                    <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/30">
                        <i class="fas fa-trophy text-xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400"><?php echo e($achievements->where('status','pending')->count()); ?></p>
                    </div>
                    <div class="rounded-lg bg-amber-100 p-3 dark:bg-amber-900/30">
                        <i class="fas fa-clock text-xl text-amber-600 dark:text-amber-400"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Disetujui</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400"><?php echo e($achievements->where('status','approved')->count()); ?></p>
                    </div>
                    <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/30">
                        <i class="fas fa-check-circle text-xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ditolak</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400"><?php echo e($achievements->where('status','rejected')->count()); ?></p>
                    </div>
                    <div class="rounded-lg bg-red-100 p-3 dark:bg-red-900/30">
                        <i class="fas fa-times-circle text-xl text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="flex flex-wrap items-center gap-2">
            <a href="<?php echo e(route('admin.achievements.index')); ?>"
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(!request('status') ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'); ?>">
                Semua
            </a>
            <a href="<?php echo e(route('admin.achievements.index', ['status' => 'pending'])); ?>"
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(request('status') == 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-amber-600 border border-amber-200 hover:bg-amber-50 dark:bg-gray-800 dark:border-amber-900/50 dark:hover:bg-amber-900/30'); ?>">
                <i class="fas fa-clock mr-1"></i> Pending
            </a>
            <a href="<?php echo e(route('admin.achievements.index', ['status' => 'approved'])); ?>"
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(request('status') == 'approved' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-green-600 border border-green-200 hover:bg-green-50 dark:bg-gray-800 dark:border-green-900/50 dark:hover:bg-green-900/30'); ?>">
                <i class="fas fa-check-circle mr-1"></i> Disetujui
            </a>
            <a href="<?php echo e(route('admin.achievements.index', ['status' => 'rejected'])); ?>"
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(request('status') == 'rejected' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50 dark:hover:bg-red-900/30'); ?>">
                <i class="fas fa-times-circle mr-1"></i> Ditolak
            </a>
        </div>

        
        <div class="flex flex-wrap items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <i class="fas fa-calendar text-gray-500"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tahun:</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('admin.achievements.index', array_merge(request()->query(), ['tahun' => '']))); ?>"
                    class="px-3 py-1 text-xs font-semibold rounded-full transition <?php echo e(!request('tahun') ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'); ?>">
                    Semua
                </a>
                <?php $__currentLoopData = $tahunList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('admin.achievements.index', array_merge(request()->query(), ['tahun' => $tahun]))); ?>"
                    class="px-3 py-1 text-xs font-semibold rounded-full transition <?php echo e(request('tahun') == $tahun ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'); ?>">
                    <?php echo e($tahun); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-2/5">Judul & Pengaju</th>
                            <th class="px-6 py-4 font-semibold">Jenis & Tingkat</th>
                            <th class="px-6 py-4 font-semibold text-center">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php $__empty_1 = true; $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4 align-top">
                                <div class="font-bold text-gray-900 dark:text-white text-base">
                                    <?php echo e($item->title); ?>

                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1.5">
                                    <i class="fas fa-user"></i>
                                    <span><?php echo e($item->user->name ?? 'Unknown'); ?></span>
                                    <span class="text-gray-400">(<?php echo e($item->user->user_type ?? '-'); ?>)</span>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-clock"></i>
                                    Diajukan: <?php echo e($item->created_at->format('d/m/Y H:i')); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="font-semibold text-indigo-600 dark:text-indigo-400">
                                    <?php echo e($item->type->description ?? '-'); ?>

                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-signal"></i>
                                    <?php echo e($item->level->description ?? '-'); ?>

                                </div>
                                <?php if($item->unit_id): ?>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-building"></i>
                                    <?php echo e($item->unit_id); ?>

                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center align-top">
                                <span class="text-sm font-medium">
                                    <?php echo e(date('d/m/Y', strtotime($item->achievement_date))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-center align-top">
                                <?php
                                $statusColor = 'bg-gray-100 text-gray-800';
                                if ($item->status == 'approved') {
                                $statusColor = 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50';
                                } elseif ($item->status == 'rejected') {
                                $statusColor = 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50';
                                } elseif ($item->status == 'pending') {
                                $statusColor = 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50';
                                }
                                ?>
                                <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-bold border <?php echo e($statusColor); ?>">
                                    <?php if($item->status == 'pending'): ?>
                                    <i class="fas fa-clock mr-1"></i> Pending
                                    <?php elseif($item->status == 'approved'): ?>
                                    <i class="fas fa-check-circle mr-1"></i> Disetujui
                                    <?php else: ?>
                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center align-top">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?php echo e(route('admin.achievements.show', $item->id)); ?>"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye text-blue-500"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                    <div class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <i class="fas fa-file-alt text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium">Belum ada data prestasi.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <?php if($achievements->hasPages()): ?>
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                <?php echo e($achievements->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/ManajemenAchievement\resources/views/admin/index.blade.php ENDPATH**/ ?>