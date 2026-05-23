<?php
    // 1. Data Hierarki (Udah Bener Sesuai Database Lu)
    $kampus = $units->first(fn($u) => $u->id === $u->unit_parent);
    $kampusId = $kampus->id ?? 'U001';
    $kampusName = $kampus->unit_name ?? 'UIN Prof. K.H. Saifuddin Zuhri';

    $fakultasList = $units->filter(fn($u) => $u->unit_parent === $kampusId && $u->id !== $kampusId)->values();
    $listFakultasArr = $fakultasList->map(fn($f) => ['id' => $f->id, 'name' => $f->unit_name])->toArray();

    $fakultasIds = $fakultasList->pluck('id')->toArray();
    $prodiList = $units->filter(fn($u) => in_array($u->unit_parent, $fakultasIds))->values();
    $listProdiArr = $prodiList->map(fn($p) => ['id' => $p->id, 'name' => $p->unit_name, 'parent' => $p->unit_parent])->toArray();
?>

<div x-data="openCreateModal()" @open-create-modal.window="openCreate = true" x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openCreate = false"
        class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-200 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        
        <div
            class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tambah User Baru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan pengguna baru ke sistem <span
                        class="font-semibold text-indigo-600 dark:text-indigo-400">Sainteku</span></p>
            </div>
            <button type="button" @click="openCreate = false"
                class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all dark:hover:bg-red-900/30 dark:hover:text-red-400">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        
        <form action="<?php echo e(route('masterdata.admin.users.store')); ?>" method="POST"
            class="flex flex-col flex-1 overflow-hidden" @submit="submitForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="unit_id" :value="unitUtamaId">

            
            <div class="flex-1 overflow-y-auto p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">

                
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
                                placeholder="Masukkan nama lengkap" value="<?php echo e(old('name')); ?>">
                        </div>
                        <div>
                            <label
                                class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Email
                                <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                placeholder="user@example.com" value="<?php echo e(old('email')); ?>">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">NIM
                                / NIP / NIK</label>
                            <input type="text" name="identity_id"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                placeholder="123456789" value="<?php echo e(old('identity_id')); ?>">
                        </div>
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tipe
                                Pengguna <span class="text-red-500">*</span></label>
                            <select name="user_type" required
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                <option value="">-- Pilih Tipe --</option>
                                <?php $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e(old('user_type') == $type->id ? 'selected' : ''); ?>>
                                        <?php echo e($type->description); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                
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

                
                <div x-show="tingkatUtama !== '' && unitUtamaId !== ''" x-cloak x-transition
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

                
                <div
                    class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-indigo-500"></i> Hak Akses / Role <span
                                class="text-red-500">*</span>
                        </h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" name="role_ids[]" value="<?php echo e($role->id); ?>"
                                    class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                <span
                                    class="text-sm leading-snug text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition">
                                    <?php echo e($role->role_name); ?>

                                </span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
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

            
            <div
                class="shrink-0 flex items-center justify-end gap-3 border-t border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                <button type="button" @click="openCreate = false"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border dark:border-gray-600 transition">
                    <i class="fa-solid fa-times"></i> Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 shadow-md transition">
                    <i class="fa-solid fa-save"></i> Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        return {
            openCreate: false,
            tingkatUtama: '',
            filterFakultasUtama: '',
            unitUtamaId: '',
            kampusId: '<?php echo e($kampusId); ?>',
            kampusName: '<?php echo e($kampusName); ?>',
            listFakultas: <?php echo json_encode($listFakultasArr, 15, 512) ?>,
            listProdi: <?php echo json_encode($listProdiArr, 15, 512) ?>,
            tingkatTambahan: '',
            filterFakultasTambahan: '',

            init() {
                // Alpine native listener via HTML (@open-create-modal.window) covers the opening.
                // We just watch state changes here.
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
                    document.querySelectorAll('input[name="unit_tambahan[]"]').forEach(cb => cb.checked = false);
                });
            },

            submitForm(e) {
                this.openCreate = false;
                setTimeout(() => e.target.submit(), 100);
            }
        }
    }
</script><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/admin/modal-create.blade.php ENDPATH**/ ?>