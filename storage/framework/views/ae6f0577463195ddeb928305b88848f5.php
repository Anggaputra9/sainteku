

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <div class="space-y-6">

        
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Detail Prestasi
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Manajemen Achievement /</li>
                        <li><a href="<?php echo e(route('admin.achievements.index')); ?>" class="hover:text-blue-600">Admin Prestasi</a> /</li>
                        <li class="text-blue-600 dark:text-blue-400">Detail</li>
                    </ol>
                </nav>
            </div>

            <a href="<?php echo e(url()->previous()); ?>"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i>
                Kembali
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
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400 w-1/3">Pengaju</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <?php echo e($achievement->user->name); ?> (<?php echo e($achievement->user->id); ?>)
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Unit</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->unit_id ?? '-'); ?></td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Prestasi</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        <?php echo e($achievement->type->description ?? '-'); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Judul Karya</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white font-semibold"><?php echo e($achievement->title); ?></td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <i class="far fa-calendar mr-1 text-gray-400"></i>
                                    <?php echo e(date('d F Y', strtotime($achievement->achievement_date))); ?>

                                </td>
                            </tr>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tingkat</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                        <?php echo e($achievement->level->description ?? '-'); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php if($achievement->publication_type): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Publikasi</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->publication_type); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($achievement->publisher): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Penerbit/Penyelenggara</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->publisher); ?></td>
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
                            <?php if($achievement->description): ?>
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</td>
                                <td class="py-3 text-sm text-gray-900 dark:text-white"><?php echo e($achievement->description); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status</td>
                                <td class="py-3">
                                    <?php
                                    $statusColor = 'bg-gray-100 text-gray-800';
                                    if ($achievement->status == 'approved') {
                                    $statusColor = 'bg-green-100 text-green-800 border-green-200';
                                    } elseif ($achievement->status == 'rejected') {
                                    $statusColor = 'bg-red-100 text-red-800 border-red-200';
                                    } elseif ($achievement->status == 'pending') {
                                    $statusColor = 'bg-amber-100 text-amber-800 border-amber-200';
                                    }
                                    ?>
                                    <span class="inline-flex items-center rounded-md px-3 py-1 text-xs font-bold border <?php echo e($statusColor); ?>">
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
                                <?php if($achievement->file_path): ?>
                                <?php
                                $size = Storage::exists($achievement->file_path) ? round(Storage::size($achievement->file_path) / 1024, 2) : 0;
                                ?>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($size); ?> KB</p>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e(route('student.achievements.download', $achievement->id)); ?>"
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
                            Aksi Verifikasi
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        
                        <form action="<?php echo e(route('admin.achievements.approve', $achievement->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-3 text-sm font-semibold text-white hover:bg-green-700 transition shadow-md"
                                onclick="return confirm('Yakin ingin menyetujui prestasi ini?')">
                                <i class="fas fa-check-circle"></i>
                                Setujui Prestasi
                            </button>
                        </form>

                        
                        <form action="<?php echo e(route('admin.achievements.reject', $achievement->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Catatan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="rejection_note" rows="3" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-red-500 focus:ring-red-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    placeholder="Jelaskan alasan penolakan..."></textarea>
                            </div>
                            <button type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700 transition shadow-md"
                                onclick="return confirm('Yakin ingin menolak prestasi ini?')">
                                <i class="fas fa-times-circle"></i>
                                Tolak Prestasi
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                
                <?php if($achievement->status != 'pending'): ?>
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-clock mr-2 text-blue-500"></i>
                            Timeline Verifikasi
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <?php if($achievement->approved_at): ?>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Diverifikasi pada</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e(date('d F Y H:i', strtotime($achievement->approved_at))); ?>

                            </p>
                        </div>
                        <?php endif; ?>
                        <?php if($achievement->approved_by): ?>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Diverifikasi oleh</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo e($achievement->approver->name ?? 'Admin'); ?>

                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-history mr-2 text-blue-500"></i>
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

        
        <?php if($achievement->status == 'rejected' && $achievement->rejection_note): ?>
        <div class="rounded-xl border border-red-200 bg-red-50 p-6 shadow-sm dark:border-red-800/30 dark:bg-red-900/10">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <i class="fa-solid fa-circle-exclamation text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-base font-bold text-red-800 dark:text-red-400 mb-1">Catatan Penolakan</h4>
                    <p class="text-sm text-red-700 dark:text-red-300 leading-relaxed">
                        <?php echo e($achievement->rejection_note); ?>

                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>


<script>
    // Tunggu sampai DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM sudah siap');

        // Cari semua form
        const forms = document.querySelectorAll('form');
        console.log('Total form ditemukan:', forms.length);

        forms.forEach((form, index) => {
            console.log(`Form ${index}:`, {
                action: form.action,
                method: form.method,
                hasToken: form.querySelector('input[name="_token"]') !== null
            });
        });

        // Khusus form reject
        const rejectForm = document.querySelector('form[action*="reject"]');
        if (rejectForm) {
            console.log('Form reject DITEMUKAN');

            // Sudah pakai method POST, tidak perlu dipaksa
            console.log('Method form reject:', rejectForm.method);

            // Tambah event listener untuk memastikan
            rejectForm.addEventListener('submit', function(e) {
                console.log('Form reject disubmit dengan method:', this.method);
                console.log('Action:', this.action);
                console.log('Data:', new FormData(this));
            });
        } else {
            console.log('Form reject TIDAK DITEMUKAN');
        }
    });

    // Tangkap semua error JavaScript
    window.onerror = function(msg, url, line) {
        console.log('ERROR DITANGKAP:', msg, 'di baris', line);
        return true; // Mencegah error muncul di console
    };
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Unduhan\sainteku\Modules/ManajemenAchievement\resources/views/admin/show.blade.php ENDPATH**/ ?>