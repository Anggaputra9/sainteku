<x-masterdata::layouts.master>
    <div class="my-6 flex flex-col gap-9">
        <div class="rounded-lg border border-stroke bg-white px-8 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Selamat Datang di Dashboard Master Data!
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Kelola semua data master sistem Sainteku dari sini. Pilih menu di sebelah kiri untuk mulai.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <!-- Card: Data Pengguna -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Data Pengguna</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Kelola data pengguna dan user sistem
                            </p>
                        </div>
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                        </svg>
                    </div>
                    <a href="{{ route('masterdata.admin.users.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        Kelola Pengguna →
                    </a>
                </div>

                <!-- Card: Data Role -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Data Role</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Kelola role dan permission sistem
                            </p>
                        </div>
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <a href="{{ route('masterdata.roles.index') }}" class="mt-3 inline-block text-green-600 hover:text-green-700 dark:text-green-400">
                        Kelola Role →
                    </a>
                </div>

                <!-- Card: Data Unit -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Data Unit/Prodi</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Kelola data unit dan program studi
                            </p>
                        </div>
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m0 0h5.581M9 9h.008v.008H9V9m5 0h.008v.008h-.008V9m-5 4h.008v.008H9v-.008zm5 0h.008v.008h-.008v-.008z"></path>
                        </svg>
                    </div>
                    <a href="{{ route('masterdata.units.index') }}" class="mt-3 inline-block text-purple-600 hover:text-purple-700 dark:text-purple-400">
                        Kelola Unit →
                    </a>
                </div>

                <!-- Card: Data Kurikulum -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Data Kurikulum</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Kelola kurikulum dan struktur akademik
                            </p>
                        </div>
                        <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                        </svg>
                    </div>
                    <a href="{{ route('masterdata.curricula.index') }}" class="mt-3 inline-block text-yellow-600 hover:text-yellow-700 dark:text-yellow-400">
                        Kelola Kurikulum →
                    </a>
                </div>

                <!-- Card: Data Kategori Berkas -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Data Kategori Berkas</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Kelola kategori dan jenis-jenis berkas
                            </p>
                        </div>
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <a href="{{ route('masterdata.categories.index') }}" class="mt-3 inline-block text-red-600 hover:text-red-700 dark:text-red-400">
                        Kelola Kategori →
                    </a>
                </div>

                <!-- Card: Info Module -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="mb-2 font-semibold text-gray-900 dark:text-white">Module Info</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {!! config('masterdata.name') !!}
                            </p>
                        </div>
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-masterdata::layouts.master>
