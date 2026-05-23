@extends('layouts.app')

@section('content')
    @php($moduleMap = $modules->pluck('description', 'id'))
    <div class="space-y-6" x-data="menuManagementApp()" x-init="init()" x-cloak>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-bars-staggered text-indigo-500"></i> Manajemen Menu
                </h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Administrator /</li>
                        <li class="text-indigo-600 dark:text-indigo-400">Sidebar Menu</li>
                    </ol>
                </nav>
            </div>
            <button type="button" @click="openCreate = true"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 transition">
                <i class="fa-solid fa-plus"></i> Tambah Menu
            </button>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pencarian</label>
                    <input type="text" x-model="filters.search" @input.debounce.500ms="fetchMenus(1)" placeholder="Cari nama, route, icon..." class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                    <select x-model="filters.status" @change="fetchMenus(1)" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="">Semua</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Parent</label>
                    <select x-model="filters.parent" @change="fetchMenus(1)" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="">Semua</option>
                        <option value="root">Root</option>
                        @foreach($parents as $p)<option value="{{ $p->id }}">{{ $p->menu_name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Modul</label>
                    <select x-model="filters.module_id" @change="fetchMenus(1)" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                        <option value="">Semua</option>
                        @foreach($modules as $m)<option value="{{ $m->id }}">{{ $m->description }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <button type="button" @click="resetFilters()" class="w-full inline-flex justify-center rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Reset</button>
                </div>
            </div>
        </div>

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

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 relative">
            <div x-show="isLoading" class="absolute inset-0 z-10 bg-white/70 dark:bg-gray-900/70 backdrop-blur-sm flex items-center justify-center"><i class="fas fa-circle-notch fa-spin text-3xl text-indigo-600"></i></div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Menu</th>
                            <th class="px-6 py-4 font-semibold">Route</th>
                            <th class="px-6 py-4 font-semibold">Parent</th>
                            <th class="px-6 py-4 font-semibold">Modul</th>
                            <th class="px-6 py-4 font-semibold text-center">Urut</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        <template x-for="menu in menusList" :key="menu.id">
                            <tr class="hover:bg-gray-50 transition dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <i :class="menu.menu_icon || 'fa-solid fa-circle'" class="text-indigo-500"></i>
                                        <span x-text="menu.menu_name"></span>
                                    </div>
                                     <div class="text-xs text-gray-500 mt-1">ID: <span x-text="menu.id"></span></div>
                                </td>
                                <td class="px-6 py-4 font-medium" x-text="menu.menu_link || '-'"></td>
                                <td class="px-6 py-4" x-text="menu.parent?.menu_name || 'Root'"></td>
                                <td class="px-6 py-4" x-text="moduleMap[menu.module_id] || '-'"></td>
                                <td class="px-6 py-4 text-center font-bold text-gray-900 dark:text-white" x-text="menu.order_no"></td>
                                <td class="px-6 py-4 text-center">
                                    <span x-show="menu.is_active" class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border bg-green-100 text-green-800 border-green-200">Aktif</span>
                                    <span x-show="!menu.is_active" class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border bg-gray-100 text-gray-700 border-gray-200">Nonaktif</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" @click="openDetailModal(menu)"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                        <i class="fa-solid fa-eye text-blue-500"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="menusList.length === 0 && !isLoading"><td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class="fas fa-folder-open text-3xl mb-3 opacity-50"></i><br>Belum ada menu.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="px-6 py-4 border rounded-xl border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4" x-show="pagination.total > 0 && !isLoading">
            <span class="text-sm text-gray-500">Menampilkan <b x-text="pagination.from"></b> - <b x-text="pagination.to"></b> dari <b x-text="pagination.total"></b> data</span>
            <div class="flex gap-2">
                <button @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url" class="px-4 py-2 rounded-lg border bg-white disabled:opacity-50"><i class="fa-solid fa-chevron-left"></i></button>
                <button @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url" class="px-4 py-2 rounded-lg border bg-white disabled:opacity-50"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        {{-- Modal Tambah --}}
        <div x-show="openCreate"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>
            <div @click.away="openCreate = false"
                class="relative w-full max-w-4xl transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all flex flex-col max-h-[90dvh] sm:max-h-[95vh] overflow-hidden">
                <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                    <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2 sm:gap-3"><span class="leading-tight">Tambah Menu</span>
                    </h3>
                </div>
                <form method="POST" action="{{ route('menu-management.store') }}" class="flex-1 flex flex-col overflow-hidden relative">
                    @csrf
                    <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 custom-scrollbar bg-slate-50 dark:bg-[#0f172a]">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm dark:border-gray-700/50 dark:bg-[#1e293b] relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pl-2">
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nama Menu</label><input name="menu_name" required class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Route Name</label><input name="menu_link" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Icon</label><input name="menu_icon" placeholder="fa-solid fa-database" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Parent</label><select name="parent_id" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"><option value="">Root Menu</option>@foreach($parents as $p)<option value="{{ $p->id }}">{{ $p->menu_name }}</option>@endforeach</select></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Modul</label><select name="module_id" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"><option value="">Tanpa Modul</option>@foreach($modules as $m)<option value="{{ $m->id }}">{{ $m->description }}</option>@endforeach</select></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Urutan</label><input name="order_no" type="number" value="0" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"></div>
                                <label class="md:col-span-2 flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-[#0f172a]"><input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300">Aktif</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0 backdrop-blur">
                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 sm:gap-3">
                            <button type="button" @click="openCreate = false" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-gray-200 px-5 sm:px-6 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-300 focus:ring-4 focus:ring-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-all">Batal</button>
                            <button class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-blue-600 px-5 sm:px-8 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 hover:shadow-lg focus:ring-4 focus:ring-blue-500/30 transition-all"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Detail/Edit/Hapus --}}
        <div x-show="openDetail"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/40"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>
            <div @click.away="openDetail = false"
                class="relative w-full max-w-4xl transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all flex flex-col max-h-[90dvh] sm:max-h-[95vh] overflow-hidden">
                <div class="shrink-0 flex items-start justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
                    <div class="pr-4">
                        <h3 class="text-base sm:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2 sm:gap-3">
                            <span class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-xl bg-blue-50 border border-blue-100 dark:bg-blue-900/30 dark:border-blue-800/50 shrink-0">
                                <i :class="selected.menu_icon || 'fa-solid fa-circle'" class="text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                            </span>
                            <span class="leading-tight" x-text="selected.menu_name"></span>
                        </h3>
                        <p class="text-xs text-gray-500 mt-1 ml-11 sm:ml-12">Detail, edit, hapus menu.</p>
                    </div>
                </div>
                <form method="POST" :action="updateUrl" class="flex-1 flex flex-col overflow-hidden relative">
                    @csrf @method('PUT')
                    <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 custom-scrollbar bg-slate-50 dark:bg-[#0f172a]">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm dark:border-gray-700/50 dark:bg-[#1e293b] relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pl-2">
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nama Menu</label><input name="menu_name" x-model="selected.menu_name" required class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Route Name</label><input name="menu_link" x-model="selected.menu_link" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Icon</label><input name="menu_icon" x-model="selected.menu_icon" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Parent</label><select name="parent_id" x-model="selected.parent_id" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"><option value="">Root Menu</option>@foreach($parents as $p)<option value="{{ $p->id }}">{{ $p->menu_name }}</option>@endforeach</select></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Modul</label><select name="module_id" x-model="selected.module_id" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"><option value="">Tanpa Modul</option>@foreach($modules as $m)<option value="{{ $m->id }}">{{ $m->description }}</option>@endforeach</select></div>
                                <div><label class="block text-[10px] sm:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Urutan</label><input name="order_no" type="number" x-model="selected.order_no" class="w-full text-xs sm:text-sm rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 sm:px-4 sm:py-3 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white dark:focus:ring-blue-900/50 transition-all shadow-sm"></div>
                                <label class="md:col-span-2 flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-[#0f172a]"><input type="checkbox" name="is_active" value="1" x-model="selected.is_active" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300">Aktif</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0 backdrop-blur">
                        <div class="flex flex-col-reverse sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4">
                            <button type="button" @click="$refs.deleteForm.submit()" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-red-600 text-white px-5 sm:px-6 py-2.5 text-sm font-bold shadow-md shadow-red-600/20 hover:bg-red-700 hover:shadow-lg focus:ring-4 focus:ring-red-500/30 transition-all"><i class="fas fa-trash-alt"></i> Hapus</button>
                            <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-2.5 sm:gap-3 sm:justify-end">
                                <button type="button" @click="openDetail = false" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-gray-200 px-5 sm:px-6 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-300 focus:ring-4 focus:ring-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-all">Batal</button>
                                <button class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-blue-600 px-5 sm:px-8 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 hover:shadow-lg focus:ring-4 focus:ring-blue-500/30 transition-all"><i class="fas fa-edit"></i> Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </form>
                <form x-ref="deleteForm" method="POST" :action="deleteUrl" onsubmit="return confirm('Hapus menu ini?')" class="hidden">@csrf @method('DELETE')</form>
            </div>
        </div>    <script>
        function menuManagementApp() {
            return {
                openCreate: false,
                openDetail: false,
                isLoading: false,
                filters: {search: '', status: '', parent: '', module_id: ''},
                menusList: @json($menus->items()),
                moduleMap: @json($moduleMap),
                pagination: @json($menus->toArray()),
                selected: {},
                updateUrl: '',
                deleteUrl: '',
                init() {},
                async fetchMenus(page = 1) {
                    this.isLoading = true;
                    const url = new URL(`{{ route('menu-management.index') }}`);
                    url.searchParams.append('page', page);
                    Object.entries(this.filters).forEach(([key, value]) => { if (value) url.searchParams.append(key, value); });
                    try {
                        const res = await fetch(url, {headers: {'Accept': 'application/json'}});
                        const result = await res.json();
                        this.menusList = result.data || [];
                        this.pagination = result;
                    } catch (e) { console.error(e); }
                    finally { this.isLoading = false; }
                },
                changePage(page) { this.fetchMenus(page); window.scrollTo({top: 150, behavior: 'smooth'}); },
                resetFilters() { this.filters = {search: '', status: '', parent: '', module_id: ''}; this.fetchMenus(1); },
                openDetailModal(menu) {
                    this.selected = {...menu, is_active: !!menu.is_active, parent_id: menu.parent_id ? String(menu.parent_id) : '', module_id: menu.module_id ? String(menu.module_id) : ''};
                    this.updateUrl = `{{ url('menu-management') }}/${menu.id}`;
                    this.deleteUrl = `{{ url('menu-management') }}/${menu.id}`;
                    this.openDetail = true;
                }
            }
        }
    </script>
@endsection


