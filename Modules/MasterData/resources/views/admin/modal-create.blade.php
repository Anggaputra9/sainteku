@php
    $mahasiswaRoleId = $roles->first(fn ($r) => $r->role_code === 'MHS')?->id;
    $studentUserType = \Modules\MasterData\Http\Controllers\AdminController::STUDENT_USER_TYPE;

    // 1. Data Hierarki (Udah Bener Sesuai Database Lu)
    $kampus = $units->first(fn($u) => $u->id === $u->unit_parent);
    $kampusId = $kampus->id ?? 'UIN';
    $kampusName = $kampus->unit_name ?? 'UIN Prof. K.H. Saifuddin Zuhri';

    $fakultasList = $units->filter(fn($u) => $u->unit_parent === $kampusId && $u->id !== $kampusId)->values();
    $listFakultasArr = $fakultasList->map(fn($f) => ['id' => $f->id, 'name' => $f->unit_name])->toArray();

    $fakultasIds = $fakultasList->pluck('id')->toArray();
    $prodiList = $units->filter(fn($u) => in_array($u->unit_parent, $fakultasIds))->values();
    $listProdiArr = $prodiList->map(fn($p) => ['id' => $p->id, 'name' => $p->unit_name, 'parent' => $p->unit_parent])->toArray();
@endphp

