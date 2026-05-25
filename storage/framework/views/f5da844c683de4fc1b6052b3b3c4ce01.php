<?php
    // Data Hierarki Unit
    $kampus = $units->first(fn($u) => $u->id === $u->unit_parent);
    $kampusId = $kampus->id ?? 'U001';
    $kampusName = $kampus->unit_name ?? 'UIN Prof. K.H. Saifuddin Zuhri';

    $fakultasList = $units->filter(fn($u) => $u->unit_parent === $kampusId && $u->id !== $kampusId)->values();
    $listFakultasArr = $fakultasList->map(fn($f) => ['id' => (string) $f->id, 'name' => $f->unit_name])->toArray();

    $fakultasIds = $fakultasList->pluck('id')->toArray();
    $prodiList = $units->filter(fn($u) => in_array($u->unit_parent, $fakultasIds))->values();
    $listProdiArr = $prodiList->map(fn($p) => ['id' => (string) $p->id, 'name' => $p->unit_name, 'parent' => (string) $p->unit_parent])->toArray();
?>

<div x-data="openDetailModal()" @open-detail-modal.window="handleOpenDetail($event)" x-show="openDetail"
    class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/60 p-3 sm:p-4 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

    <div @click.away="openDetail = false"
        class="relative w-full max-w-4xl flex flex-col max-h-[90dvh] sm:max-h-[95vh] transform rounded-2xl bg-slate-50 shadow-2xl ring-1 ring-gray-200 dark:bg-[#0f172a] dark:ring-gray-700 transition-all overflow-hidden">

        
        <div
            class="shrink-0 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 z-20 dark:bg-[#1e293b] dark:border-gray-700">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-user text-indigo-500"></i> Detail User
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Informasi pengguna: <span
                        class="font-semibold text-indigo-600 dark:text-indigo-400" x-text="userData.name"></span></p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="toggleEditMode()"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs font-bold transition shadow-sm"
                    :class="editMode ? 'bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:hover:bg-amber-900/50 border border-amber-200 dark:border-amber-800' : 'bg-indigo-600 text-white hover:bg-indigo-700'">
                    <i class="fa-solid" :class="editMode ? 'fa-eye' : 'fa-pen'"></i>
                    <span x-text="editMode ? 'Mode Lihat' : 'Mode Edit'"></span>
                </button>
            </div>
        </div>

        
        <form :action="url" method="POST" class="flex flex-col flex-1 overflow-hidden m-0">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <input type="hidden" name="unit_id" :value="userData.unit">

            
            <div class="flex-1 overflow-y-auto p-6 space-y-5 bg-slate-50 dark:bg-[#0f172a] custom-scrollbar">

                
                <div x-show="!editMode" class="space-y-5" x-transition>
                    <div
                        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-user text-indigo-500"></i> Informasi Dasar
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <div class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">
                                    Nama Lengkap</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="userData.name">
                                </div>
                            </div>
                            <div>
                                <div class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">
                                    Email</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="userData.email">
                                </div>
                            </div>
                            <div>
                                <div class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">
                                    NIM / NIP / NIK</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white"
                                    x-text="userData.identity || '—'"></div>
                            </div>
                            <div>
                                <div class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">
                                    Tipe Pengguna</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    <?php $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span x-show="userData.type == '<?php echo e($type->id); ?>'"><?php echo e($type->description); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <div>
                                <div class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">
                                    Status Akun</div>
                                <span x-show="userData.active"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-600 dark:bg-green-400"></span> Aktif
                                </span>
                                <span x-show="!userData.active"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-600 dark:bg-red-400"></span> Nonaktif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-building text-indigo-500"></i> Unit Kerja
                            </h4>
                        </div>
                        <div class="p-5 space-y-5">
                            <div>
                                <div class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">
                                    Tingkat Unit Utama</div>
                                <span x-show="userData.tingkatUtama === 'kampus'"
                                    class="inline-flex rounded-md bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400">Kampus</span>
                                <span x-show="userData.tingkatUtama === 'fakultas'"
                                    class="inline-flex rounded-md bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400">Fakultas</span>
                                <span x-show="userData.tingkatUtama === 'prodi'"
                                    class="inline-flex rounded-md bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400">Program
                                    Studi</span>
                            </div>
                            <div>
                                <div class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">
                                    Unit Utama</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span x-show="userData.unit == '<?php echo e($unit->id); ?>'"><?php echo e($unit->unit_name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <div
                                x-show="userData.tingkatUtama !== 'kampus' && userData.unitTambahan && userData.unitTambahan.length > 0">
                                <div class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">
                                    Unit Tambahan / Rangkap</div>
                                <div class="flex flex-wrap gap-2">
                                    <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span
                                            x-show="userData.unitTambahan && userData.unitTambahan.includes('<?php echo e($unit->id); ?>')"
                                            class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 border border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700">
                                            <?php echo e($unit->unit_name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-indigo-500"></i> Hak Akses / Role
                            </h4>
                        </div>
                        <div class="p-5">
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span x-show="userData.roles && userData.roles.includes('<?php echo e($role->id); ?>')"
                                        class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
                                        <?php echo e($role->role_name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div x-show="editMode" class="space-y-5" x-cloak x-transition>
                    <div
                        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-user text-indigo-500"></i> Informasi Dasar
                            </h4>
                        </div>
                        <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label
                                    class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Nama
                                    Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required x-model="userData.name"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                    placeholder="Masukkan nama lengkap">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Email
                                    <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required x-model="userData.email"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                    placeholder="user@example.com">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">NIM
                                    / NIP / NIK</label>
                                <input type="text" name="identity_id" x-model="userData.identity"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600"
                                    placeholder="123456789">
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tipe
                                    Pengguna <span class="text-red-500">*</span></label>
                                <select name="user_type" required x-model="userData.type"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Pilih Tipe --</option>
                                    <?php $__currentLoopData = $userTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->id); ?>"><?php echo e($type->description); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
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
                                <label
                                    class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Tingkat
                                    Unit <span class="text-red-500">*</span></label>
                                <select name="tingkatUtama" x-model="userData.tingkatUtama" required
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Pilih Tingkat / Strata --</option>
                                    <option value="kampus">Universitas / Institut</option>
                                    <option value="fakultas">Fakultas</option>
                                    <option value="prodi">Program Studi</option>
                                </select>
                            </div>

                            <div x-show="userData.tingkatUtama === 'kampus'" x-cloak x-transition>
                                <div
                                    class="rounded-lg border border-indigo-200 bg-indigo-100 px-4 py-3 text-sm text-indigo-800 dark:border-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                    <span class="font-bold">Universitas Terpilih:</span> <span
                                        x-text="kampusName"></span>
                                </div>
                            </div>

                            <div x-show="userData.tingkatUtama === 'fakultas'" x-cloak x-transition>
                                <label
                                    class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Pilih
                                    Fakultas <span class="text-red-500">*</span></label>
                                <select x-model="userData.unit" x-bind:required="userData.tingkatUtama === 'fakultas'"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                    <option value="">-- Pilih Fakultas --</option>
                                    <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                        <option :value="fakultas.id" x-text="fakultas.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div x-show="userData.tingkatUtama === 'prodi'" x-cloak x-transition
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 rounded-lg bg-gray-50/50 p-4 ring-1 ring-gray-200 dark:bg-[#0f172a]/50 dark:ring-gray-700">
                                <div>
                                    <label
                                        class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Filter
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
                                    <label
                                        class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-gray-400">Pilih
                                        Prodi <span class="text-red-500">*</span></label>
                                    <select x-model="userData.unit" x-bind:required="userData.tingkatUtama === 'prodi'"
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

                    
                    <div x-show="userData.tingkatUtama !== '' && userData.unit !== '' && userData.tingkatUtama !== 'kampus'"
                        x-cloak x-transition
                        class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-building-user text-indigo-500"></i> Unit Tambahan / Rangkap <span
                                    class="font-normal lowercase text-xs text-gray-500">(Opsional)</span>
                            </h4>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div x-show="userData.tingkatUtama === 'fakultas'">
                                    <select x-model="tingkatTambahan"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                        <option value="">-- Tampilkan Semua Tingkat --</option>
                                        <option value="fakultas">Fakultas</option>
                                        <option value="prodi">Program Studi</option>
                                    </select>
                                </div>

                                <div x-show="tingkatTambahan === 'prodi' || tingkatTambahan === ''" x-cloak
                                    x-transition>
                                    <select x-model="filterFakultasTambahan"
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:text-white dark:border-gray-600">
                                        <option value="">-- Tampilkan Semua Fakultas --</option>
                                        <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                            <option
                                                x-show="userData.tingkatUtama !== 'fakultas' || fakultas.id !== userData.unit"
                                                :value="fakultas.id" x-text="fakultas.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div
                                class="rounded-lg border border-gray-200 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-[#0f172a]/50">
                                <h5 class="font-bold text-sm text-gray-800 dark:text-gray-200 mb-3">Pilih Unit Tambahan
                                </h5>
                                <div
                                    class="max-h-60 overflow-y-auto rounded-lg bg-white p-4 ring-1 ring-gray-200 dark:bg-[#1e293b] dark:ring-gray-600 space-y-6 custom-scrollbar">

                                    <div
                                        x-show="userData.tingkatUtama !== 'prodi' && (tingkatTambahan === '' || tingkatTambahan === 'fakultas')">
                                        <h5
                                            class="font-bold text-sm text-gray-800 dark:text-gray-200 mb-3 border-b border-gray-100 pb-1 dark:border-gray-700">
                                            Tingkat Fakultas</h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pl-2">
                                            <template x-for="fakultas in listFakultas" :key="fakultas.id">
                                                <label x-show="fakultas.id !== userData.unit"
                                                    class="flex items-start gap-3 cursor-pointer group">
                                                    <input type="checkbox" name="unit_tambahan[]" :value="fakultas.id"
                                                        x-model="userData.unitTambahan"
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
                                                    x-show="(filterFakultasTambahan === '' || prodi.parent === filterFakultasTambahan) && (userData.tingkatUtama !== 'fakultas' || prodi.parent !== userData.unit) && prodi.id !== userData.unit"
                                                    class="flex items-start gap-3 cursor-pointer group">
                                                    <input type="checkbox" name="unit_tambahan[]" :value="prodi.id"
                                                        x-model="userData.unitTambahan"
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

                    <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-[#1e293b]">
                        <h4 class="mb-4 flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-200">
                            <i class="fa-solid fa-shield-halved text-indigo-500"></i> Hak Akses / Role <span
                                class="text-red-500">*</span>
                        </h4>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox" name="role_ids[]" value="<?php echo e($role->id); ?>"
                                        x-model="userData.roles"
                                        class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                    <span
                                        class="text-sm leading-snug text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition">
                                        <?php echo e($role->role_name); ?>

                                    </span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-[#1e293b]">
                        <h4 class="mb-4 flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-200">
                            <i class="fa-solid fa-toggle-on text-indigo-500"></i> Status Akun
                        </h4>
                        <label class="relative inline-flex cursor-pointer items-center gap-3">
                            <input type="checkbox" name="is_active" value="1" class="peer sr-only"
                                x-model="userData.active">
                            <div
                                class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700">
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Aktifkan Akun</span>
                        </label>
                    </div>
                </div>
            </div>

            <div
                class="shrink-0 flex justify-between border-t border-gray-200 bg-gray-50 px-6 py-4 dark:bg-gray-800/40 dark:border-gray-700 z-20">
                <button type="button" @click="confirmDelete()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm font-bold text-red-700 border border-red-200 hover:bg-red-100">
                    <i class="fa-solid fa-trash"></i> Hapus
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

<script>
    function openDetailModal() {
        return {
            openDetail: false,
            editMode: false,
            url: '',
            deleteUrl: '',
            userName: '',

            // Variabel untuk Filter Unit Dinamis
            kampusId: '<?php echo e($kampusId); ?>',
            kampusName: '<?php echo e($kampusName); ?>',
            listFakultas: <?php echo json_encode($listFakultasArr, 15, 512) ?>,
            listProdi: <?php echo json_encode($listProdiArr, 15, 512) ?>,
            filterFakultasUtama: '',
            tingkatTambahan: '',
            filterFakultasTambahan: '',
            isInitializing: false,

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
                // Pantau perubahan unit utama untuk mereset unit tambahan secara otomatis
                this.$watch('userData.tingkatUtama', value => {
                    if (this.isInitializing) return;
                    this.filterFakultasUtama = '';
                    this.filterFakultasTambahan = '';

                    if (value === 'kampus') {
                        this.userData.unit = this.kampusId;
                        this.tingkatTambahan = '';
                    } else if (value === 'prodi') {
                        this.userData.unit = '';
                        this.tingkatTambahan = 'prodi';
                    } else {
                        this.userData.unit = '';
                        this.tingkatTambahan = '';
                    }
                    this.userData.unitTambahan = []; // Reset pilihan unit tambahan
                });
            },

            handleOpenDetail(event) {
                this.isInitializing = true;
                this.openDetail = true;
                this.editMode = false;
                this.url = event.detail.url;
                this.deleteUrl = event.detail.deleteUrl;
                this.userName = event.detail.userName;

                // Clone objek agar tidak merusak data aslinya dan paksa array menjadi string 
                // agar x-model checkboxes bekerja semestinya dengan perbandingan Strict (===)
                let data = JSON.parse(JSON.stringify(event.detail.userData));
                data.roles = (data.roles || []).map(String);
                data.unitTambahan = (data.unitTambahan || []).map(String);
                data.unit = String(data.unit);

                this.userData = data;

                // Setup state awal filter saat modal dibuka
                let incUnitId = this.userData.unit;
                if (incUnitId === this.kampusId) {
                    this.userData.tingkatUtama = 'kampus';
                } else if (this.listFakultas.some(f => f.id === incUnitId)) {
                    this.userData.tingkatUtama = 'fakultas';
                } else {
                    let prodi = this.listProdi.find(p => p.id === incUnitId);
                    if (prodi) {
                        this.userData.tingkatUtama = 'prodi';
                        this.filterFakultasUtama = prodi.parent;
                    }
                }

                this.tingkatTambahan = '';
                this.filterFakultasTambahan = '';

                setTimeout(() => { this.isInitializing = false; }, 100);
            },

            toggleEditMode() {
                this.editMode = !this.editMode;
            },

            confirmDelete() {
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
</script><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/admin/modal-detail.blade.php ENDPATH**/ ?>