<div x-data="{ openCreate: false }"
    @open-create-modal.window="openCreate = true"
    x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak style="display: none;">

    <div @click.away="openCreate = false" x-show="openCreate"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Infrastruktur / Inventaris</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan detail aset, spesifikasi, dan ketersediaan barang.</p>
            </div>
            <button type="button" @click="openCreate = false"
                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>

        <form action="<?php echo e(route('masterdata.infrastructures.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                
                <div class="space-y-4 rounded-xl border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                    <h4 class="text-sm font-bold uppercase tracking-wide text-gray-700 dark:text-gray-300 border-b border-gray-200 pb-2 dark:border-gray-600">Info Utama</h4>
                    
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">
                            Nama Barang / Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="item_name" required value="<?php echo e(old('item_name')); ?>"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                            placeholder="Contoh: Proyektor Epson X500">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">
                            Kategori Tipe <span class="text-red-500">*</span>
                        </label>
                        <select name="inventory_type" required
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            <option value="">-- Pilih Kategori --</option>
                            <?php $__currentLoopData = $inventoryTypes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php echo e(old('inventory_type') == $type->id ? 'selected' : ''); ?>>
                                    <?php echo e($type->description); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Merk Barang</label>
                            <input type="text" name="brand" value="<?php echo e(old('brand')); ?>"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                                placeholder="Cth: Epson, Olympic">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Satuan</label>
                            <select name="unit_measure"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Pilih Satuan --</option>
                                <option value="PCS" <?php echo e(old('unit_measure') == 'PCS' ? 'selected' : ''); ?>>PCS</option>
                                <option value="UNIT" <?php echo e(old('unit_measure') == 'UNIT' ? 'selected' : ''); ?>>UNIT</option>
                                <option value="SET" <?php echo e(old('unit_measure') == 'SET' ? 'selected' : ''); ?>>SET</option>
                                <option value="LEMBAR" <?php echo e(old('unit_measure') == 'LEMBAR' ? 'selected' : ''); ?>>LEMBAR</option>
                                <option value="PAK" <?php echo e(old('unit_measure') == 'PAK' ? 'selected' : ''); ?>>PAK</option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-4 rounded-xl border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                    <h4 class="text-sm font-bold uppercase tracking-wide text-gray-700 dark:text-gray-300 border-b border-gray-200 pb-2 dark:border-gray-600">Manajemen Aset</h4>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">
                                Jumlah Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stock" required min="0" value="<?php echo e(old('stock', 0)); ?>"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Harga (Rp)</label>
                            <input type="number" name="price" min="0" value="<?php echo e(old('price', 0)); ?>"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Unit Pemilik</label>
                            <select name="unit_id"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Universitas / Umum --</option>
                                <?php $__currentLoopData = $units ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($unit->id); ?>" <?php echo e(old('unit_id') == $unit->id ? 'selected' : ''); ?>>
                                        <?php echo e($unit->unit_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Status</label>
                            <select name="status" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="1" <?php echo e(old('status') == '1' ? 'selected' : ''); ?>>🟢 Aktif / Baik</option>
                                <option value="0" <?php echo e(old('status') == '0' ? 'selected' : ''); ?>>🔴 Rusak / Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Foto Barang</label>
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:cursor-pointer file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:text-gray-400 dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600">
                        <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">Format: JPG, PNG, WEBP (Maks: 2MB)</p>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-900 dark:text-white">Spesifikasi / Deskripsi</label>
                        <textarea name="description" rows="2"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                            placeholder="Catatan tambahan terkait barang ini..."><?php echo e(old('description')); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end mt-6">
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MasterData\resources/views/infrastructures/modal-create.blade.php ENDPATH**/ ?>