<template x-teleport="#modal-root">

    <div x-data="openCreateModal()" @open-create-modal.window="openCreate = true; resetModal()" x-show="openCreate"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6 overflow-y-auto"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="openCreate = false"
        class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        {{-- MODAL HEADER --}}
        <div
            class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div
                    class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                    <i class="fa-solid fa-user-plus text-sm text-blue-600 dark:text-blue-400 sm:text-base"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl">
                        Tambah User Baru
                    </h3>
                    <p class="truncate whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 sm:text-sm"
                        x-text="createMode === 'bulk' ? 'Tambah banyak user sekaligus' : 'Daftarkan pengguna baru ke sistem Sainteku'">
                    </p>
                </div>
            </div>
            <div class="shrink-0 w-8 sm:w-9"></div>
        </div>

        {{-- MODE: MANUAL / BULK --}}
        <div class="shrink-0 flex gap-2 border-b border-gray-200 bg-white px-4 sm:px-8 py-2 dark:border-gray-700 dark:bg-[#1e293b]">
            <button type="button" @click="createMode = 'manual'"
                class="rounded-lg px-4 py-2 text-xs font-bold transition sm:text-sm"
                :class="createMode === 'manual' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'">
                Manual
            </button>
            <button type="button" @click="createMode = 'bulk'"
                class="rounded-lg px-4 py-2 text-xs font-bold transition sm:text-sm"
                :class="createMode === 'bulk' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'">
                Bulk
            </button>
        </div>

        {{-- FORM WRAPPER (Flex-1 & Overflow Hidden biar konten di dalam bisa scroll tapi footer tetep fixed) --}}
        <form id="create-user-form" x-show="createMode === 'manual'" action="{{ route('masterdata.admin.users.store') }}" method="POST"
            class="flex flex-col flex-1 overflow-hidden" @submit="submitForm">
            @csrf
            <input type="hidden" name="unit_id" :value="unitUtamaId">

            {{-- SCROLLABLE BODY --}}
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">

                {{-- Section: Informasi Dasar --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-user text-indigo-500"></i> Informasi Dasar
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Nama
                                Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
                        </div>
                        <div>
                            <label
                                class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Email
                                <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                placeholder="user@example.com" value="{{ old('email') }}">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">NIM
                                / NIP / NIK</label>
                            <input type="text" name="identity_id"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                placeholder="123456789" value="{{ old('identity_id') }}">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tipe
                                Pengguna <span class="text-red-500">*</span></label>
                            <select name="user_type" required x-model="userType"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Tipe --</option>
                                @foreach ($userTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('user_type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section: Kata Sandi Akun --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-lock text-indigo-500"></i> Kata Sandi Akun
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-[11px] font-bold text-gray-500 uppercase">Password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                placeholder="••••••••">
                        </div>
                        <div>
                            <label class="mb-2 block text-[11px] font-bold text-gray-500 uppercase">Konfirmasi Password
                                <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                {{-- Section: Penempatan Unit Utama --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-building text-indigo-500"></i> Penempatan Unit Utama
                        </h4>
                    </div>
                    <div class="p-5 space-y-5">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tingkat Unit
                                <span class="text-red-500">*</span></label>
                            <select name="tingkatUtama" x-model="tingkatUtama" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Tingkat / Strata --</option>
                                <option value="kampus">Universitas / Institut</option>
                                <option value="fakultas">Fakultas</option>
                                <option value="prodi">Program Studi</option>
                            </select>
                        </div>

                        <div x-show="tingkatUtama === 'kampus'" x-cloak x-transition>
                            <div
                                class="rounded-lg border border-indigo-200 bg-indigo-100 px-4 py-3 text-sm text-indigo-800 dark:border-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                <span class="font-bold">Universitas Terpilih:</span> <span x-text="kampusName"></span>
                            </div>
                        </div>

                        <div x-show="tingkatUtama === 'fakultas'" x-cloak x-transition>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Pilih Fakultas
                                <span class="text-red-500">*</span></label>
                            <select x-model="unitUtamaId" x-bind:required="tingkatUtama === 'fakultas'"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Fakultas --</option>
                                <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                    <option :value="fakultas.id" x-text="fakultas.name"></option>
                                </template>
                            </select>
                        </div>

                        <div x-show="tingkatUtama === 'prodi'" x-cloak x-transition
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 rounded-lg bg-gray-50/50 p-4 ring-1 ring-gray-200 dark:bg-[#0f172a]/50 dark:ring-gray-700">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Filter
                                    Fakultas</label>
                                <select x-model="filterFakultasUtama"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Tampilkan Semua Prodi --</option>
                                    <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                        <option :value="fakultas.id" x-text="fakultas.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Pilih
                                    Prodi <span class="text-red-500">*</span></label>
                                <select x-model="unitUtamaId" x-bind:required="tingkatUtama === 'prodi'"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Pilih Prodi --</option>
                                    <template
                                        x-for="prodi in listProdi.filter(p => filterFakultasUtama === '' || p.parent === filterFakultasUtama)"
                                        :key="prodi.id">
                                        <option :value="prodi.id" x-text="prodi.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Unit Tambahan / Rangkap --}}
                <template x-if="!isMahasiswa() && tingkatUtama !== '' && unitUtamaId !== ''">
                <div
                    class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-building-user text-indigo-500"></i> Unit Tambahan / Rangkap <span
                                class="font-normal lowercase text-xs text-gray-500">(Opsional)</span>
                        </h4>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div x-show="tingkatUtama === 'fakultas'">
                                <select x-model="tingkatTambahan"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Tampilkan Semua Tingkat --</option>
                                    <option value="fakultas">Fakultas</option>
                                    <option value="prodi">Program Studi</option>
                                </select>
                            </div>

                            <div x-show="tingkatTambahan === 'prodi' || tingkatTambahan === ''" x-cloak x-transition>
                                <select x-model="filterFakultasTambahan"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Tampilkan Semua Fakultas --</option>
                                    <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                        <option x-show="tingkatUtama !== 'fakultas' || fakultas.id !== unitUtamaId"
                                            :value="fakultas.id" x-text="fakultas.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-[#0f172a]/50">
                            <h5 class="font-bold text-sm text-gray-800 dark:text-gray-200 mb-3">Pilih Unit Tambahan</h5>
                            <div
                                class="max-h-60 overflow-y-auto rounded-lg bg-white p-4 ring-1 ring-gray-200 dark:bg-[#1e293b] dark:ring-gray-600 space-y-6 custom-scrollbar">

                                <div
                                    x-show="tingkatUtama !== 'prodi' && (tingkatTambahan === '' || tingkatTambahan === 'fakultas')">
                                    <h5
                                        class="font-bold text-sm text-gray-800 dark:text-gray-200 mb-3 border-b border-gray-100 pb-1 dark:border-gray-700">
                                        Tingkat Fakultas</h5>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pl-2">
                                        <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                            <label x-show="fakultas.id !== unitUtamaId"
                                                class="flex items-start gap-3 cursor-pointer group">
                                                <input type="checkbox" name="unit_tambahan[]" :value="fakultas.id"
                                                    class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700">
                                                <span
                                                    class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition"
                                                    x-text="fakultas.name"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>

                                <div x-show="tingkatTambahan === '' || tingkatTambahan === 'prodi'">
                                    <h5
                                        class="font-bold text-sm text-gray-800 dark:text-gray-200 mb-3 border-b border-gray-100 pb-1 dark:border-gray-700 mt-2">
                                        Tingkat Program Studi</h5>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pl-2">
                                        <template x-for="prodi in listProdi" :key="prodi.id">
                                            <label
                                                x-show="(filterFakultasTambahan === '' || prodi.parent === filterFakultasTambahan) && (tingkatUtama !== 'fakultas' || prodi.parent !== unitUtamaId) && prodi.id !== unitUtamaId"
                                                class="flex items-start gap-3 cursor-pointer group">
                                                <input type="checkbox" name="unit_tambahan[]" :value="prodi.id"
                                                    class="mt-0.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700">
                                                <span
                                                    class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition"
                                                    x-text="prodi.name"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                </template>

                {{-- Section: Hak Akses / Role --}}
                <template x-if="isMahasiswa()">
                    <div
                        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-indigo-500"></i> Hak Akses / Role
                            </h4>
                        </div>
                        <div class="p-5">
                            <div
                                class="flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:border-blue-800/50 dark:bg-blue-900/20 dark:text-blue-300">
                                <i class="fa-solid fa-lock text-blue-500"></i>
                                <span>Otomatis <strong>Mahasiswa</strong> sesuai tipe pengguna.</span>
                            </div>
                            <input type="hidden" name="role_ids[]" :value="mahasiswaRoleId">
                        </div>
                    </div>
                </template>

                <template x-if="!isMahasiswa()">
                    <div
                        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-indigo-500"></i> Hak Akses / Role <span
                                    class="text-red-500">*</span>
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                            @foreach ($roles as $role)
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox" name="role_ids[]" value="{{ $role->id }}"
                                        class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span
                                        class="text-sm leading-snug text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition">
                                        {{ $role->role_name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </template>

                {{-- Section: Status Akun --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-toggle-on text-indigo-500"></i> Status Akun
                        </h4>
                    </div>
                    <div class="p-5">
                        <label class="relative inline-flex cursor-pointer items-center gap-3">
                            <input type="checkbox" name="is_active" value="1" class="peer sr-only" checked>
                            <div
                                class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700">
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Aktifkan Akun
                                Langsung</span>
                        </label>
                    </div>
                </div>

            </div>

            {{-- FIXED FOOTER --}}
            <div
                class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                    <div class="flex shrink-0 items-center">
                        <button type="button" @click="openCreate = false"
                            class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 shadow-sm hover:bg-gray-300 focus:ring-4 focus:ring-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                    <div class="flex shrink-0 flex-row flex-nowrap items-center justify-end gap-2 sm:gap-3">
                        <button type="submit"
                            class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 hover:shadow-lg focus:ring-4 focus:ring-blue-500/30 sm:gap-2 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- BULK MODE --}}
        <div x-show="createMode === 'bulk'" x-cloak class="flex flex-col flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-indigo-500"></i> Pengaturan Batch
                        </h4>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tipe
                                Pengguna <span class="text-red-500">*</span></label>
                            <select x-model="userType" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Tipe --</option>
                                @foreach ($userTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tingkat Unit
                                <span class="text-red-500">*</span></label>
                            <select x-model="tingkatUtama"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Tingkat / Strata --</option>
                                <option value="kampus">Universitas / Institut</option>
                                <option value="fakultas">Fakultas</option>
                                <option value="prodi">Program Studi</option>
                            </select>
                        </div>

                        <div x-show="tingkatUtama === 'kampus'" x-cloak>
                            <div
                                class="rounded-lg border border-indigo-200 bg-indigo-100 px-4 py-3 text-sm text-indigo-800 dark:border-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                <span class="font-bold">Universitas Terpilih:</span> <span x-text="kampusName"></span>
                            </div>
                        </div>

                        <div x-show="tingkatUtama === 'fakultas'" x-cloak>
                            <select x-model="unitUtamaId"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Fakultas --</option>
                                <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                    <option :value="fakultas.id" x-text="fakultas.name"></option>
                                </template>
                            </select>
                        </div>

                        <div x-show="tingkatUtama === 'prodi'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select x-model="filterFakultasUtama"
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Filter Fakultas --</option>
                                <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                    <option :value="fakultas.id" x-text="fakultas.name"></option>
                                </template>
                            </select>
                            <select x-model="unitUtamaId"
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Prodi --</option>
                                <template
                                    x-for="prodi in listProdi.filter(p => filterFakultasUtama === '' || p.parent === filterFakultasUtama)"
                                    :key="prodi.id">
                                    <option :value="prodi.id" x-text="prodi.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <template x-if="isMahasiswa()">
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-indigo-500"></i> Hak Akses / Role
                            </h4>
                        </div>
                        <div class="p-5">
                            <div
                                class="flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:border-blue-800/50 dark:bg-blue-900/20 dark:text-blue-300">
                                <i class="fa-solid fa-lock text-blue-500"></i>
                                <span>Otomatis <strong>Mahasiswa</strong>. Email: <code class="text-xs">{nim}@mhs.uinsaizu.ac.id</code></span>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="!isMahasiswa() && userType !== ''">
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-indigo-500"></i> Hak Akses / Role <span class="text-red-500">*</span>
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                            @foreach ($roles as $role)
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox" :value="{{ $role->id }}" x-model="bulkRoleIds"
                                        class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $role->role_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </template>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-2">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-users text-indigo-500"></i> Input Bulk
                        </h4>
                        <div class="flex flex-wrap items-center gap-2">
                            <a :href="templateUrl" target="_blank"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
                                <i class="fa-solid fa-download"></i> Template
                            </a>
                            <label
                                class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 dark:border-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                <i class="fa-solid fa-file-excel"></i> Excel/CSV
                                <input type="file" class="hidden" accept=".csv,.xlsx,.xls" @change="handleBulkFile($event)">
                            </label>
                        </div>
                    </div>
                    <div class="p-5 space-y-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-show="isMahasiswa()">
                            Ketik bebas atau satu baris per user. Contoh:
                            <span class="font-mono text-gray-700 dark:text-gray-300">angga wicaksono 123456789 rizal fakhri nur riski 0987654321</span>
                            — password otomatis = NIM.
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-show="!isMahasiswa() && userType !== ''">
                            Satu baris per user: <span class="font-mono">Nama NIP Email</span> — password otomatis = NIP/NIK.
                        </p>
                        <textarea x-model="bulkText" rows="8"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 text-sm font-mono outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                            placeholder="Tempel data bulk di sini..."></textarea>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="p-5">
                        <label class="relative inline-flex cursor-pointer items-center gap-3">
                            <input type="checkbox" class="peer sr-only" x-model="bulkIsActive">
                            <div
                                class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-gray-700">
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Aktifkan akun langsung</span>
                        </label>
                    </div>
                </div>

                <template x-if="bulkResult">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="mb-3 flex flex-wrap items-center gap-3 text-sm font-bold">
                            <span class="text-emerald-600 dark:text-emerald-400">
                                <i class="fa-solid fa-check-circle"></i>
                                <span x-text="bulkResult.success_count"></span> berhasil
                            </span>
                            <span class="text-red-600 dark:text-red-400" x-show="bulkResult.failed_count > 0">
                                <i class="fa-solid fa-circle-xmark"></i>
                                <span x-text="bulkResult.failed_count"></span> gagal
                            </span>
                        </div>
                        <div class="max-h-40 overflow-y-auto custom-scrollbar" x-show="bulkResult.log && bulkResult.log.length > 0">
                            <table class="w-full text-left text-xs text-gray-600 dark:text-gray-400">
                                <thead class="text-[10px] uppercase text-gray-400">
                                    <tr>
                                        <th class="pb-2 pr-2">Nama</th>
                                        <th class="pb-2 pr-2">NIM/NIP</th>
                                        <th class="pb-2">Alasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, idx) in bulkResult.log" :key="idx">
                                        <tr class="border-t border-gray-100 dark:border-gray-700">
                                            <td class="py-1.5 pr-2" x-text="row.name"></td>
                                            <td class="py-1.5 pr-2" x-text="row.identity_id"></td>
                                            <td class="py-1.5 text-red-600 dark:text-red-400" x-text="row.reason"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-2 text-xs text-amber-700 dark:text-amber-400" x-show="bulkResult.failed_count > 0">
                            Baris gagal tetap ada di textarea. Perbaiki lalu simpan lagi.
                        </p>
                    </div>
                </template>
            </div>

            <div
                class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                    <div class="flex shrink-0 items-center">
                        <button type="button" @click="openCreate = false"
                            class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 shadow-sm hover:bg-gray-300 focus:ring-4 focus:ring-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 sm:gap-2 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                    <div class="flex shrink-0 flex-row flex-nowrap items-center justify-end gap-2 sm:gap-3">
                        <button type="button" @click="submitBulk()" :disabled="bulkImporting"
                            class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 disabled:opacity-60 sm:gap-2 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas" :class="bulkImporting ? 'fa-circle-notch fa-spin' : 'fa-save'"></i>
                            <span x-text="bulkImporting ? 'Proses...' : 'Simpan'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    function openCreateModal() {
        return {
            openCreate: false,
            createMode: 'manual',
            userType: '',
            tingkatUtama: '',
            filterFakultasUtama: '',
            unitUtamaId: '',
            kampusId: '{{ $kampusId }}',
            kampusName: '{{ $kampusName }}',
            listFakultas: @json($listFakultasArr),
            listProdi: @json($listProdiArr),
            tingkatTambahan: '',
            filterFakultasTambahan: '',
            mahasiswaRoleId: {{ $mahasiswaRoleId ?? 'null' }},
            studentUserType: @json($studentUserType),
            bulkText: '',
            bulkRoleIds: [],
            bulkIsActive: true,
            bulkImporting: false,
            bulkResult: null,
            bulkStoreUrl: @json(route('masterdata.admin.users.bulk.store')),
            templateBaseUrl: @json(route('masterdata.admin.users.bulk.template')),

            get templateUrl() {
                const type = this.userType || this.studentUserType;
                return `${this.templateBaseUrl}?type=${encodeURIComponent(type)}`;
            },

            isMahasiswa() {
                return this.userType === this.studentUserType;
            },

            resetModal() {
                this.createMode = 'manual';
                this.bulkText = '';
                this.bulkRoleIds = [];
                this.bulkIsActive = true;
                this.bulkResult = null;
                this.bulkImporting = false;
            },

            clearUnitTambahanSelections() {
                document.querySelectorAll('#create-user-form input[name="unit_tambahan[]"]').forEach(cb => {
                    cb.checked = false;
                });
            },

            init() {
                this.$watch('userType', () => {
                    if (this.isMahasiswa()) {
                        this.bulkRoleIds = this.mahasiswaRoleId ? [String(this.mahasiswaRoleId)] : [];
                        this.clearUnitTambahanSelections();
                    } else {
                        this.bulkRoleIds = [];
                    }
                });

                this.$watch('tingkatUtama', value => {
                    this.filterFakultasUtama = '';
                    this.filterFakultasTambahan = '';
                    if (value === 'kampus') {
                        this.unitUtamaId = this.kampusId;
                        this.tingkatTambahan = '';
                    } else if (value === 'prodi') {
                        this.unitUtamaId = '';
                        this.tingkatTambahan = 'prodi';
                    } else {
                        this.unitUtamaId = '';
                        this.tingkatTambahan = '';
                    }
                    this.clearUnitTambahanSelections();
                });
            },

            submitForm(e) {
                this.openCreate = false;
                setTimeout(() => e.target.submit(), 100);
            },

            async handleBulkFile(event) {
                const file = event.target.files?.[0];
                event.target.value = '';
                if (!file) return;

                if (file.name.toLowerCase().endsWith('.csv')) {
                    const text = await file.text();
                    this.appendBulkLines(this.parseCsvToLines(text));
                    return;
                }

                if (typeof XLSX === 'undefined') {
                    alert('Library Excel belum dimuat. Muat ulang halaman.');
                    return;
                }

                const buffer = await file.arrayBuffer();
                const workbook = XLSX.read(buffer, { type: 'array' });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });
                this.appendBulkLines(this.rowsToBulkLines(rows));
            },

            parseCsvToLines(text) {
                const rows = text.split(/\r?\n/).map(line => line.split(/[,;]\s*/));
                return this.rowsToBulkLines(rows);
            },

            rowsToBulkLines(rows) {
                const lines = [];
                rows.forEach((row, index) => {
                    const cells = (row || []).map(cell => String(cell).trim()).filter(Boolean);
                    if (cells.length === 0) return;

                    const header = cells.map(c => c.toLowerCase());
                    if (index === 0 && (header.includes('nama') || header.includes('nim') || header.includes('nip'))) {
                        return;
                    }

                    if (this.isMahasiswa()) {
                        if (cells.length >= 2) {
                            lines.push(`${cells[0]} ${cells[1]}`);
                        }
                    } else if (cells.length >= 3) {
                        lines.push(`${cells[0]} ${cells[1]} ${cells[2]}`);
                    }
                });

                return lines;
            },

            appendBulkLines(lines) {
                if (!lines.length) return;
                const chunk = lines.join('\n');
                this.bulkText = this.bulkText.trim() ? `${this.bulkText.trim()}\n${chunk}` : chunk;
            },

            validateBulkSetup() {
                if (!this.userType) return 'Pilih tipe pengguna.';
                if (!this.tingkatUtama) return 'Pilih tingkat unit.';
                if (!this.unitUtamaId) return 'Pilih unit utama.';
                if (!this.isMahasiswa() && this.bulkRoleIds.length === 0) return 'Pilih minimal satu role.';
                if (!this.bulkText.trim()) return 'Input bulk masih kosong.';
                return null;
            },

            async submitBulk() {
                const error = this.validateBulkSetup();
                if (error) {
                    alert(error);
                    return;
                }

                this.bulkImporting = true;

                try {
                    const roleIds = this.isMahasiswa()
                        ? (this.mahasiswaRoleId ? [this.mahasiswaRoleId] : [])
                        : this.bulkRoleIds.map(id => parseInt(id, 10));

                    const response = await fetch(this.bulkStoreUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({
                            user_type: this.userType,
                            tingkatUtama: this.tingkatUtama,
                            unit_id: this.unitUtamaId,
                            role_ids: roleIds,
                            is_active: this.bulkIsActive,
                            bulk_text: this.bulkText,
                        }),
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        alert(result.message || 'Gagal import bulk user.');
                        return;
                    }

                    this.bulkResult = result;
                    this.bulkText = result.failed_text || '';

                    window.dispatchEvent(new CustomEvent('users-bulk-imported', {
                        bubbles: true,
                        detail: result,
                    }));

                    if (result.success_count > 0 && result.failed_count === 0) {
                        this.openCreate = false;
                        this.resetModal();
                    }
                } catch (e) {
                    console.error(e);
                    alert('Terjadi kesalahan saat import bulk.');
                } finally {
                    this.bulkImporting = false;
                }
            }
        }
    }

</script>