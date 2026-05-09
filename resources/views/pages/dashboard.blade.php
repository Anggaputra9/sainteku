@extends('layouts.app')

@section('content')
    <div class="mx-auto space-y-10">

        {{-- KOP SURAT / WELCOME BANNER --}}
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-700 p-8 shadow-lg dark:from-gray-800 dark:to-gray-900">
            <div class="absolute -right-10 -top-10 text-white/10 pointer-events-none">
                <i class="fa-solid fa-shapes text-9xl"></i>
            </div>
            <div class="relative z-10 flex items-center gap-6">
                <div
                    class="h-20 w-20 rounded-full border-4 border-white/20 bg-white/10 p-1 backdrop-blur-md hidden sm:block">
                    <img src="{{ Auth::user()->avatar ? asset('storage/avatars/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                        class="h-full w-full rounded-full object-cover">
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Selamat Datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="mt-2 text-sm text-indigo-100 font-medium max-w-xl leading-relaxed">
                        Selamat datang di Pusat Kendali Terpadu Sainteku. Halaman ini menyajikan ringkasan informasi dan
                        akses cepat menuju layanan akademik serta administratif sesuai dengan kewenangan Anda.
                    </p>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- SECTION 1: MASTER DATA (KHUSUS ADMIN) --}}
        {{-- ========================================================================= --}}
        @if(isset($isAdmin) && $isAdmin)
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-3 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white"><i
                            class="fas fa-database text-blue-500 mr-2"></i> Master Data System</h2>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                    {{-- Pengguna --}}
                    <a href="{{ route('masterdata.admin.users.index') }}"
                        class="group block rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 hover:ring-blue-500 hover:shadow-md hover:-translate-y-1 transition-all dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-blue-500">
                        <div class="flex items-center gap-3">
                            <div
                                class="rounded-full bg-blue-100 p-2.5 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white dark:bg-blue-900/50 dark:text-blue-400">
                                <i class="fas fa-users text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest dark:text-gray-400">
                                    Pengguna</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
                            </div>
                        </div>
                    </a>

                    {{-- Role & Akses --}}
                    <a href="{{ route('masterdata.roles.index') }}"
                        class="group block rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 hover:ring-green-500 hover:shadow-md hover:-translate-y-1 transition-all dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-green-500">
                        <div class="flex items-center gap-3">
                            <div
                                class="rounded-full bg-green-100 p-2.5 text-green-600 transition-colors group-hover:bg-green-600 group-hover:text-white dark:bg-green-900/50 dark:text-green-400">
                                <i class="fas fa-user-shield text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest dark:text-gray-400">Role
                                    / Akses</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalRoles) }}</p>
                            </div>
                        </div>
                    </a>

                    {{-- Unit & Prodi --}}
                    <a href="{{ route('masterdata.units.index') }}"
                        class="group block rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 hover:ring-purple-500 hover:shadow-md hover:-translate-y-1 transition-all dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-purple-500">
                        <div class="flex items-center gap-3">
                            <div
                                class="rounded-full bg-purple-100 p-2.5 text-purple-600 transition-colors group-hover:bg-purple-600 group-hover:text-white dark:bg-purple-900/50 dark:text-purple-400">
                                <i class="fas fa-building text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest dark:text-gray-400">Unit
                                    / Prodi</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUnits) }}</p>
                            </div>
                        </div>
                    </a>

                    {{-- KURIKULUM / PERIODE (Baru) --}}
                    <a href="{{ route('masterdata.curricula.index') ?? '#' }}"
                        class="group block rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 hover:ring-amber-500 hover:shadow-md hover:-translate-y-1 transition-all dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-amber-500">
                        <div class="flex items-center gap-3">
                            <div
                                class="rounded-full bg-amber-100 p-2.5 text-amber-600 transition-colors group-hover:bg-amber-600 group-hover:text-white dark:bg-amber-900/50 dark:text-amber-400">
                                <i class="fas fa-calendar-alt text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest dark:text-gray-400">
                                    Kurikulum / TA</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPeriods) }}
                                </p>
                            </div>
                        </div>
                    </a>

                    {{-- MATA KULIAH (Berdiri Sendiri) --}}
                    <a href="{{ route('masterdata.courses.index') }}"
                        class="group block rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 hover:ring-yellow-500 hover:shadow-md hover:-translate-y-1 transition-all dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-yellow-500">
                        <div class="flex items-center gap-3">
                            <div
                                class="rounded-full bg-yellow-100 p-2.5 text-yellow-600 transition-colors group-hover:bg-yellow-600 group-hover:text-white dark:bg-yellow-900/50 dark:text-yellow-400">
                                <i class="fas fa-book-open text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest dark:text-gray-400">Mata
                                    Kuliah</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCourses) }}
                                </p>
                            </div>
                        </div>
                    </a>

                    {{-- Infrastruktur Master --}}
                    <a href="{{ route('masterdata.infrastructures.index') }}"
                        class="group block rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 hover:ring-teal-500 hover:shadow-md hover:-translate-y-1 transition-all dark:bg-gray-800 dark:ring-gray-700 dark:hover:ring-teal-500">
                        <div class="flex items-center gap-3">
                            <div
                                class="rounded-full bg-teal-100 p-2.5 text-teal-600 transition-colors group-hover:bg-teal-600 group-hover:text-white dark:bg-teal-900/50 dark:text-teal-400">
                                <i class="fas fa-boxes-stacked text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest dark:text-gray-400">
                                    Infrastruktur</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($totalInfraMaster) }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- SECTION 2: TASHIH SOAL (MONEV AKADEMIK) --}}
        {{-- ========================================================================= --}}
        @if(isset($showTashih) && $showTashih)
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white"><i
                            class="fas fa-file-signature text-emerald-500 mr-2"></i> Monev Akademik (Tashih Soal)</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    {{-- Antrean ACC (HANYA MUNCUL JIKA REVIEWER/KAPRODI/ADMIN) --}}
                    @if(isset($isReviewerMonev) && $isReviewerMonev)
                        <a href="{{ route('monevakademik.tashih.index') }}"
                            class="group block rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-rose-100"><i
                                            class="fas fa-bell mr-1"></i> Antrean ACC</p>
                                    <p class="mt-2 text-4xl font-extrabold text-white">{{ number_format($examNeedAcc) }}</p>
                                </div>
                                <div class="text-white/30 text-4xl group-hover:scale-110 transition-transform"><i
                                        class="fas fa-clipboard-check"></i></div>
                            </div>
                            <p class="mt-2 text-xs text-rose-100">Perlu review & persetujuan Anda</p>
                        </a>
                    @endif

                    {{-- Pengajuan Saya (Pending) --}}
                    <a href="{{ route('monevakademik.tashih.index') }}"
                        class="group block rounded-2xl border border-blue-200 bg-blue-50 p-5 hover:bg-blue-100 hover:shadow-md hover:-translate-y-1 transition-all dark:border-blue-800 dark:bg-blue-900/20 dark:hover:bg-blue-900/40">
                        <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">Pengajuan
                            Pending</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($examSubmitted) }}</p>
                        <p class="mt-2 text-[11px] text-gray-500 dark:text-gray-400">Menunggu antrean review</p>
                    </a>

                    {{-- Pengajuan Disetujui --}}
                    <a href="{{ route('monevakademik.tashih.index') }}"
                        class="group block rounded-2xl border border-emerald-200 bg-emerald-50 p-5 hover:bg-emerald-100 hover:shadow-md hover:-translate-y-1 transition-all dark:border-emerald-800 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/40">
                        <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Telah
                            Disetujui</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($examApproved) }}</p>
                        <p class="mt-2 text-[11px] text-gray-500 dark:text-gray-400">Siap dicetak/digunakan</p>
                    </a>

                    {{-- Bank Soal --}}
                    <a href="{{ route('monevakademik.banksoal.index') }}"
                        class="group block rounded-2xl border border-purple-200 bg-purple-50 p-5 hover:bg-purple-100 hover:shadow-md hover:-translate-y-1 transition-all dark:border-purple-800 dark:bg-purple-900/20 dark:hover:bg-purple-900/40">
                        <p class="text-xs font-semibold uppercase tracking-widest text-purple-600 dark:text-purple-400">Total
                            Bank Soal</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalBankSoal) }}
                            <span class="text-sm font-normal text-gray-500">Butir</span>
                        </p>
                        <p class="mt-2 text-[11px] text-gray-500 dark:text-gray-400">Klik untuk mencari butir soal</p>
                    </a>
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- SECTION 3: MANAJEMEN INFRASTRUKTUR --}}
        {{-- ========================================================================= --}}
        @if(isset($showInfra) && $showInfra)
            <div class="space-y-4 pt-2 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white"><i
                            class="fas fa-building text-amber-500 mr-2"></i> Peminjaman Infrastruktur</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    {{-- Antrean ACC Infra (HANYA REVIEWER) --}}
                    @if(isset($isReviewerInfra) && $isReviewerInfra)
                        <a href="{{ route('manajementinfrastruktur.persetujuan.index') }}"
                            class="group block rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-amber-100"><i
                                            class="fas fa-bell mr-1"></i> Antrean ACC Peminjaman</p>
                                    <p class="mt-2 text-4xl font-extrabold text-white">{{ number_format($infraNeedAcc) }}</p>
                                </div>
                                <div class="text-white/30 text-4xl group-hover:scale-110 transition-transform"><i
                                        class="fas fa-calendar-check"></i></div>
                            </div>
                        </a>
                    @endif

                    <a href="{{ route('manajementinfrastruktur.pengajuan.index') }}"
                        class="group block rounded-2xl border border-amber-200 bg-amber-50 p-5 hover:bg-amber-100 hover:shadow-md hover:-translate-y-1 transition-all dark:border-amber-800 dark:bg-amber-900/20 dark:hover:bg-amber-900/40">
                        <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-widest">Pengajuan
                            Saya</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($infraPending) }}</p>
                        <p class="mt-2 text-[11px] text-gray-500 dark:text-gray-400">Menunggu ACC</p>
                    </a>

                    <a href="{{ route('manajementinfrastruktur.pengajuan.index') }}"
                        class="group block rounded-2xl border border-blue-200 bg-blue-50 p-5 hover:bg-blue-100 hover:shadow-md hover:-translate-y-1 transition-all dark:border-blue-800 dark:bg-blue-900/20 dark:hover:bg-blue-900/40">
                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-widest">Sedang
                            Dipinjam</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($infraDipinjam) }}</p>
                        <p class="mt-2 text-[11px] text-gray-500 dark:text-gray-400">Barang/Fasilitas sedang digunakan</p>
                    </a>

                    <a href="{{ route('manajementinfrastruktur.pengajuan.index') }}"
                        class="group block rounded-2xl border border-gray-200 bg-gray-50 p-5 hover:bg-gray-100 hover:shadow-md hover:-translate-y-1 transition-all dark:border-gray-700 dark:bg-gray-800/50 dark:hover:bg-gray-800">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Dikembalikan
                        </p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($infraSelesai) }}</p>
                        <p class="mt-2 text-[11px] text-gray-500 dark:text-gray-400">Peminjaman selesai</p>
                    </a>
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- SECTION 4: REPOSITORI DOKUMEN --}}
        {{-- ========================================================================= --}}
        @if(isset($showDoc) && $showDoc)
            <div class="space-y-4 pt-2 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white"><i
                            class="fas fa-folder-open text-indigo-500 mr-2"></i> Repositori Dokumen</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    {{-- Antrean ACC Dokumen (HANYA REVIEWER) --}}
                    @if(isset($isReviewerDoc) && $isReviewerDoc)
                        <a href="{{ route('DocumentRepository.review.index') }}"
                            class="group block rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-100"><i
                                            class="fas fa-bell mr-1"></i> Antrean ACC Dokumen</p>
                                    <p class="mt-2 text-4xl font-extrabold text-white">{{ number_format($docNeedAcc) }}</p>
                                </div>
                                <div class="text-white/30 text-4xl group-hover:scale-110 transition-transform"><i
                                        class="fas fa-file-signature"></i></div>
                            </div>
                        </a>
                    @endif

                    <a href="{{ route('DocumentRepository.index') }}"
                        class="group block rounded-2xl border border-gray-200 bg-white p-5 hover:border-indigo-300 hover:shadow-md hover:-translate-y-1 transition-all dark:bg-gray-800 dark:border-gray-700 dark:hover:border-indigo-500">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Dokumen
                        </p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalDokumen) }}</p>
                    </a>

                    <a href="{{ route('DocumentRepository.index') }}"
                        class="group block rounded-2xl border border-gray-200 bg-white p-5 hover:border-amber-300 hover:shadow-md hover:-translate-y-1 transition-all dark:bg-gray-800 dark:border-gray-700 dark:hover:border-amber-500">
                        <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider">Menunggu Review</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($docPending) }}</p>
                    </a>

                    <a href="{{ route('DocumentRepository.index') }}"
                        class="group block rounded-2xl border border-gray-200 bg-white p-5 hover:border-emerald-300 hover:shadow-md hover:-translate-y-1 transition-all dark:bg-gray-800 dark:border-gray-700 dark:hover:border-emerald-500">
                        <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Telah Disetujui</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($docApproved) }}</p>
                    </a>
                </div>
            </div>
        @endif

        {{-- FALLBACK JIKA USER GAK PUNYA ROLE APAPUN --}}
        @if(!isset($isAdmin) && !isset($showTashih) && !isset($showInfra) && !isset($showDoc))
            <div
                class="rounded-2xl border border-gray-200 bg-white p-12 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800 mt-10">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                    <i class="fas fa-lock text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Akses Terbatas</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Akun Anda belum memiliki hak akses (role) ke modul manapun.
                    Silakan hubungi Administrator sistem.</p>
            </div>
        @endif

    </div>
@endsection