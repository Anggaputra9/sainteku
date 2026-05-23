<div x-data="openDetailModal()"
    @open-detail-modal.window="handleOpenDetail($event)"
    x-show="openDetail"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;">

        <div @click.away="openDetail = false"
            class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all overflow-hidden">

            <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-gray-50 px-6 py-4 dark:bg-gray-800/40 dark:border-gray-700">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-user text-indigo-500"></i> Detail User
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Informasi pengguna: <span class="font-semibold text-indigo-600 dark:text-indigo-400" x-text="userData.name"></span></p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="toggleEditMode()"
                        class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-bold transition"
                        :class="editMode ? 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200' : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                        <i class="fa-solid" :class="editMode ? 'fa-eye' : 'fa-pen'"></i>
                        <span x-text="editMode ? 'Mode Lihat' : 'Mode Edit'"></span>
                    </button>
                    <button @click="openDetail = false"
                        class="inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <form :action="url" method="POST" class="flex flex-col min-h-full">
                @csrf
                @method('PUT')

                <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">

                    <div x-show="!editMode" class="space-y-4">
                        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-[#1e293b] overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-user text-indigo-500"></i> Informasi Dasar
                                </h4>
                            </div>
                            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-[11px] font-bold text-gray-500 uppercase mb-1">Nama Lengkap</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="userData.name"></div>
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-gray-500 uppercase mb-1">Email</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="userData.email"></div>
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-gray-500 uppercase mb-1">NIM / NIP / NIK</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="userData.identity || '—'"></div>
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-gray-500 uppercase mb-1">Tipe Pengguna</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        @foreach ($userTypes as $type)
                                            <span x-show="userData.type === '{{ $type->id }}'">{{ $type->description }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-gray-500 uppercase mb-1">Status Akun</div>
                                    <span x-show="userData.active" class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-600 dark:bg-green-400"></span>
                                        Aktif
                                    </span>
                                    <span x-show="!userData.active" class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-600 dark:bg-red-400"></span>
                                        Nonaktif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-[#1e293b] overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-building text-indigo-500"></i> Unit Kerja
                                </h4>
                            </div>
                            <div class="p-4 space-y-3">
                                <div>
                                    <div class="text-[11px] font-bold text-gray-500 uppercase mb-1">Tingkat Unit</div>
                                    <span x-show="userData.tingkatUtama === 'kampus'" class="inline-flex rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400">Kampus</span>
                                    <span x-show="userData.tingkatUtama === 'fakultas'" class="inline-flex rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400">Fakultas</span>
                                    <span x-show="userData.tingkatUtama === 'prodi'" class="inline-flex rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400">Program Studi</span>
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-gray-500 uppercase mb-1">Unit Utama</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        @foreach ($units as $unit)
                                            <span x-show="userData.unit === '{{ $unit->id }}'">{{ $unit->unit_name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div x-show="userData.tingkatUtama !== 'kampus' && userData.unitTambahan && userData.unitTambahan.length > 0">
                                    <div class="text-[11px] font-bold text-gray-500 uppercase mb-2">Unit Tambahan</div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($units as $unit)
                                            <span x-show="userData.unitTambahan && userData.unitTambahan.includes('{{ $unit->id }}')"
                                                class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700">
                                                {{ $unit->unit_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-[#1e293b] overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 dark:bg-gray-800/40 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fa-solid fa-shield-halved text-indigo-500"></i> Hak Akses / Role
                                </h4>
                            </div>
                            <div class="p-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($roles as $role)
                                        <span x-show="userData.roles && userData.roles.includes({{ $role->id }})"
                                            class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
                                            {{ $role->role_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="editMode" class="space-y-4">
                        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-[#1e293b]">
                            <h4 class="mb-4 flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-200">
                                <i class="fa-solid fa-user text-indigo-500"></i> Informasi Dasar
                            </h4>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-[11px] font-bold text-gray-500 uppercase">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" required x-model="userData.name"
                                        :readonly="!editMode"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 dark:bg-[#1e293b] dark:text-white dark:border-gray-600"
                                        :class="{'bg-gray-50 dark:bg-gray-900': !editMode}"
                                        placeholder="Masukkan nama lengkap">
                                </div>

                                <div>
                                    <label class="mb-2 block text-[11px] font-bold text-gray-500 uppercase">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" required x-model="userData.email"
                                        :readonly="!editMode"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 dark:bg-[#1e293b] dark:text-white dark:border-gray-600"
                                        :class="{'bg-gray-50 dark:bg-gray-900': !editMode}"
                                        placeholder="user@example.com">
                                </div>

                                <div>
                                    <label class="mb-2 block text-[11px] font-bold text-gray-500 uppercase">NIM / NIP / NIK</label>
                                    <input type="text" name="identity_id" x-model="userData.identity"
                                        :readonly="!editMode"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 dark:bg-[#1e293b] dark:text-white dark:border-gray-600"
                                        :class="{'bg-gray-50 dark:bg-gray-900': !editMode}"
                                        placeholder="123456789">
                                </div>

                                <div>
                                    <label class="mb-2 block text-[11px] font-bold text-gray-500 uppercase">Tipe Pengguna <span class="text-red-500">*</span></label>
                                    <select name="user_type" required x-model="userData.type"
                                        :disabled="!editMode"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 dark:bg-[#1e293b] dark:text-white dark:border-gray-600"
                                        :class="{'bg-gray-50 dark:bg-gray-900': !editMode}">
                                        <option value="">-- Pilih Tipe --</option>
                                        @foreach ($userTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-[#1e293b]">
                            <h4 class="mb-4 flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-200">
                                <i class="fa-solid fa-building text-indigo-500"></i> Unit Kerja
                            </h4>
                            <div class="space-y-6">
                                <div>
                                    <label class="mb-3 block text-[11px] font-bold text-gray-500 uppercase">Unit Utama <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-1 gap-4 rounded-xl bg-white p-4 ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-600 sm:grid-cols-3">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="tingkatUtama" value="kampus" required
                                                x-model="userData.tingkatUtama"
                                                :disabled="!editMode"
                                                class="h-5 w-5 border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition">Kampus</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="tingkatUtama" value="fakultas" required
                                                x-model="userData.tingkatUtama"
                                                :disabled="!editMode"
                                                class="h-5 w-5 border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition">Fakultas</span>
                                        </label>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="tingkatUtama" value="prodi" required
                                                x-model="userData.tingkatUtama"
                                                :disabled="!editMode"
                                                class="h-5 w-5 border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition">Program Studi</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-2 block text-[11px] font-bold text-gray-500 uppercase">Pilih Unit <span class="text-red-500">*</span></label>
                                    <select name="unit_id" required x-model="userData.unit"
                                        :disabled="!editMode"
                                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 dark:bg-[#1e293b] dark:text-white dark:border-gray-600"
                                        :class="{'bg-gray-50 dark:bg-gray-900': !editMode}">
                                        <option value="">-- Pilih Unit --</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="userData.tingkatUtama !== 'kampus'">
                                    <label class="mb-3 block text-[11px] font-bold text-gray-500 uppercase">Unit Tambahan (Opsional)</label>
                                    <div class="grid grid-cols-1 gap-3 rounded-xl bg-white p-4 ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-600 sm:grid-cols-2">
                                        @foreach ($units as $unit)
                                            <label class="flex items-start gap-3 cursor-pointer group">
                                                <input type="checkbox" name="unit_tambahan[]" value="{{ $unit->id }}"
                                                    x-bind:checked="userData.unitTambahan && userData.unitTambahan.includes('{{ $unit->id }}')"
                                                    :disabled="!editMode"
                                                    class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                                <span class="text-sm leading-snug text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition">
                                                    {{ $unit->unit_name }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-[#1e293b]">
                            <h4 class="mb-4 flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-200">
                                <i class="fa-solid fa-shield-halved text-indigo-500"></i> Hak Akses / Role <span class="text-red-500">*</span>
                            </h4>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                                @foreach ($roles as $role)
                                    <label class="flex items-start gap-3 cursor-pointer group">
                                        <input type="checkbox" name="role_ids[]" value="{{ $role->id }}"
                                            x-bind:checked="userData.roles && userData.roles.includes({{ $role->id }})"
                                            :disabled="!editMode"
                                            class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                        <span class="text-sm leading-snug text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition">
                                            {{ $role->role_name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-[#1e293b]">
                            <h4 class="mb-4 flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-200">
                                <i class="fa-solid fa-toggle-on text-indigo-500"></i> Status Akun
                            </h4>
                            <label class="relative inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" name="is_active" value="1" class="peer sr-only" x-bind:checked="userData.active" :disabled="!editMode">
                                <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700"></div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Aktifkan Akun</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 flex justify-between border-t border-gray-200 bg-gray-50 px-6 py-4 dark:bg-gray-800/40 dark:border-gray-700">
                    <button type="button" @click="confirmDelete()"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700 transition shadow-sm">
                        <i class="fa-solid fa-trash"></i> Hapus User
                    </button>
                    <div class="flex gap-2">
                        <button type="button" @click="openDetail = false"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-times"></i> Tutup
                        </button>
                        <button type="submit" x-show="editMode" x-transition
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2 text-sm font-bold text-white hover:bg-emerald-700 shadow-md transition">
                            <i class="fa-solid fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Global variable to store modal component reference
    let detailModalComponent = null;

    function openDetailModal() {
        return {
            openDetail: false,
            editMode: false,
            url: '',
            deleteUrl: '',
            userName: '',
            userData: {
                name: '',
                email: '',
                identity: '',
                type: '',
                unit: '',
                tingkatUtama: 'kampus',
                active: true,
                roles: [],
                unitTambahan: []
            },

            init() {
                detailModalComponent = this;

                // Listen for open-detail-modal event
                window.addEventListener('open-detail-modal', handleOpenDetail);
            },

            destroy() {
                window.removeEventListener('open-detail-modal', handleOpenDetail);
            },

            toggleEditMode() {
                this.editMode = !this.editMode;
            },

            confirmDelete() {
                // Lempar data ke delete-modal lalu tutup modal detail
                window.dispatchEvent(new CustomEvent('open-delete-modal', {
                    bubbles: true,
                    detail: {
                        url: this.deleteUrl,
                        name: this.userName
                    }
                }));
                this.openDetail = false;
            }
        }
    }

    function handleOpenDetail(event) {
        if (detailModalComponent) {
            detailModalComponent.openDetail = true;
            detailModalComponent.editMode = false;
            detailModalComponent.url = event.detail.url;
            detailModalComponent.deleteUrl = event.detail.deleteUrl;
            detailModalComponent.userName = event.detail.userName;
            detailModalComponent.userData = event.detail.userData;
        }
    }

</script>
