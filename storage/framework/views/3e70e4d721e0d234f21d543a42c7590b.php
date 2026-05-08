<?php $__env->startSection('content'); ?>
    <div class="mx-auto">
        <div class="space-y-6">

            
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Daftar Role
                    </h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Master Data /</li>
                            <li class="text-blue-600 dark:text-blue-400">Role</li>
                        </ol>
                    </nav>
                </div>

                
                <div x-data="{ openCreate: false }">
                    <button @click="$dispatch('open-create-modal')"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fas fa-plus"></i>
                        Tambah Role
                    </button>

                    <?php echo $__env->make('masterdata::roles.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            
            <?php if(session('success')): ?>
                <div
                    class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                    <div
                        class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-green-800 dark:text-green-400">Sukses!</h5>
                        <p class="text-sm text-green-700 dark:text-green-500"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div
                    class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                    <div
                        class="mr-3 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <h5 class="text-sm font-semibold text-red-800 dark:text-red-400">Peringatan!</h5>
                        <p class="text-sm text-red-700 dark:text-red-500"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="rounded-lg">
                <form method="GET" class="flex flex-wrap items-center justify-between gap-3">

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        
                        <div class="relative w-full sm:max-w-xs">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 dark:text-gray-500">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                placeholder="Cari nama role..."
                                class="w-full rounded-md border border-gray-300 bg-gray-50 py-1.5 pl-9 pr-3 text-sm text-gray-900 focus:border-teal-500 focus:ring-teal-500 outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 transition">
                        </div>
                        
                        <select name="per_page" onchange="this.form.submit()"
                            class="rounded-md border border-gray-300 bg-gray-50 py-1.5 pl-3 pr-8 text-sm text-gray-900 focus:border-teal-500 focus:ring-teal-500 outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white transition cursor-pointer shadow-sm">
                            <option value="10" <?php echo e(request('per_page') == 10 ? 'selected' : ''); ?>>10 Baris</option>
                            <option value="25" <?php echo e(request('per_page') == 25 ? 'selected' : ''); ?>>25 Baris</option>
                            <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>50 Baris</option>
                            <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100 Baris</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition shadow-sm">
                            <i class="fa-solid fa-filter text-xs"></i> Filter
                        </button>

                        
                        <a href="<?php echo e(route('masterdata.roles.index')); ?>"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-md bg-teal-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-teal-700 focus:ring-4 focus:ring-teal-300 dark:focus:ring-teal-800 transition shadow-sm">
                            <i class="fa-solid fa-rotate text-xs"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50/50 text-left text-sm dark:bg-gray-700/30">
                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">ID</th>
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Nama Role
                        </th>
                        <th class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Deskripsi
                        </th>
                        <th
                            class="px-4 py-4 font-semibold text-gray-900 dark:text-white uppercase tracking-wider text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="bg-transparent">
                            <td class="px-4 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                                #<?php echo e($loop->iteration); ?>

                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-bold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    <?php echo e(strtoupper($role->role_name)); ?>

                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <?php echo e($role->description ?? 'Tidak ada deskripsi'); ?>

                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <?php
                                        // Siapkan array izin khusus untuk role ini agar bisa dibaca oleh Alpine.js
                                        // Formatnya: "modul_id-permission_id" (Contoh: "1-2" berarti Modul 1 punya Izin 2)
                                        $perms = isset($rolePermissions[$role->id])
                                            ? $rolePermissions[$role->id]
                                            : collect();
                                        $assignedPerms = $perms
                                            ->map(function ($p) {
                                                return $p->modul_id . '-' . $p->permission_id;
                                            })
                                            ->values()
                                            ->toJson();
                                    ?>

                                    
                                    <button type="button"
                                        @click="$dispatch('open-perm-modal', { 
                                            url: '<?php echo e(route('masterdata.roles.permissions.update', $role->id ?? 0)); ?>',
                                            name: '<?php echo e($role->role_name); ?>',
                                            assigned: <?php echo e($assignedPerms); ?> 
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-purple-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-900"
                                        title="Atur Hak Akses">
                                        <i class="fa-solid fa-shield-halved"></i> Akses
                                    </button>

                                    
                                    <button type="button"
                                        @click="$dispatch('open-edit-modal', { 
                                            url: '<?php echo e(route('masterdata.roles.update', $role->id)); ?>',
                                            code: '<?php echo e($role->role_code); ?>',
                                            name: '<?php echo e($role->role_name); ?>',
                                            active: <?php echo e($role->is_active == '1' ? 'true' : 'false'); ?>

                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                                        title="Edit Role">
                                        <i class="fa-solid fa-pencil"></i> Ubah
                                    </button>

                                    <button type="button"
                                        @click="$dispatch('open-delete-modal', { 
                                            url: '<?php echo e(route('masterdata.roles.destroy', $role->id)); ?>',
                                            name: '<?php echo e($role->role_name); ?>'
                                        })"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                                        title="Hapus Role">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-shield-alt text-3xl mb-2 opacity-20"></i>
                                <p>Belum ada data role yang dikonfigurasi.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        
        <?php if(isset($roles) && $roles->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($roles->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php echo $__env->make('masterdata::roles.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('masterdata::roles.modal-permissions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('masterdata::roles.modal-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('masterdata::roles.delete-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/roles/index.blade.php ENDPATH**/ ?>