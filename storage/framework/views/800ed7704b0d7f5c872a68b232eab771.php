<?php $__env->startSection('content'); ?>
  <div class="mb-8 flex items-center justify-between">
    <div>
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Kategori Berkas</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400">Kelola jenis dokumen pendukung seperti Ijazah, Transkrip, dan Sertifikat.</p>
    </div>
    <button disabled class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed dark:bg-gray-700 dark:text-gray-500">
      <i class="fas fa-plus"></i>
      Tambah Kategori
    </button>
  </div>

  <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
    <div class="flex flex-col items-center justify-center py-24 text-center">
      <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-red-50 dark:bg-red-900/20">
        <svg class="h-10 w-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
      </div>
      
      <h3 class="text-xl font-bold text-gray-900 dark:text-white">Modul Dalam Pengembangan</h3>
      <p class="mx-auto mt-2 max-w-xs text-sm text-gray-500 dark:text-gray-400">
        Fitur untuk mengatur kategori unggahan berkas sedang kami siapkan agar sesuai dengan kebutuhan arsip digital Sainteku.
      </p>

      <div class="mt-6">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/40 dark:text-red-300">
          <i class="fas fa-tools text-[10px]"></i>
          Under Construction
        </span>
      </div>
    </div>
  </div>

  <div class="mt-8 rounded-lg bg-gray-50 p-6 ring-1 ring-gray-200 dark:bg-gray-700/30 dark:ring-gray-700">
    <h4 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white flex items-center gap-2">
      <i class="fas fa-lightbulb text-amber-500"></i>
      Rencana Fitur
    </h4>
    <ul class="mt-3 space-y-2 text-sm text-gray-600 dark:text-gray-400">
      <li class="flex items-center gap-2">
        <span class="h-1 w-1 rounded-full bg-red-500"></span>
        Pengaturan batas ukuran file per kategori (misal: Ijazah maks 2MB).
      </li>
      <li class="flex items-center gap-2">
        <span class="h-1 w-1 rounded-full bg-red-500"></span>
        Validasi format file (PDF, JPG, PNG).
      </li>
    </ul>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/categories/index.blade.php ENDPATH**/ ?>