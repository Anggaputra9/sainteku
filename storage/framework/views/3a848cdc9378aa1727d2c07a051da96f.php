<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Repository Prestasi Dosen
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Manajemen Achievement /</li>
                        <li class="text-blue-600 dark:text-blue-400">Repository Dosen</li>
                    </ol>
                </nav>
            </div>

            
            <div x-data="{ openCreateDosen: false }">
                <button @click="openCreateDosen = true"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                    <i class="fas fa-plus-circle"></i>
                    Ajukan Prestasi Dosen
                </button>

                
                <?php echo $__env->make('manajemenachievement::dosen.modal-create', [
                'kategori' => $kategori,
                'tingkat' => $tingkat
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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

        
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <a href="<?php echo e(route('dosen.repository.index')); ?>"
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(!request('status') ? 'bg-green-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'); ?>">
                Semua Prestasi
            </a>
            <a href="<?php echo e(route('dosen.repository.index', ['status' => 'pending'])); ?>"
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(request('status') == 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-amber-600 border border-amber-200 hover:bg-amber-50 dark:bg-gray-800 dark:border-amber-900/50 dark:hover:bg-amber-900/30'); ?>">
                <i class="fas fa-clock mr-1"></i> Pending
            </a>
            <a href="<?php echo e(route('dosen.repository.index', ['status' => 'approved'])); ?>"
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(request('status') == 'approved' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-green-600 border border-green-200 hover:bg-green-50 dark:bg-gray-800 dark:border-green-900/50 dark:hover:bg-green-900/30'); ?>">
                <i class="fas fa-circle-check mr-1"></i> Disetujui
            </a>
            <a href="<?php echo e(route('dosen.repository.index', ['status' => 'rejected'])); ?>"
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(request('status') == 'rejected' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50 dark:hover:bg-red-900/30'); ?>">
                <i class="fas fa-circle-xmark mr-1"></i> Ditolak
            </a>
        </div>

        
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Kategori</th>
                            <th class="px-6 py-4 font-semibold">Judul</th>
                            <th class="px-6 py-4 font-semibold text-center">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-center">Tingkat</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php $__empty_1 = true; $__currentLoopData = $achievements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4">
                                <span class="font-medium text-green-600 dark:text-green-400">
                                    <?php echo e($item->kategori->nama ?? '-'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    <?php echo e($item->judul); ?>

                                </div>
                                <?php if($item->deskripsi): ?>
                                <div class="text-xs text-gray-500 mt-1">
                                    <?php echo e(Str::limit($item->deskripsi, 50)); ?>

                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php echo e(date('d/m/Y', strtotime($item->tanggal))); ?>

                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                    <?php echo e($item->tingkat->nama ?? '-'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                $statusColor = 'bg-gray-100 text-gray-800';
                                if ($item->status == 'approved') {
                                $statusColor = 'bg-green-100 text-green-800';
                                } elseif ($item->status == 'rejected') {
                                $statusColor = 'bg-red-100 text-red-800';
                                } elseif ($item->status == 'pending') {
                                $statusColor = 'bg-amber-100 text-amber-800';
                                }
                                ?>
                                <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-bold <?php echo e($statusColor); ?>">
                                    <?php if($item->status == 'pending'): ?>
                                    <i class="fas fa-clock mr-1"></i> Pending
                                    <?php elseif($item->status == 'approved'): ?>
                                    <i class="fas fa-circle-check mr-1"></i> Disetujui
                                    <?php else: ?>
                                    <i class="fas fa-circle-xmark mr-1"></i> Ditolak
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    <a href="<?php echo e(route('dosen.repository.show', $item->id)); ?>"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye text-green-500"></i>
                                    </a>

                                    
                                    <?php if($item->file_path): ?>
                                    <a href="<?php echo e(route('dosen.repository.download', $item->id)); ?>"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
                                        title="Download File">
                                        <i class="fas fa-file-pdf text-red-500"></i>
                                    </a>
                                    <?php endif; ?>

                                    
                                    <?php if($item->status == 'pending'): ?>
                                    <a href="<?php echo e(route('dosen.repository.edit', $item->id)); ?>"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 px-3 py-2 text-xs font-bold text-white hover:bg-amber-600 transition shadow-sm"
                                        title="Edit">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <?php endif; ?>

                                    
                                    <?php if($item->status == 'pending'): ?>
                                    <form method="POST" action="<?php echo e(route('dosen.repository.destroy', $item->id)); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-red-500 px-3 py-2 text-xs font-bold text-white hover:bg-red-600 transition shadow-sm"
                                            title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus prestasi ini?')">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                    <div class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <i class="fas fa-folder-open text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium">Belum ada prestasi dosen yang diajukan.</p>
                                    
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Unduhan\sainteku\Modules/ManajemenAchievement\resources/views/dosen/index.blade.php ENDPATH**/ ?>