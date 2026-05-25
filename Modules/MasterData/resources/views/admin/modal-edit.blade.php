@php
    $kampus = $units->first(fn($u) => $u->id === $u->unit_parent);
    $kampusId = $kampus->id ?? 'U001';
    $kampusName = $kampus->unit_name ?? 'UIN Prof. K.H. Saifuddin Zuhri';

    $fakultasList = $units->filter(fn($u) => $u->unit_parent === $kampusId && $u->id !== $kampusId)->values();
    $listFakultasArr = $fakultasList->map(fn($f) => ['id' => $f->id, 'name' => $f->unit_name])->toArray();

    $fakultasIds = $fakultasList->pluck('id')->toArray();
    $prodiList = $units->filter(fn($u) => in_array($u->unit_parent, $fakultasIds))->values();
    $listProdiArr = $prodiList->map(fn($p) => ['id' => $p->id, 'name' => $p->unit_name, 'parent' => $p->unit_parent])->toArray();
@endphp

<div x-data="openEditModal()" @open-edit-modal.window="handleOpenEdit($event)" x-show="openEdit"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openEdit = false"
        class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-200 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        {{-- MODAL HEADER --}}
        <div
            class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit User</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Memperbarui data pengguna: <span
                        class="font-semibold text-indigo-600 dark:text-indigo-400" x-text="userData.name"></span></p>
            </div>
            <button type="button" @click="openEdit = false"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        {{-- FORM WRAPPER (Flex-1 & Overflow Hidden) --}}
        <form :action="url" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')

            <input type="hidden" name="unit_id" :value="unitUtamaId">

            {{-- SCROLLABLE BODY --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">

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
                            <input type="text" name="name" required x-model="userData.name"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label
                                class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Email
                                <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required x-model="userData.email"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">NIM
                                / NIP / NIK</label>
                            <input type="text" name="identity_id" x-model="userData.identity"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tipe
                                Pengguna <span class="text-red-500">*</span></label>
                            <select name="user_type" required x-model="userData.type"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Tipe --</option>
                                @foreach ($userTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section: Keamanan --}}
                <div
                    class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-lock text-indigo-500"></i> Keamanan (Opsional)
                        </h4>
                    </div>
                    <div class="p-5">
                        <p
                            class="text-xs text-amber-600 dark:text-amber-400 mb-4 flex items-center gap-1.5 bg-amber-50 p-2 rounded-lg border border-amber-200 dark:bg-amber-900/30 dark:border-amber-800">
                            <i class="fa-solid fa-circle-info"></i> Kosongkan jika tidak ingin mengubah password
                        </p>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label
                                    class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Password
                                    Baru</label>
                                <input type="password" name="password"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                    placeholder="••••••••">
                            </div>
                            <div>
                                <label
                                    class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Konfirmasi
                                    Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                    placeholder="••••••••">
                            </div>
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
                                    <option value="">-- Tampilkan Semua Fakultas --</option>
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
                <div x-show="tingkatUtama !== 'kampus' && tingkatUtama !== '' && unitUtamaId !== ''" x-cloak
                    x-transition
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
                                                    x-model="unitTambahan"
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
                                                    x-model="unitTambahan"
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

                {{-- Section: Hak Akses / Role --}}
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
                                <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" x-model="userData.roles"
                                    class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                <span
                                    class="text-sm leading-snug text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition">{{ $role->role_name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

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
                            <input type="checkbox" name="is_active" value="1" class="peer sr-only"
                                x-model="userData.active">
                            <div
                                class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700">
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Akun Aktif</span>
                        </label>
                    </div>
                </div>

            </div>

            {{-- FIXED FOOTER --}}
            <div
                class="shrink-0 flex items-center justify-end gap-3 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <button type="button" @click="openEdit = false"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border dark:border-gray-600 transition">
                    <i class="fa-solid fa-times"></i> Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 shadow-md transition">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Global variable to store modal component reference
    let editModalComponent = null;

    function openEditModal() {
        return {
            openEdit: false,
            url: '',
            userData: { name: '', email: '', identity: '', type: '', active: true, roles: [] },
            kampusId: '{{ $kampusId }}',
            kampusName: '{{ $kampusName }}',
            listFakultas: @json($listFakultasArr),
            listProdi: @json($listProdiArr),
            tingkatUtama: '',
            filterFakultasUtama: '',
            unitUtamaId: '',
            tingkatTambahan: '',
            filterFakultasTambahan: '',
            unitTambahan: [],
            isInitializing: false,

            init() {
                editModalComponent = this;

                // Listen for open-edit-modal event
                window.addEventListener('open-edit-modal', handleOpenEdit);

                this.$watch('tingkatUtama', value => {
                    if (this.isInitializing) return;
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
                    this.unitTambahan = [];
                });
            },

            destroy() {
                window.removeEventListener('open-edit-modal', handleOpenEdit);
            }
        }
    }

    function handleOpenEdit(event) {
        if (editModalComponent) {
            editModalComponent.isInitializing = true;
            editModalComponent.openEdit = true;
            editModalComponent.url = event.detail.url;
            editModalComponent.userData = event.detail.userData;

            let incUnitId = event.detail.userData.unit;
            editModalComponent.unitUtamaId = incUnitId;
            editModalComponent.unitTambahan = event.detail.userData.unitTambahan || [];

            if (incUnitId === editModalComponent.kampusId) {
                editModalComponent.tingkatUtama = 'kampus';
            } else if (editModalComponent.listFakultas.some(f => f.id === incUnitId)) {
                editModalComponent.tingkatUtama = 'fakultas';
            } else {
                let prodi = editModalComponent.listProdi.find(p => p.id === incUnitId);
                if (prodi) {
                    editModalComponent.tingkatUtama = 'prodi';
                    editModalComponent.filterFakultasUtama = prodi.parent;
                } else {
                    editModalComponent.tingkatUtama = '';
                }
            }

            editModalComponent.tingkatTambahan = '';
            editModalComponent.filterFakultasTambahan = '';

            setTimeout(() => { editModalComponent.isInitializing = false; }, 100);
        }
    }
</script>