@extends('layouts.app')

@section('content')
    @php($moduleMap = $modules->pluck('description', 'id'))
    {{--
        Manajemen Menu — gaya selaras dengan MonevAkademik/Tashih:
        - Header + breadcrumb
        - Filter card
        - Tabel ringkas dengan satu tombol "Detail"
        - Modal Detail jadi tempat semua aksi (Edit/Hapus/Toggle aktif).
        - Modal Tambah berdiri sendiri dipanggil dari header.
    --}}
    <div class="space-y-6" x-data="menuManagementApp()" x-init="init()" x-cloak>

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-bars-staggered text-indigo-500"></i> Manajemen Menu
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Pengaturan Aplikasi /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">Manajemen Menu</li>
                    </ol>
                </nav>
            </div>
            <button type="button" @click="openCreate = true"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 transition">
                <i class="fa-solid fa-plus"></i> Tambah Menu
            </button>
        </div>

        {{-- ================= ALERTS ================= --}}
        @if (session('success'))
            <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="flex items-start w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3 mt-0.5"></i>
                <div class="text-sm font-bold text-red-700 dark:text-red-400">
                    @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            </div>
        @endif

        {{-- ================= FILTERS ================= --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pencarian</label>
                    <input type="text" x-model="filters.search" @input.debounce.500ms="fetchMenus(1)" placeholder="Cari nama, route, icon..."
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                    <select x-model="filters.status" @change="fetchMenus(1)"
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="">Semua</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Parent</label>
                    <select x-model="filters.parent" @change="fetchMenus(1)"
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="">Semua</option>
                        <option value="root">Root</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}">{{ $p->menu_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Modul</label>
                    <select x-model="filters.module_id" @change="fetchMenus(1)"
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="">Semua</option>
                        @foreach($modules as $m)
                            <option value="{{ $m->id }}">{{ $m->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="button" @click="resetFilters()"
                        class="w-full inline-flex justify-center rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Reset</button>
                </div>
            </div>
        </div>

        {{-- ================= TABLE (gaya tashih) ================= --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 relative">
            <div x-show="isLoading" class="absolute inset-0 z-10 bg-white/70 dark:bg-gray-900/70 backdrop-blur-sm flex items-center justify-center">
                <i class="fas fa-circle-notch fa-spin text-3xl text-indigo-600"></i>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-1/3">Menu</th>
                            <th class="px-6 py-4 font-semibold text-center">Parent &amp; Modul</th>
                            <th class="px-6 py-4 font-semibold text-center">Urutan</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="menu in menusList" :key="menu.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                {{-- Menu: nama besar + route name kecil --}}
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                        <i :class="menu.menu_icon || 'fa-solid fa-circle'" class="text-indigo-500"></i>
                                        <span x-text="menu.menu_name"></span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1" x-text="menu.menu_link || '— tanpa route —'"></div>
                                </td>

                                {{-- Parent & Modul --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-gray-700 dark:text-gray-300" x-text="menu.parent?.menu_name || 'Root'"></span>
                                    <div class="text-xs text-gray-500 mt-0.5" x-text="moduleMap[menu.module_id] || '— tanpa modul —'"></div>
                                </td>

                                {{-- Urutan --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-gray-900 dark:text-white tabular-nums" x-text="menu.order_no"></span>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    <span x-show="menu.is_active"
                                        class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border bg-green-100 text-green-800 border-green-200">AKTIF</span>
                                    <span x-show="!menu.is_active"
                                        class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border bg-gray-100 text-gray-700 border-gray-200">NONAKTIF</span>
                                </td>

                                {{-- Aksi: tombol Detail putih dengan icon biru --}}
                                <td class="px-6 py-4 text-center">
                                    <button type="button" @click="openDetailModal(menu)"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                        <i class="fa-solid fa-eye text-blue-500"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="menusList.length === 0 && !isLoading">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-folder-open text-3xl mb-3 opacity-50"></i><br>Belum ada menu.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- pagination --}}
        <div class="px-6 py-4 border rounded-xl border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4"
             x-show="pagination.total > 0 && !isLoading">
            <span class="text-sm text-gray-500">Menampilkan <b x-text="pagination.from"></b> - <b x-text="pagination.to"></b> dari <b x-text="pagination.total"></b> data</span>
            <div class="flex gap-2">
                <button @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url"
                    class="px-4 py-2 rounded-lg border bg-white disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700"><i class="fa-solid fa-chevron-left"></i></button>
                <button @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url"
                    class="px-4 py-2 rounded-lg border bg-white disabled:opacity-50 dark:bg-gray-800 dark:border-gray-700"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        {{-- ================= MODAL CREATE ================= --}}
        {{-- Modal Tambah: form input bersih, header indigo seperti modal email settings --}}
        <template x-teleport="#modal-root">
            <div x-show="openCreate"
            class="app-modal-overlay fixed inset-0 flex items-center justify-center p-3 sm:p-6 overflow-y-auto backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="openCreate = false"
                class="relative w-full max-w-3xl rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">
                <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-bars-staggered text-indigo-500"></i> Tambah Menu
                    </h3>
                    <button type="button" @click="openCreate = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('settings.menu.store') }}" class="flex-1 flex flex-col overflow-hidden">
                    @csrf
                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Nama Menu *</label>
                                <input name="menu_name" required maxlength="50"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Route Name</label>
                                <input name="menu_link" maxlength="100" placeholder="cth: settings.email.index"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Icon</label>
                                <input name="menu_icon" maxlength="100" placeholder="fa-solid fa-database"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Parent</label>
                                <select name="parent_id"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <option value="">Root Menu</option>
                                    @foreach($parents as $p)<option value="{{ $p->id }}">{{ $p->menu_name }}</option>@endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Modul</label>
                                <select name="module_id"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                                    <option value="">Tanpa Modul</option>
                                    @foreach($modules as $m)<option value="{{ $m->id }}">{{ $m->description }}</option>@endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Urutan</label>
                                <input name="order_no" type="number" value="0" min="0"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm dark:bg-[#1e293b] dark:border-gray-600 dark:text-white">
                            </div>
                            <label class="md:col-span-2 flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-[#1e293b]">
                                <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Aktifkan menu ini</span>
                            </label>
                        </div>
                    </div>

                    <div class="shrink-0 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700 flex justify-end gap-3">
                        <button type="button" @click="openCreate = false"
                            class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Batal</button>
                        <button class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </template>

        {{-- ================= MODAL DETAIL ================= --}}
        {{--
            Mengikuti pola tashih: tampil detail data + tombol Edit langsung di-form yang sama
            (toggle isEditing). Tombol Hapus di footer kiri, Edit/Simpan di kanan.
        --}}
        <div x-show="openDetail"
            class="fixed inset-0 flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
            x-transition x-cloak>
            <div @click.away="closeDetail()"
                class="relative w-full max-w-2xl rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 flex flex-col max-h-[95vh] overflow-hidden">
                <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <div class="pr-4">
                        <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 border border-indigo-100 dark:bg-indigo-900/30 dark:border-indigo-800/50 shrink-0">
                                <i :class="selected.menu_icon || 'fa-solid fa-circle'" class="text-indigo-600 dark:text-indigo-400"></i>
                            </span>
                            <span x-text="selected.menu_name"></span>
                        </h3>
                        <p class="text-xs text-gray-500 mt-1" x-text="isEditing ? 'Edit data menu — simpan untuk konfirmasi.' : 'Detail menu & aksi pengelolaan.'"></p>
                    </div>
                    <button type="button" @click="closeDetail()" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                {{-- Form yang sama dipakai untuk Detail dan Edit; mode edit cuma toggle disabled --}}
                <form method="POST" :action="updateUrl" class="flex-1 flex flex-col overflow-hidden">
                    @csrf @method('PUT')

                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Nama Menu *</label>
                                <input name="menu_name" x-model="selected.menu_name" :disabled="!isEditing" required maxlength="50"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm disabled:bg-gray-100 disabled:text-gray-700 dark:bg-[#1e293b] dark:border-gray-600 dark:text-white dark:disabled:bg-gray-800">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Route Name</label>
                                <input name="menu_link" x-model="selected.menu_link" :disabled="!isEditing" maxlength="100"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm disabled:bg-gray-100 disabled:text-gray-700 dark:bg-[#1e293b] dark:border-gray-600 dark:text-white dark:disabled:bg-gray-800">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Icon</label>
                                <input name="menu_icon" x-model="selected.menu_icon" :disabled="!isEditing" maxlength="100"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm disabled:bg-gray-100 disabled:text-gray-700 dark:bg-[#1e293b] dark:border-gray-600 dark:text-white dark:disabled:bg-gray-800">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Parent</label>
                                <select name="parent_id" x-model="selected.parent_id" :disabled="!isEditing"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm disabled:bg-gray-100 disabled:text-gray-700 dark:bg-[#1e293b] dark:border-gray-600 dark:text-white dark:disabled:bg-gray-800">
                                    <option value="">Root Menu</option>
                                    @foreach($parents as $p)<option value="{{ $p->id }}">{{ $p->menu_name }}</option>@endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Modul</label>
                                <select name="module_id" x-model="selected.module_id" :disabled="!isEditing"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm disabled:bg-gray-100 disabled:text-gray-700 dark:bg-[#1e293b] dark:border-gray-600 dark:text-white dark:disabled:bg-gray-800">
                                    <option value="">Tanpa Modul</option>
                                    @foreach($modules as $m)<option value="{{ $m->id }}">{{ $m->description }}</option>@endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Urutan</label>
                                <input name="order_no" type="number" min="0" x-model="selected.order_no" :disabled="!isEditing"
                                    class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm disabled:bg-gray-100 disabled:text-gray-700 dark:bg-[#1e293b] dark:border-gray-600 dark:text-white dark:disabled:bg-gray-800">
                            </div>
                            <label class="md:col-span-2 flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-[#1e293b]"
                                   :class="!isEditing && 'opacity-80'">
                                <input type="checkbox" name="is_active" value="1" x-model="selected.is_active" :disabled="!isEditing"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                    <span x-show="selected.is_active">Menu aktif &amp; tampil di sidebar</span>
                                    <span x-show="!selected.is_active">Menu nonaktif</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Footer aksi --}}
                    <div class="shrink-0 border-t border-gray-200 bg-white px-4 sm:px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                            <button type="button" @click="$refs.deleteForm.submit()"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm font-bold text-red-700 border border-red-200 hover:bg-red-100">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <button type="button" @click="closeDetail()"
                                    class="rounded-xl bg-gray-200 px-5 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Batal</button>
                                <template x-if="!isEditing">
                                    <button type="button" @click="isEditing = true"
                                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-indigo-700">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                </template>
                                <template x-if="isEditing">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-indigo-700">
                                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Form delete terpisah, dipicu dari tombol Hapus di footer --}}
                <form x-ref="deleteForm" method="POST" :action="deleteUrl"
                      onsubmit="return confirm('Hapus menu ini?')" class="hidden">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    {{-- Style scrollbar selaras dengan halaman lain --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
    </style>

    <script>
        function menuManagementApp() {
            return {
                openCreate: false,
                openDetail: false,
                isEditing: false,
                isLoading: false,

                filters: { search: '', status: '', parent: '', module_id: '' },
                menusList: @json($menus->items()),
                moduleMap: @json($moduleMap),
                pagination: @json($menus->toArray()),

                selected: {},
                updateUrl: '',
                deleteUrl: '',

                init() {},

                async fetchMenus(page = 1) {
                    this.isLoading = true;
                    const url = new URL(`{{ route('settings.menu.index') }}`);
                    url.searchParams.append('page', page);
                    Object.entries(this.filters).forEach(([k, v]) => { if (v) url.searchParams.append(k, v); });
                    try {
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        const result = await res.json();
                        this.menusList = result.data || [];
                        this.pagination = result;
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.isLoading = false;
                    }
                },

                changePage(page) {
                    this.fetchMenus(page);
                    window.scrollTo({ top: 150, behavior: 'smooth' });
                },

                resetFilters() {
                    this.filters = { search: '', status: '', parent: '', module_id: '' };
                    this.fetchMenus(1);
                },

                openDetailModal(menu) {
                    // Salin objek supaya x-model nggak mutate row di tabel sampai user submit
                    this.selected = {
                        ...menu,
                        is_active: !!menu.is_active,
                        parent_id: menu.parent_id ? String(menu.parent_id) : '',
                        module_id: menu.module_id ? String(menu.module_id) : '',
                    };
                    this.updateUrl = `{{ url('settings/menu') }}/${menu.id}`;
                    this.deleteUrl = `{{ url('settings/menu') }}/${menu.id}`;
                    this.isEditing = false;
                    this.openDetail = true;
                },

                closeDetail() {
                    this.openDetail = false;
                    this.isEditing = false;
                }
            };
        }
    </script>
@endsection