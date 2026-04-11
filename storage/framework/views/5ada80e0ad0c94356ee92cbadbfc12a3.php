

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                    </div>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($user->name); ?></h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?php echo e($user->identity_id ?? '-'); ?> • <?php echo e($user->user_type ?? 'User'); ?></p>

                    
                    <div class="flex flex-wrap gap-6 mt-4">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Total Prestasi</span>
                            <p class="text-xl font-bold text-gray-900 dark:text-white"><?php echo e($statistics['total']); ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Prestasi Mahasiswa</span>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400"><?php echo e($statistics['mahasiswa']); ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Prestasi Dosen</span>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400"><?php echo e($statistics['dosen']); ?></p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Tahun Aktif</span>
                            <p class="text-xl font-bold text-gray-900 dark:text-white"><?php echo e(count($statistics['per_tahun'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($tahunList->isNotEmpty()): ?>
        <div class="flex flex-wrap items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <i class="fas fa-calendar text-gray-500"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Tahun:</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('portfolio.show', $user->id)); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(!request('tahun') ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'); ?>">
                    Semua
                </a>
                <?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('portfolio.show', [$user->id, 'tahun' => $tahun])); ?>"
                    class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e(request('tahun') == $tahun ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'); ?>">
                    <?php echo e($tahun); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="space-y-6">
            <?php $__empty_1 = true; $__currentLoopData = $achievementsByYear; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-calendar text-blue-500"></i>
                        Tahun <?php echo e($tahun); ?>

                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">(<?php echo e($items->count()); ?> prestasi)</span>
                    </h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $achievement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                
                                <div class="flex items-center gap-2 mb-2">
                                    <?php if($achievement['type'] == 'mahasiswa'): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        <i class="fas fa-graduation-cap"></i>
                                        Mahasiswa
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        <i class="fas fa-chalkboard-user"></i>
                                        Dosen
                                    </span>
                                    <?php endif; ?>

                                    <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                        <?php echo e($achievement['tingkat']); ?>

                                    </span>
                                </div>

                                
                                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                                    <?php echo e($achievement['judul']); ?>

                                </h4>

                                
                                <p class="text-sm text-indigo-600 dark:text-indigo-400 mb-2">
                                    <?php echo e($achievement['kategori']); ?>

                                </p>

                                
                                <?php if($achievement['deskripsi']): ?>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    <?php echo e(Str::limit($achievement['deskripsi'], 150)); ?>

                                </p>
                                <?php endif; ?>

                                
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-500">
                                    <?php if($achievement['penerbit']): ?>
                                    <span><i class="fas fa-building mr-1"></i> <?php echo e($achievement['penerbit']); ?></span>
                                    <?php endif; ?>
                                    <?php if($achievement['url']): ?>
                                    <a href="<?php echo e($achievement['url']); ?>" target="_blank" class="text-blue-600 hover:underline">
                                        <i class="fas fa-link mr-1"></i> Link
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    <?php echo e(date('d M Y', strtotime($achievement['tanggal']))); ?>

                                </span>
                                <?php if($achievement['file_path']): ?>
                                <a href="<?php echo e($achievement['type'] == 'mahasiswa' ? route('student.achievements.download', $achievement['id']) : route('dosen.repository.download', $achievement['id'])); ?>"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-blue-600 border border-blue-200 hover:bg-blue-50 transition dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800 dark:hover:bg-gray-700"
                                    title="Download File">
                                    <i class="fas fa-file-pdf"></i>
                                    File
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                    <div class="h-20 w-20 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <i class="fas fa-folder-open text-4xl"></i>
                    </div>
                    <p class="text-base font-medium">Belum ada prestasi yang ditampilkan</p>
                    <p class="text-sm">User ini belum memiliki prestasi yang disetujui.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Unduhan\sainteku\Modules/ManajemenAchievement\resources/views/portfolio/show.blade.php ENDPATH**/ ?>