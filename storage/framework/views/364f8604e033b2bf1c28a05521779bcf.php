
<div x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">

    <div @click.away="openCreate = false" x-show="openCreate"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all mt-10">

        
        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Formulir Peminjaman Fasilitas</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Silakan lengkapi data peminjaman di bawah ini.</p>
            </div>
            <button @click="openCreate = false" type="button"
                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>

        
        <form action="<?php echo e(route('manajementinfrastruktur.pengajuan.store')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                
                
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Barang / Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select name="inventory_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Fasilitas --</option>
                        <?php $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($inv->id); ?>"><?php echo e($inv->item_name); ?> (Tersedia: <?php echo e($inv->stock); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Jumlah Pinjam <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quantity" required min="1" value="1"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                </div>

                
                <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 md:col-span-2">
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-gray-400 italic">Jadwal Penggunaan</h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Waktu Mulai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="start_date" required class="w-full rounded-lg border-0 px-4 py-2.5 text-sm text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Waktu Selesai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="end_date" required class="w-full rounded-lg border-0 px-4 py-2.5 text-sm text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                    </div>
                </div>

                
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Tujuan / Keterangan Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="purpose" required rows="3"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Contoh: Rapat Koordinasi BEM di Ruang Rapat Lt. 2..."></textarea>
                </div>
            </div>

            
            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end mt-8">
                <button type="button" @click="openCreate = false"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-6 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-300 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-xmark"></i> Batal
                </button>
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/ManajementInfrastruktur\resources/views/pengajuan/modal-create.blade.php ENDPATH**/ ?>