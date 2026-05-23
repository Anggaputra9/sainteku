<?php
    $kampus = $units->first(fn($u) => $u->id === $u->unit_parent);
    $kampusId = $kampus->id ?? 'U001';
    $kampusName = $kampus->unit_name ?? 'UIN Prof. K.H. Saifuddin Zuhri';

    $fakultasList = $units->filter(fn($u) => $u->unit_parent === $kampusId && $u->id !== $kampusId)->values();
    $listFakultasArr = $fakultasList->map(fn($f) => ['id' => $f->id, 'name' => $f->unit_name])->toArray();

    $fakultasIds = $fakultasList->pluck('id')->toArray();
    $prodiList = $units->filter(fn($u) => in_array($u->unit_parent, $fakultasIds))->values();
    $listProdiArr = $prodiList->map(fn($p) => ['id' => $p->id, 'name' => $p->unit_name, 'parent' => $p->unit_parent])->toArray();
?>

<div x-data="openEditModal()"
    @open-edit-modal.window="handleOpenEdit($event)"
    x-show="openEdit"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openEdit = false"
        class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-200 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        <div class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit User</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Memperbarui data pengguna: <span class="font-semibold text-blue-600 dark:text-blue-400" x-text="userData.name"></span></p>
            </div>
            <button @click="openEdit = false"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form :action="url" method="POST" class="flex flex-col min-h-full">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <input type="hidden" name="unit_id" :value="unitUtamaId">

            <div class="flex-1 overflow-y-auto p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-user text-indigo-500"></i> Informasi Dasar
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="userData.name" required
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                </div>

                <div>
                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" x-model="userData.email" required
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                </div>

                <div>
                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">NIM / NIP /
                        NIK</label>
                    <input type="text" name="identity_id" x-model="userData.identity"
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                </div>

                <div>
                    <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tipe Pengguna <span
                            class="text-red-500">*</span></label>
                    <select name="user_type" x-model="userData.type" required
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                        <option value="">-- Pilih Tipe --</option>
                        <?php $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->description); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-lock text-indigo-500"></i> Keamanan
                        </h4>
                    </div>
                    <div class="p-5 md:col-span-2 bg-slate-50 dark:bg-[#0f172a]">
                    <h4 class="mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 italic">Isi jika
                        ingin diubah)</h4>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <input type="password" name="password" placeholder="Password Baru"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 md:col-span-2">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-building text-indigo-500"></i> Penempatan Unit Utama
                        </h4>
                    </div>
                    <div class="p-5 space-y-5 bg-slate-50 dark:bg-[#0f172a]">
                    <h4 class="mb-4 text-[10px] font-bold uppercase tracking-widest text-blue-500 italic">Penempatan Unit
                        Utama</h4>

                    <div class="space-y-5">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tingkat Unit
                                <span class="text-red-500">*</span></label>
                            <select name="tingkatUtama" x-model="tingkatUtama" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Pilih Tingkat / Strata --</option>
                                <option value="kampus">Universitas / Institut</option>
                                <option value="fakultas">Fakultas</option>
                                <option value="prodi">Program Studi</option>
                            </select>
                        </div>

                        <div x-show="tingkatUtama === 'kampus'" x-cloak x-transition>
                            <div
                                class="rounded-lg border border-blue-200 bg-blue-100 px-4 py-3 text-sm text-blue-800 dark:border-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                <span class="font-bold">Universitas Terpilih:</span> <span x-text="kampusName"></span>
                            </div>
                        </div>

                        <div x-show="tingkatUtama === 'fakultas'" x-cloak x-transition>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Pilih Fakultas
                                <span class="text-red-500">*</span></label>
                            <select x-model="unitUtamaId" x-bind:required="tingkatUtama === 'fakultas'"
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                <option value="">-- Pilih Fakultas --</option>
                                <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                    <option :value="fakultas.id" x-text="fakultas.name"></option>
                                </template>
                            </select>
                        </div>

                        <div x-show="tingkatUtama === 'prodi'" x-cloak x-transition
                            class="grid grid-cols-1 md:grid-cols-2 gap-4 rounded-lg bg-white p-4 ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-600">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Filter
                                    Fakultas</label>
                                <select x-model="filterFakultasUtama"
                                    class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
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
                                    class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
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

                <div x-show="tingkatUtama !== 'kampus' && tingkatUtama !== '' && unitUtamaId !== ''" x-cloak
                    x-transition
                    class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 md:col-span-2">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-building-user text-indigo-500"></i> Unit Tambahan / Rangkap
                        </h4>
                    </div>
                    <div class="p-5 bg-slate-50 dark:bg-[#0f172a]">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 italic">Unit Tambahan /
                            Rangkap <span class="font-normal lowercase">(Opsional)</span></h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div x-show="tingkatUtama === 'fakultas'">
                            <select x-model="tingkatTambahan"
                                class="w-full rounded-lg border-0 px-3 py-2 text-sm text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:ring-gray-600">
                                <option value="">-- Tampilkan Semua Tingkat --</option>
                                <option value="fakultas">Fakultas</option>
                                <option value="prodi">Program Studi</option>
                            </select>
                        </div>

                        <div x-show="tingkatTambahan === 'prodi' || tingkatTambahan === ''" x-cloak x-transition>
                            <select x-model="filterFakultasTambahan"
                                class="w-full rounded-lg border-0 px-3 py-2 text-sm text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:ring-gray-600">
                                <option value="">-- Tampilkan Semua Fakultas --</option>
                                <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                    <option x-show="tingkatUtama !== 'fakultas' || fakultas.id !== unitUtamaId"
                                        :value="fakultas.id" x-text="fakultas.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div
                        class="max-h-60 overflow-y-auto rounded-lg bg-white p-4 ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-600 space-y-6">

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
                                            class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:bg-gray-700">
                                        <span
                                            class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition"
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
                                    <label x-show="
                                            (filterFakultasTambahan === '' || prodi.parent === filterFakultasTambahan) &&
                                            (tingkatUtama !== 'fakultas' || prodi.parent !== unitUtamaId) &&
                                            prodi.id !== unitUtamaId"
                                        class="flex items-start gap-3 cursor-pointer group">

                                        <input type="checkbox" name="unit_tambahan[]" :value="prodi.id"
                                            x-model="unitTambahan"
                                            class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:bg-gray-700">
                                        <span
                                            class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition"
                                            x-text="prodi.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 md:col-span-2">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-200">Hak Akses / Role <span
                            class="text-red-500">*</span></label>
                    </div>
                    <div class="p-5 grid grid-cols-2 gap-3 sm:grid-cols-3 bg-slate-50 dark:bg-[#0f172a]">
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="role_ids[]" value="<?php echo e($role->id); ?>" x-model="userData.roles"
                                    class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition"><?php echo e($role->role_name); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="flex items-end pb-2 md:col-span-2">
                    <label class="relative inline-flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" class="peer sr-only" x-model="userData.active">
                        <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-emerald-500 peer-checked:after:translate-x-full dark:bg-gray-700"></div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Akun Aktif</span>
                    </label>
                </div>
            </div>
            </div>

            <div class="shrink-0 sticky bottom-0 z-30 flex flex-col sm:flex-row justify-end items-center border-t border-gray-200 bg-slate-50 px-6 py-4 dark:bg-[#0f172a] dark:border-gray-700 gap-3">
                <button type="button" @click="openEdit = false"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-lg bg-amber-500 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-amber-600 transition">
                    <i class="fas fa-save"></i> Simpan Perubahan
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
            kampusId: '<?php echo e($kampusId); ?>',
            kampusName: '<?php echo e($kampusName); ?>',
            listFakultas: <?php echo json_encode($listFakultasArr, 15, 512) ?>,
            listProdi: <?php echo json_encode($listProdiArr, 15, 512) ?>,
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
<?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/admin/modal-edit.blade.php ENDPATH**/ ?>