<?php $__env->startSection('content'); ?>
  <div class="mb-8 flex items-center justify-between">
    <div>
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Unit Baru</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan struktur organisasi baru seperti fakultas, prodi, atau lembaga.</p>
    </div>
    <a href="<?php echo e(route('masterdata.units.index')); ?>" 
       class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-700 transition">
      <i class="fas fa-arrow-left"></i>
      Kembali
    </a>
  </div>

  <div class="max-w-4xl rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
    <form action="<?php echo e(route('masterdata.units.store')); ?>" method="POST" class="space-y-8">
      <?php echo csrf_field(); ?>

      <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
        
        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
            ID Unit <span class="text-red-500">*</span>
          </label>
          <input type="text" name="id" maxlength="4" required
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
            placeholder="Contoh: U001">
          <p class="mt-2 text-xs text-gray-400 italic">Maksimal 4 karakter unik.</p>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
            Nama Unit <span class="text-red-500">*</span>
          </label>
          <input type="text" name="unit_name" required
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
            placeholder="Contoh: Fakultas Sains dan Teknologi">
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Unit Parent</label>
          <input type="text" name="unit_parent" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
            placeholder="ID Induk (Opsional)">
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tipe Unit (ID)</label>
          <input type="number" name="unit_type_id" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
            placeholder="Masukkan ID tipe unit">
        </div>

        <div class="flex items-center pt-4 md:col-span-2">
          <label class="relative inline-flex cursor-pointer items-center gap-3">
            <input type="checkbox" name="is_active" value="1" class="peer sr-only" checked>
            <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700"></div>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">Aktifkan Unit Sekarang</span>
          </label>
        </div>
      </div>

      <div class="flex flex-col-reverse gap-3 pt-6 border-t border-gray-100 dark:border-gray-700 sm:flex-row sm:justify-end">
        <a href="<?php echo e(route('masterdata.units.index')); ?>" 
           class="inline-flex justify-center rounded-lg px-6 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-700 transition">
          Batal
        </a>
        <button type="submit" 
                class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
          <i class="fas fa-save"></i>
          Simpan Unit
        </button>
      </div>
    </form>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MasterData\resources/views/units/create.blade.php ENDPATH**/ ?>