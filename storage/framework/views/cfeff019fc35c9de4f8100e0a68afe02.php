<?php $__env->startSection('content'); ?>
  <div class="mb-8 flex items-center justify-between">
    <div>
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Data Kurikulum</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400">Pengaturan kurikulum akademik per program studi di lingkungan Sainteku.</p>
    </div>
    <button disabled class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed dark:bg-gray-700 dark:text-gray-500">
      <i class="fas fa-plus"></i>
      Tambah Kurikulum
    </button>
  </div>

  <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
    <div class="flex flex-col items-center justify-center py-24 text-center">
      <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-amber-50 dark:bg-amber-900/20">
        <svg class="h-10 w-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
        </svg>
      </div>
      
      <h3 class="text-xl font-bold text-gray-900 dark:text-white">Modul Dalam Pengembangan</h3>
      <p class="mx-auto mt-2 max-w-xs text-sm text-gray-500 dark:text-gray-400">
        Kami sedang menyiapkan fitur pengelolaan kurikulum agar terintegrasi dengan data mata kuliah dan CPL.
      </p>

      <div class="mt-6">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
          <i class="fas fa-hammer text-[10px]"></i>
          Coming Soon
        </span>
      </div>
    </div>
  </div>

  <div class="mt-8 rounded-lg bg-blue-50/50 p-6 ring-1 ring-blue-100 dark:bg-blue-900/10 dark:ring-blue-800/30">
    <h4 class="text-sm font-bold uppercase tracking-wider text-blue-800 dark:text-blue-400 flex items-center gap-2">
      <i class="fas fa-info-circle"></i>
      Informasi Modul
    </h4>
    <p class="mt-2 text-sm text-blue-700 dark:text-blue-300">
      Modul ini nantinya akan memungkinkan Anda untuk mendefinisikan standar kurikulum yang berlaku, memetakan mata kuliah ke dalam semester, serta mengelola korelasi antara capaian pembelajaran.
    </p>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\sainteku\Modules/MasterData\resources/views/curricula/index.blade.php ENDPATH**/ ?>