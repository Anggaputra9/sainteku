<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="{ 
    openReview: false, 
    reviewUrl: '', 
    loanCode: '', 
    userName: '',
    itemName: '',
    purpose: ''
}">
    <div class="space-y-6">

        
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Persetujuan Fasilitas
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Manajemen Infrastruktur /</li>
                        <li class="text-blue-600 dark:text-blue-400">Persetujuan Peminjaman</li>
                    </ol>
                </nav>
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

        <?php if($errors->any() || session('error')): ?>
            <div class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                <div class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-red-800 dark:text-red-400">Peringatan!</h5>
                    <p class="text-sm text-red-700 dark:text-red-500"><?php echo e(session('error') ?? $errors->first()); ?></p>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <a href="<?php echo e(route('manajementinfrastruktur.persetujuan.index', ['status' => 'all'])); ?>" 
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'all' ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'); ?>">
                Semua Riwayat
            </a>
            <a href="<?php echo e(route('manajementinfrastruktur.persetujuan.index', ['status' => 'pending'])); ?>" 
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-amber-600 border border-amber-200 hover:bg-amber-50 dark:bg-gray-800 dark:border-amber-900/50 dark:hover:bg-amber-900/30'); ?>">
                <i class="fa-solid fa-clock mr-1"></i> Perlu Direview
            </a>
            <a href="<?php echo e(route('manajementinfrastruktur.persetujuan.index', ['status' => 'approved'])); ?>" 
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'approved' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-green-600 border border-green-200 hover:bg-green-50 dark:bg-gray-800 dark:border-green-900/50 dark:hover:bg-green-900/30'); ?>">
                <i class="fa-solid fa-check-circle mr-1"></i> Sedang Dipinjam
            </a>
            <a href="<?php echo e(route('manajementinfrastruktur.persetujuan.index', ['status' => 'returned'])); ?>" 
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'returned' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-blue-600 border border-blue-200 hover:bg-blue-50 dark:bg-gray-800 dark:border-blue-900/50 dark:hover:bg-blue-900/30'); ?>">
                <i class="fa-solid fa-rotate-left mr-1"></i> Telah Dikembalikan
            </a>
            <a href="<?php echo e(route('manajementinfrastruktur.persetujuan.index', ['status' => 'rejected'])); ?>" 
                class="px-4 py-2 text-sm font-semibold rounded-full transition <?php echo e($filterStatus == 'rejected' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50 dark:hover:bg-red-900/30'); ?>">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> Ditolak
            </a>
        </div>

        
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Peminjam & Kode</th>
                            <th class="px-6 py-4 font-semibold">Barang & Jadwal</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition items-start">
                                <td class="px-6 py-4 align-top">
                                    <div class="font-bold text-gray-900 dark:text-white text-base"><?php echo e($item->user_name); ?></div>
                                    <div class="text-sm font-medium text-blue-600 dark:text-blue-400 mt-0.5"><?php echo e($item->loan_code); ?></div>
                                    <div class="text-xs text-gray-500 mt-1.5 opacity-80">Tujuan: <?php echo e($item->purpose); ?></div>
                                    
                                    <?php if($item->status == 2 && $item->admin_note): ?>
                                        <div class="mt-2 text-[11px] text-red-600 font-medium">Alasan Tolak: <?php echo e($item->admin_note); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="font-semibold text-indigo-600 dark:text-indigo-400"><?php echo e($item->item_name); ?></div>
                                    <div class="text-xs text-gray-500 mt-0.5 mb-2">Jumlah: <?php echo e($item->quantity); ?> Item</div>
                                    
                                    <div class="flex flex-col gap-1 text-[11px] font-medium text-gray-700 dark:text-gray-300">
                                        <div><i class="fa-solid fa-plane-departure text-gray-400 mr-1 w-3"></i> <?php echo e(\Carbon\Carbon::parse($item->start_date)->format('d M Y, H:i')); ?></div>
                                        <div><i class="fa-solid fa-plane-arrival text-gray-400 mr-1 w-3"></i> <?php echo e(\Carbon\Carbon::parse($item->end_date)->format('d M Y, H:i')); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center align-top">
                                    <?php
                                        $statusColor = 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50';
                                        $statusText = 'Menunggu';
                                        if($item->status == 1) { $statusColor = 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50'; $statusText = 'Dipinjam'; }
                                        elseif($item->status == 2) { $statusColor = 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50'; $statusText = 'Ditolak'; }
                                        elseif($item->status == 3) { $statusColor = 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50'; $statusText = 'Dikembalikan'; }
                                    ?>
                                    <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-bold border <?php echo e($statusColor); ?>">
                                        <?php echo e($statusText); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center align-top">
                                    <div class="flex justify-center gap-2">
                                        <?php if($item->status == 0): ?> 
                                            
                                            <button 
                                                @click="openReview = true; 
                                                        reviewUrl = '<?php echo e(route('manajementinfrastruktur.persetujuan.update', $item->id)); ?>'; 
                                                        loanCode = '<?php echo e($item->loan_code); ?>'; 
                                                        userName = '<?php echo e(addslashes($item->user_name)); ?>';
                                                        itemName = '<?php echo e(addslashes($item->item_name)); ?>';
                                                        purpose = '<?php echo e(addslashes($item->purpose)); ?>'"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                                                <i class="fa-solid fa-clipboard-check"></i> Proses
                                            </button>
                                        <?php elseif($item->status == 1): ?>
                                            
                                            <form action="<?php echo e(route('manajementinfrastruktur.persetujuan.update', $item->id)); ?>" method="POST" onsubmit="return confirm('Tandai barang ini sudah dikembalikan dan pulihkan stok?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <input type="hidden" name="status" value="3">
                                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100 transition shadow-sm border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50">
                                                    <i class="fa-solid fa-box-archive"></i> Tandai Selesai
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">Selesai</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    <i class="fa-solid fa-clipboard-list text-3xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p>Tidak ada data pengajuan di kategori ini.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div x-show="openReview"
        class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">

        <div @click.away="openReview = false" x-show="openReview"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-2xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all mt-10">

            
            <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/50">
                        <i class="fa-solid fa-file-signature text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Proses Persetujuan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Silakan periksa dan berikan keputusan.</p>
                    </div>
                </div>
            </div>

            
            <form :action="reviewUrl" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="space-y-6">
                    
                    <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400 text-xs mb-1">Kode Pengajuan:</span>
                                <strong x-text="loanCode" class="text-gray-900 dark:text-white"></strong>
                            </div>
                            <div>
                                <span class="block text-gray-500 dark:text-gray-400 text-xs mb-1">Peminjam:</span>
                                <strong x-text="userName" class="text-gray-900 dark:text-white"></strong>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-gray-500 dark:text-gray-400 text-xs mb-1">Fasilitas/Barang:</span>
                                <strong x-text="itemName" class="text-indigo-600 dark:text-indigo-400"></strong>
                            </div>
                            <div class="col-span-2 mt-2 bg-white dark:bg-gray-800 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                <span class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Tujuan Peminjaman:</span>
                                <span x-text="purpose" class="text-gray-700 dark:text-gray-300 italic"></span>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <label for="admin_note" class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Catatan (Wajib diisi jika menolak):</label>
                        <textarea id="admin_note" name="admin_note" rows="3"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                            placeholder="Contoh: Maaf, barang sedang masuk masa maintenance..."></textarea>
                    </div>
                </div>

                
                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end mt-8">
                    <button type="button" @click="openReview = false"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    
                    <button type="submit" name="status" value="2"
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-red-700 transition">
                        <i class="fa-solid fa-xmark"></i> Tolak
                    </button>
                    
                    <button type="submit" name="status" value="1"
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-green-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fa-solid fa-check"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Unduhan\sainteku\Modules/ManajementInfrastruktur\resources/views/persetujuan/index.blade.php ENDPATH**/ ?>