<?php $__env->startSection('content'); ?>
    <div class="my-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Master Data</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola data inti sistem Sainteku. Data di sini akan menjadi referensi untuk semua modul lainnya.
            </p>
        </div>

        <!-- Statistik Ringkas -->
        <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengguna</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($totalUsers ?? '0'); ?></p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Role</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($totalRoles ?? '0'); ?></p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center">
                    <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.586M9 9h.008v.008H9V9zm5 0h.008v.008h-.008V9z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Unit</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($totalUnits ?? '0'); ?></p>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center">
                    <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Kurikulum</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($totalCurricula ?? '0'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Menu Master Data -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Card: Data Pengguna -->
            <div class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg hover:ring-blue-200 dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-blue-700">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/0 to-blue-600/0 opacity-0 transition-opacity group-hover:opacity-5"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Pengguna</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola pengguna sistem, dosen, dan mahasiswa</p>
                            <div class="mt-4 flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900 dark:text-blue-300">Aktif</span>
                                <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">Terintegrasi Auth</span>
                            </div>
                        </div>
                        <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-900">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="<?php echo e(route('masterdata.admin.users.index')); ?>" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            Kelola Pengguna
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card: Data Role -->
            <div class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg hover:ring-green-200 dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-green-700">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Role</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Role & permission untuk akses sistem</p>
                            <div class="mt-4">
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900 dark:text-green-300">Default: Admin, Dekan, Kaprodi, Dosen, Mahasiswa</span>
                            </div>
                        </div>
                        <div class="rounded-lg bg-green-50 p-3 dark:bg-green-900">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="<?php echo e(route('masterdata.roles.index')); ?>" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-700 dark:text-green-400">
                            Kelola Role
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card: Data Unit/Prodi -->
            <div class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg hover:ring-purple-200 dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-purple-700">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Unit / Prodi</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Struktur fakultas, jurusan, program studi</p>
                            <div class="mt-4">
                                <span class="inline-flex items-center rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 dark:bg-purple-900 dark:text-purple-300">Contoh: Saintek, Ushuluddin, dll</span>
                            </div>
                        </div>
                        <div class="rounded-lg bg-purple-50 p-3 dark:bg-purple-900">
                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.586M9 9h.008v.008H9V9zm5 0h.008v.008h-.008V9z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="<?php echo e(route('masterdata.units.index')); ?>" class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400">
                            Kelola Unit
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card: Data Kurikulum -->
            <div class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg hover:ring-yellow-200 dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-yellow-700">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Kurikulum</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kurikulum akademik per program studi</p>
                            <div class="mt-4">
                                <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">Referensi untuk modul akademik</span>
                            </div>
                        </div>
                        <div class="rounded-lg bg-yellow-50 p-3 dark:bg-yellow-900">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="<?php echo e(route('masterdata.curricula.index')); ?>" class="inline-flex items-center text-sm font-medium text-yellow-600 hover:text-yellow-700 dark:text-yellow-400">
                            Kelola Kurikulum
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card: Data Kategori Berkas -->
            <div class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg hover:ring-red-200 dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-red-700">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kategori Berkas</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Jenis-jenis berkas untuk upload</p>
                            <div class="mt-4">
                                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900 dark:text-red-300">IJAZAH, TRANSKRIP, SERTIFIKAT</span>
                            </div>
                        </div>
                        <div class="rounded-lg bg-red-50 p-3 dark:bg-red-900">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="<?php echo e(route('masterdata.categories.index')); ?>" class="inline-flex items-center text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400">
                            Kelola Kategori
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card: Module Info -->
            <div class="group relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-lg dark:bg-gray-800 dark:ring-gray-700">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e(config('masterdata.name')); ?></h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Versi <?php echo e(config('masterdata.version') ?? '1.0'); ?></p>
                            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                                Data master akan tersinkronisasi ke semua modul. Pastikan data diisi dengan benar.
                            </p>
                        </div>
                        <div class="rounded-lg bg-indigo-50 p-3 dark:bg-indigo-900">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips Penggunaan -->
        <div class="mt-8 rounded-lg bg-blue-50 p-6 dark:bg-blue-900/20">
            <h4 class="text-sm font-semibold uppercase tracking-wide text-blue-800 dark:text-blue-300">💡 Tips Penggunaan Master Data</h4>
            <ul class="mt-3 space-y-2 text-sm text-blue-700 dark:text-blue-200">
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-blue-500">•</span>
                    <span>Isi <strong>Data Unit</strong> terlebih dahulu (fakultas & prodi)</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-blue-500">•</span>
                    <span>Buat <strong>Role</strong> yang diperlukan (minimal: Admin, Dekan, Kaprodi, Dosen, Mahasiswa)</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-blue-500">•</span>
                    <span>Input <strong>Pengguna</strong> dan assign ke role & unit yang sesuai</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-0.5 text-blue-500">•</span>
                    <span>Data <strong>Kurikulum</strong> dan <strong>Kategori Berkas</strong> bisa diisi setelah struktur dasar selesai</span>
                </li>
            </ul>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MasterData\resources/views/index.blade.php ENDPATH**/ ?>