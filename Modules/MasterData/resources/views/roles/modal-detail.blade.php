<template x-teleport="#modal-root">
<div x-data="openRoleDetailModal()" @open-detail-modal.window="handleOpenDetail($event)" x-show="openDetail"
    class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto backdrop-blur-sm bg-gray-900/40 p-3 sm:p-6"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

    <div @click.away="!permMode && (openDetail = false)"
        class="relative w-full flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden"
        :class="permMode ? 'max-w-5xl' : 'max-w-2xl'">

        {{-- HEADER --}}
        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-8 py-3 sm:py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700 shadow-sm sticky top-0">
            <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3 pr-4">
                <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-800/50 dark:bg-blue-900/30">
                    <i class="fa-solid text-sm text-blue-600 dark:text-blue-400 sm:text-base"
                        :class="permMode ? 'fa-shield-halved' : 'fa-user-shield'"></i>
                </div>
                <div class="min-w-0 leading-tight">
                    <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100 sm:text-xl"
                        x-text="permMode ? 'Matriks Hak Akses' : (editMode ? 'Edit Role' : 'Detail Role')"></h3>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400 sm:text-sm" x-text="roleData.name"></p>
                </div>
            </div>
            <div class="shrink-0 w-8 sm:w-9"></div>
        </div>

        {{-- MODE LIHAT --}}
        <div x-show="!editMode && !permMode" class="flex flex-col flex-1 overflow-hidden">
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-indigo-500"></i> Informasi Role
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Kode Role</div>
                            <div class="text-sm font-mono font-semibold text-indigo-700 dark:text-indigo-400" x-text="roleData.code"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nama Role</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="roleData.name"></div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Status</div>
                            <span x-show="roleData.active"
                                class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Aktif
                            </span>
                            <span x-show="!roleData.active"
                                class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Nonaktif
                            </span>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Pengguna Terdaftar</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                <span x-text="roleData.user_count"></span> user
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-key text-indigo-500"></i> Ringkasan Hak Akses
                        </h4>
                    </div>
                    <div class="p-5">
                        <span class="inline-flex rounded-full bg-purple-50 px-3 py-1 text-sm font-bold text-purple-700 border border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800">
                            <span x-text="roleData.permission_count"></span> izin aktif
                        </span>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                            Klik <strong>Hak Akses</strong> untuk mengatur matriks perizinan modul.
                        </p>
                    </div>
                </div>
            </div>

            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2 sm:gap-4">
                    <button type="button" @click="openDetail = false"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <button type="button" x-show="canDelete" @click="confirmDelete()"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-red-700 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                        <button type="button" @click="enterPermMode()"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-purple-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-purple-700 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-shield-halved"></i> Hak Akses
                        </button>
                        <button type="button" @click="enterEditMode()"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-blue-700 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODE EDIT --}}
        <form x-show="editMode && !permMode" :action="url" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            @method('PUT')
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-indigo-500"></i> Informasi Role
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Kode Role <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="role_code" required maxlength="5" x-model="roleData.code"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm uppercase outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                Nama Role <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="role_name" required maxlength="30" x-model="roleData.name"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div class="md:col-span-2">
                            <label class="relative inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" name="is_active" value="1" class="peer sr-only" x-model="roleData.active">
                                <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white dark:bg-gray-700"></div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Role Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2">
                    <button type="button" @click="cancelEdit()"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-blue-700 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>

        {{-- MODE HAK AKSES --}}
        <form x-show="permMode" :action="permissionsUrl" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            @csrf
            <div class="flex-1 overflow-y-auto p-3 sm:p-6 custom-scrollbar">
                <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-[#1e293b]">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-4 font-semibold border-b dark:border-gray-700">Modul / Fitur</th>
                                @foreach ($permissions as $perm)
                                    <th class="px-2 py-4 text-center font-semibold border-b dark:border-gray-700" title="{{ $perm->permission_name }}">
                                        {{ $perm->permission_code }}
                                        <div class="text-[10px] text-gray-400 font-normal mt-1">{{ $perm->permission_name }}</div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($modules as $modul)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white border-r dark:border-gray-700">
                                        {{ $modul->description }}
                                        <div class="text-xs text-gray-500 font-normal">{{ $modul->module_code }}</div>
                                    </td>
                                    @foreach ($permissions as $perm)
                                        <td class="px-2 py-3 text-center border-r border-gray-100 dark:border-gray-700/50">
                                            <label class="flex justify-center cursor-pointer">
                                                <input type="checkbox" name="permissions[{{ $modul->id }}][]"
                                                    value="{{ $perm->id }}"
                                                    :checked="isChecked('{{ $modul->id }}', '{{ $perm->id }}')"
                                                    class="h-5 w-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700">
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="shrink-0 border-t border-gray-200 bg-white/95 px-4 sm:px-6 py-4 z-20 dark:bg-[#1e293b]/95 dark:border-gray-700 sticky bottom-0 backdrop-blur">
                <div class="flex flex-row flex-nowrap items-center justify-between gap-2">
                    <button type="button" @click="cancelPermMode()"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-gray-200 px-3 py-2 text-xs font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 sm:px-6 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-purple-600 px-3 py-2 text-xs font-bold text-white shadow-md hover:bg-purple-700 sm:px-8 sm:py-2.5 sm:text-sm transition-all">
                        <i class="fas fa-save"></i> Simpan Hak Akses
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</template>

<script>
    function openRoleDetailModal() {
        return {
            openDetail: false,
            editMode: false,
            permMode: false,
            canDelete: true,
            url: '',
            deleteUrl: '',
            permissionsUrl: '',
            roleName: '',
            assigned: [],
            editSnapshot: null,
            roleData: {
                id: '',
                code: '',
                name: '',
                active: true,
                permission_count: 0,
                user_count: 0,
            },

            handleOpenDetail(event) {
                this.openDetail = true;
                this.editMode = false;
                this.permMode = false;
                this.url = event.detail.url;
                this.deleteUrl = event.detail.deleteUrl;
                this.permissionsUrl = event.detail.permissionsUrl;
                this.roleName = event.detail.roleName;
                this.canDelete = event.detail.canDelete ?? true;
                this.assigned = event.detail.assigned || [];
                this.editSnapshot = null;
                this.roleData = { ...event.detail.roleData };
            },

            enterEditMode() {
                this.editSnapshot = JSON.parse(JSON.stringify(this.roleData));
                this.editMode = true;
            },

            cancelEdit() {
                if (this.editSnapshot) {
                    this.roleData = JSON.parse(JSON.stringify(this.editSnapshot));
                }
                this.editMode = false;
                this.editSnapshot = null;
            },

            enterPermMode() {
                this.permMode = true;
            },

            cancelPermMode() {
                this.permMode = false;
            },

            isChecked(modulId, permId) {
                return this.assigned.includes(String(modulId) + '-' + String(permId));
            },

            confirmDelete() {
                window.dispatchEvent(new CustomEvent('open-delete-modal', {
                    bubbles: true,
                    detail: {
                        url: this.deleteUrl,
                        name: this.roleName,
                    },
                }));
                this.openDetail = false;
            },
        };
    }
</script>