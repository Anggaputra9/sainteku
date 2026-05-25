<?php $__env->startSection('content'); ?>
    <div class="space-y-6" x-data="{
                alert: { type: '', message: '' },
                flash(type, message) {
                    this.alert = { type, message };
                    setTimeout(() => { this.alert.message = ''; }, 4000);
                }
            }" 
            x-init="
                <?php if(session('success')): ?> flash('success', '<?php echo e(session('success')); ?>'); <?php endif; ?>
                <?php if(session('error')): ?> flash('error', '<?php echo e(session('error')); ?>'); <?php endif; ?>
            " 
            x-cloak>

            
            <div class="flex flex-col gap-4 pb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
                <div>
                    <h2 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                        <i class="fa-solid fa-users text-indigo-500"></i> Manajemen Pengguna
                    </h2>
                    <nav>
                        <ol class="flex items-center gap-2 mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            <li>Master Data /</li>
                            <li class="text-indigo-600 dark:text-indigo-400">Users</li>
                        </ol>
                    </nav>
                </div>
                <button type="button" @click="window.dispatchEvent(new CustomEvent('open-create-modal', { bubbles: true }))"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700">
                    <i class="fa-solid fa-plus"></i> Tambah User
                </button>
            </div>

            
            <template x-if="alert.message">
                <div class="flex items-center gap-3 p-4 border-l-4 rounded-r-lg shadow-sm"
                     :class="alert.type === 'error' ? 'border-red-500 bg-red-50 text-red-700' : 'border-green-500 bg-green-50 text-green-700'">
                    <i class="fa-solid" :class="alert.type === 'error' ? 'fa-circle-xmark' : 'fa-check-circle'"></i>
                    <span class="text-sm font-bold" x-text="alert.message"></span>
                </div>
            </template>

            
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form method="GET" action="<?php echo e(route('masterdata.admin.users.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 xl:grid-cols-8 gap-3 items-end">
                    <div class="md:col-span-2 xl:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Pencarian</label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama / email..."
                            class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Status</label>
                        <select name="status" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua</option>
                            <option value="1" <?php echo e(request('status') === '1' ? 'selected' : ''); ?>>Aktif</option>
                            <option value="0" <?php echo e(request('status') === '0' ? 'selected' : ''); ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Role</label>
                        <select name="role_id" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua</option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>" <?php echo e((string)request('role_id') === (string)$role->id ? 'selected' : ''); ?>><?php echo e($role->role_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Unit</label>
                        <select name="unit_id" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <option value="">Semua</option>
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($unit->id); ?>" <?php echo e(request('unit_id') === $unit->id ? 'selected' : ''); ?>><?php echo e($unit->unit_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Per Page</label>
                        <select name="per_page" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:bg-[#0f172a] dark:border-gray-600 dark:text-white">
                            <?php $__currentLoopData = [10,25,50,75,100,150,200,250,300,350,400,450,500]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($size); ?>" <?php echo e((int)$perPage === $size ? 'selected' : ''); ?>><?php echo e($size); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="md:col-span-2 xl:col-span-2 flex gap-2">
                        <button type="submit" class="flex-1 inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">Filter</button>
                        <a href="<?php echo e(route('masterdata.admin.users.index')); ?>" class="flex-1 inline-flex justify-center rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Reset</a>
                    </div>
                </form>
            </div>

            
            <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4 font-semibold">ID</th>
                                <th class="px-6 py-4 font-semibold">Info User</th>
                                <th class="px-6 py-4 font-semibold">Role</th>
                                <th class="px-6 py-4 font-semibold">Unit Utama</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-900 dark:text-white"><?php echo e($user->id); ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 font-bold text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900/40 dark:text-blue-400">
                                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white"><?php echo e($user->name); ?></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($user->email); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            <?php $__empty_2 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
                                                    <?php echo e($role->role_name); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <span class="text-xs italic text-gray-400">No Role</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 w-[20%] max-w-[150px]">
                                        <?php if($user->unit_id): ?>
                                            <span title="<?php echo e($user->unitUtama->unit_name ?? 'Unit tidak ditemukan'); ?> (ID: <?php echo e($user->unit_id); ?>)"
                                                class="inline-block max-w-full truncate rounded-md bg-indigo-50 px-2.5 py-1 align-middle text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-700/10 transition hover:bg-indigo-100 cursor-help dark:bg-indigo-900/30 dark:text-indigo-400">
                                                <?php echo e($user->unitUtama->unit_name ?? 'Unit tidak ditemukan'); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs font-medium italic text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if($user->is_active): ?>
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
                                                <span class="w-1.5 h-1.5 bg-green-600 rounded-full dark:bg-green-400"></span> Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full dark:bg-red-400"></span> Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <?php
                                            $tingkatUtama = 'kampus';
                                            if ($user->unitUtama) {
                                                if ($user->unitUtama->unit_type_id == 2)
                                                    $tingkatUtama = 'fakultas';
                                                elseif ($user->unitUtama->unit_type_id == 3)
                                                    $tingkatUtama = 'prodi';
                                            }
                                            $editPayload = [
                                                'url' => route('masterdata.admin.users.update', $user->id),
                                                'userData' => [
                                                    'name' => $user->name,
                                                    'email' => $user->email,
                                                    'identity' => $user->identity_id,
                                                    'type' => $user->user_type,
                                                    'unit' => $user->unit_id,
                                                    'tingkatUtama' => $tingkatUtama,
                                                    'active' => $user->is_active == '1',
                                                    'roles' => collect($user->roles)->pluck('id')->toArray(),
                                                    'unitTambahan' => collect($user->unitTambahan)->pluck('id')->toArray()
                                                ],
                                                'deleteUrl' => route('masterdata.admin.users.destroy', $user->id),
                                                'userName' => $user->name
                                            ];
                                        ?>
                                        <div class="flex gap-2 justify-center">
                                            <button @click="$dispatch('open-detail-modal', <?php echo e(\Illuminate\Support\Js::from($editPayload)); ?>)"
                                                class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 border border-blue-200 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800 dark:hover:bg-blue-800/50 transition shadow-sm">
                                                <i class="fa-solid fa-eye"></i> Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <i class="mb-3 text-3xl opacity-50 fa-solid fa-users"></i><br>
                                        Belum ada user.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if($users->hasPages()): ?>
                <div class="px-2"><?php echo e($users->links()); ?></div>
            <?php endif; ?>

            
            <?php echo $__env->make('masterdata::admin.modal-create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <?php echo $__env->make('masterdata::admin.modal-detail', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('masterdata::admin.delete-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sainteku\Modules/MasterData\resources/views/admin/users.blade.php ENDPATH**/ ?>