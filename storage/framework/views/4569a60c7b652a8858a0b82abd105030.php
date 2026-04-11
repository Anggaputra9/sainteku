<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Detail Prestasi Dosen
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Manajemen Achievement /</li>
                        <li><a href="<?php echo e(route('dosen.repository.index')); ?>" class="hover:text-blue-600">Repository Dosen</a> /</li>
                        <li class="text-blue-600 dark:text-blue-400">Detail</li>
                    </ol>
                </nav>
            </div>

            <a href="<?php echo e(route('dosen.repository.index')); ?>"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar
            </a>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Informasi Prestasi
                        </h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full">
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400 w-1/3">Kategori</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white font-semibold">
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        <?php echo e($achievement->kategori->nama ?? '-'); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Judul Karya</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->judul); ?></td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tingkat</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                        <?php echo e($achievement->tingkat->nama ?? '-'); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <i class="far fa-calendar mr-1 text-gray-400"></i>
                                    <?php echo e(date('d F Y', strtotime($achievement->tanggal))); ?>

                                </td>
                            </tr>
                            <?php if($achievement->penyelenggara): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Penyelenggara/Penerbit</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->penyelenggara); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->url): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">URL</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <a href="<?php echo e($achievement->url); ?>" target="_blank" class="text-blue-600 hover:underline">
                                        <?php echo e($achievement->url); ?> <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status</td>
                                <td class="py-3">
                                    <?php
                                    $statusColor = 'bg-gray-100 text-gray-800';
                                    if ($achievement->status == 'approved') {
                                    $statusColor = 'bg-green-100 text-green-800';
                                    } elseif ($achievement->status == 'rejected') {
                                    $statusColor = 'bg-red-100 text-red-800';
                                    } elseif ($achievement->status == 'pending') {
                                    $statusColor = 'bg-amber-100 text-amber-800';
                                    }
                                    ?>
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold <?php echo e($statusColor); ?>">
                                        <?php if($achievement->status == 'pending'): ?>
                                        <i class="far fa-clock mr-1"></i> Pending
                                        <?php elseif($achievement->status == 'approved'): ?>
                                        <i class="far fa-circle-check mr-1"></i> Disetujui
                                        <?php else: ?>
                                        <i class="far fa-circle-xmark mr-1"></i> Ditolak
                                        <?php endif; ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                
                <?php if($achievement->deskripsi): ?>
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-align-left mr-2 text-blue-500"></i>
                            Deskripsi
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                            <?php echo e($achievement->deskripsi); ?>

                        </p>
                    </div>
                </div>
                <?php endif; ?>

                
                <?php if($achievement->kategori && (
                str_contains($achievement->kategori->nama, 'Jurnal') ||
                str_contains($achievement->kategori->nama, 'Prosiding'))): ?>
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-book-open mr-2 text-blue-500"></i>
                            Detail Publikasi
                        </h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full">
                            <?php if($achievement->jenis_publikasi): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400 w-1/3">Jenis Publikasi</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e(ucfirst($achievement->jenis_publikasi)); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->nama_jurnal): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400">Nama Jurnal</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->nama_jurnal); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->volume): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400">Volume</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->volume); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->nomor): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400">Nomor</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->nomor); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->halaman): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400">Halaman</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->halaman); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->issn): ?>
                            <tr>
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400">ISSN</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->issn); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($achievement->kategori && (
                str_contains($achievement->kategori->nama, 'HKI') ||
                str_contains($achievement->kategori->nama, 'Paten'))): ?>
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-copyright mr-2 text-blue-500"></i>
                            Detail HKI/Paten
                        </h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full">
                            <?php if($achievement->nomor_pendaftaran): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400 w-1/3">Nomor Pendaftaran</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->nomor_pendaftaran); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->status_hki): ?>
                            <tr>
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400">Status HKI</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->status_hki); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($achievement->kategori && str_contains($achievement->kategori->nama, 'Buku')): ?>
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-book mr-2 text-blue-500"></i>
                            Detail Buku
                        </h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full">
                            <?php if($achievement->isbn): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400 w-1/3">ISBN</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->isbn); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->penerbit): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400">Penerbit</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->penerbit); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->jumlah_halaman): ?>
                            <tr>
                                <td class="py-2 text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Halaman</td>
                                <td class="py-2 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->jumlah_halaman); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                
                <?php if($achievement->status == 'rejected' && $achievement->catatan_penolakan): ?>
                <div class="rounded-xl border border-red-200 bg-red-50 shadow-sm dark:border-red-800/30 dark:bg-red-900/10">
                    <div class="border-b border-red-200 px-6 py-4 dark:border-red-800/30">
                        <h3 class="text-lg font-bold text-red-800 dark:text-red-400">
                            <i class="fas fa-circle-exclamation mr-2"></i>
                            Catatan Penolakan
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-red-700 dark:text-red-300">
                            <?php echo e($achievement->catatan_penolakan); ?>

                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="space-y-6">
                
                <?php if($achievement->file_path): ?>
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-paperclip mr-2 text-blue-500"></i>
                            File Pendukung
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    <?php echo e($achievement->file_name); ?>

                                </p>
                            </div>
                            <a href="<?php echo e(route('dosen.repository.download', $achievement->id)); ?>"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 p-2 text-white hover:bg-blue-700 transition"
                                title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                
                <?php if($achievement->status == 'pending'): ?>
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-cog mr-2 text-blue-500"></i>
                            Aksi
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="<?php echo e(route('dosen.repository.edit', $achievement->id)); ?>"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-3 text-sm font-semibold text-white hover:bg-amber-600 transition">
                            <i class="far fa-pen-to-square"></i>
                            Edit Prestasi
                        </a>
                        <form method="POST" action="<?php echo e(route('dosen.repository.destroy', $achievement->id)); ?>"
                            onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-500 px-4 py-3 text-sm font-semibold text-white hover:bg-red-600 transition">
                                <i class="far fa-trash-can"></i>
                                Hapus Prestasi
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-clock mr-2 text-blue-500"></i>
                            Timeline
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Diajukan pada</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e($achievement->created_at->format('d F Y H:i')); ?>

                            </p>
                        </div>
                        <?php if($achievement->approved_at): ?>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Diproses pada</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e(date('d F Y H:i', strtotime($achievement->approved_at))); ?>

                            </p>
                        </div>
                        <?php endif; ?>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Terakhir diperbarui</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e($achievement->updated_at->format('d F Y H:i')); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Unduhan\sainteku\Modules/ManajemenAchievement\resources/views/dosen/show.blade.php ENDPATH**/ ?>