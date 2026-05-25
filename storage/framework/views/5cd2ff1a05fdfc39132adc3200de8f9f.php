<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-4 font-semibold w-1/3">Konfigurasi</th>
                    <th class="px-6 py-4 font-semibold text-center">Provider & Model</th>
                    <th class="px-6 py-4 font-semibold text-center">Usage</th>
                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                <?php if($row->is_default): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-star"></i> Default
                                    </span>
                                <?php endif; ?>
                                <?php echo e($row->name); ?>

                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                API Key: <?php echo e($row->masked_api_key ?: '—'); ?>

                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-gray-700 dark:text-gray-300">
                                <?php echo e($providers[$row->provider]['label'] ?? $row->provider); ?>

                            </span>
                            <div class="text-xs text-gray-500 mt-0.5">
                                <i class="fa-solid fa-microchip"></i> <?php echo e($row->model); ?>

                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php if($row->daily_limit > 0): ?>
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <?php echo e($row->daily_used); ?>/<?php echo e($row->daily_limit); ?>

                                </div>
                                <div class="text-xs text-gray-500">requests/hari</div>
                            <?php else: ?>
                                <span class="text-xs text-gray-500">Unlimited</span>
                            <?php endif; ?>
                            <?php if($row->total_cost > 0): ?>
                                <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                    $<?php echo e(number_format($row->total_cost, 2)); ?>

                                </div>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php $statusClass = $row->is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-700 border-gray-200'; ?>
                            <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border <?php echo e($statusClass); ?>">
                                <?php echo e($row->is_active ? 'AKTIF' : 'NONAKTIF'); ?>

                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click='openDetailModal(<?php echo json_encode($row, 15, 512) ?>)'
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                    <i class="fa-solid fa-eye text-blue-500"></i> Detail
                                </button>
                                <form action="<?php echo e(route('settings.ai.test', $row->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-green-50 px-3 py-1.5 text-xs font-bold text-green-700 border border-green-200 hover:bg-green-100 transition shadow-sm dark:bg-green-900/20 dark:text-green-400 dark:border-green-800">
                                        <i class="fa-solid fa-plug"></i> Test
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-folder-open text-3xl mb-3 opacity-50"></i><br>
                            Belum ada konfigurasi AI.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\laragon\www\sainteku\resources\views/settings/ai/partials/_table.blade.php ENDPATH**/ ?>