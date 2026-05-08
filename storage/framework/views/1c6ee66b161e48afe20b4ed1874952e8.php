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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formCreateUser', () => ({
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
                this.$watch('tingkatUtama', value => {
                    this.filterFakultasUtama = '';
                    this.filterFakultasTambahan = '';

                    if (value === 'kampus') {
                        this.unitUtamaId = this.kampusId;
                        this.tingkatTambahan = '';
                    } else if (value === 'prodi') {
                        this.unitUtamaId = '';
                        this.tingkatTambahan = 'prodi'; // Kunci opsi tambahan cuma di prodi
                    } else {
                        this.unitUtamaId = '';
                        this.tingkatTambahan = '';
                    }

                    // Bersihin semua centangan tambahan kalau pindah-pindah unit utama
                    document.querySelectorAll('input[name="unit_tambahan[]"]').forEach(cb => cb.checked = false);
                });
            }
        }))
    })
</script>

<div x-show="openCreate"
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

    <div @click.away="openCreate = false"
        class="relative w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah User Baru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan pengguna baru ke sistem <span
                        class="font-semibold text-blue-600 dark:text-blue-400">Sainteku</span></p>
            </div>
            <button @click="openCreate = false"
                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-yellow-600 transition focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>

        <form action="<?php echo e(route('masterdata.admin.users.store')); ?>" method="POST" class="space-y-6"
            x-data="formCreateUser">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="unit_id" :value="unitUtamaId">

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="Masukkan nama lengkap" value="<?php echo e(old('name')); ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="user@example.com" value="<?php echo e(old('email')); ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">NIM / NIP /
                        NIK</label>
                    <input type="text" name="identity_id"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                        placeholder="123456789" value="<?php echo e(old('identity_id')); ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tipe Pengguna <span
                            class="text-red-500">*</span></label>
                    <select name="user_type" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Tipe --</option>
                        <?php $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>" <?php echo e(old('user_type') == $type->id ? 'selected' : ''); ?>>
                                <?php echo e($type->description); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 md:col-span-2">
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-gray-400 italic">Kata Sandi Akun
                    </h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                                placeholder="••••••••">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Konfirmasi
                                Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div
                    class="md:col-span-2 rounded-xl bg-blue-50/50 p-5 ring-1 ring-blue-100 dark:bg-blue-900/10 dark:ring-blue-900/30">
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-blue-500 italic">Penempatan Unit
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
                    class="md:col-span-2 rounded-xl bg-gray-50/50 p-5 ring-1 ring-gray-200 dark:bg-gray-900/30 dark:ring-gray-700">
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

                <div class="md:col-span-2">
                    <label class="mb-3 block text-sm font-semibold text-gray-900 dark:text-white">Hak Akses / Role <span
                            class="text-red-500">*</span></label>

                    <div
                        class="grid grid-cols-1 gap-4 rounded-xl bg-gray-50/50 p-4 ring-1 ring-gray-200 dark:bg-gray-900/30 dark:ring-gray-700 sm:grid-cols-2 md:grid-cols-3">

                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-start gap-3 cursor-pointer group">

                                <input type="checkbox" name="role_ids[]" value="<?php echo e($role->id); ?>"
                                    class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">

                                <span
                                    class="text-sm leading-snug text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition">
                                    <?php echo e($role->role_name); ?>

                                </span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div>

                <div class="flex items-end pb-2 md:col-span-2">
                    <label class="relative inline-flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" class="peer sr-only" checked>
                        <div
                            class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700">
                        </div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Aktifkan Akun Langsung</span>
                    </label>
                </div>
            </div>

            <div
                class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                <button type="submit"
                    class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save"></i> Simpan User
                </button>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/admin/modal-create.blade.php ENDPATH**/ ?>