<?php $__env->startSection('content'); ?>


    <div class="mb-10 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="flex flex-col gap-1 px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Utama</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan jumlah pengajuan berdasarkan modul dan periode akademik. Data ditampilkan sesuai role Anda.</p>
        </div>

        <div class="grid grid-cols-1 gap-10 p-10 md:grid-cols-2 xl:grid-cols-4">
            <?php if($showMonev): ?>
                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-800 dark:bg-blue-900/20">
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-300">Tashih Pending</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900 dark:text-white"><?php echo e(number_format($examSubmitted)); ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Menunggu persetujuan</p>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-800 dark:bg-emerald-900/20">
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-600 dark:text-emerald-300">Tashih Disetujui</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900 dark:text-white"><?php echo e(number_format($examApproved)); ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Sudah mendapat persetujuan</p>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-800 dark:bg-amber-900/20">
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-300">Tashih Direvisi</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900 dark:text-white"><?php echo e(number_format($examRevised)); ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Dikembalikan untuk revisi</p>
                </div>
            <?php endif; ?>

            <?php if($showInfrastructure): ?>
                <div class="rounded-2xl border border-violet-200 bg-violet-50 p-5 dark:border-violet-800 dark:bg-violet-900/20">
                    <p class="text-xs font-semibold uppercase tracking-widest text-violet-600 dark:text-violet-300">Peminjaman Pending</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900 dark:text-white"><?php echo e(number_format($infrastructurePending)); ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Belum selesai</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800/40">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-600 dark:text-slate-300">Peminjaman Selesai</p>
                    <p class="mt-3 text-4xl font-bold text-gray-900 dark:text-white"><?php echo e(number_format($infrastructureCompleted)); ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Sudah dikembalikan</p>
                </div>
            <?php endif; ?>

            <?php if(!$showMonev && !$showInfrastructure): ?>
                <div class="col-span-1 md:col-span-2 xl:col-span-4 rounded-2xl border border-gray-200 bg-gray-50 p-6 text-center dark:border-gray-700 dark:bg-gray-900">
                    <p class="font-semibold text-gray-900 dark:text-white">Tidak ada data dashboard yang dapat ditampilkan.</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Role Anda belum memberikan akses ke modul yang tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($showMonev): ?>

    
    <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Distribusi Status Tashih</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Proporsi pengajuan berdasarkan status saat ini.</p>
            </div>
            <div class="p-8">
                <div class="flex flex-wrap gap-4 mb-6">
                    <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-block w-3 h-3 rounded-sm" style="background:#3b82f6"></span>
                        Pending (<?php echo e(number_format($examSubmitted)); ?>)
                    </span>
                    <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-block w-3 h-3 rounded-sm" style="background:#10b981"></span>
                        Disetujui (<?php echo e(number_format($examApproved)); ?>)
                    </span>
                    <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-block w-3 h-3 rounded-sm" style="background:#f59e0b"></span>
                        Direvisi (<?php echo e(number_format($examRevised)); ?>)
                    </span>
                </div>
                <div class="relative" style="height:240px;">
                    <canvas id="donutStatusChart" role="img" aria-label="Donut chart distribusi status tashih">
                        Pending: <?php echo e($examSubmitted); ?>, Disetujui: <?php echo e($examApproved); ?>, Direvisi: <?php echo e($examRevised); ?>.
                    </canvas>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Histogram per Periode Akademik</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jumlah pengajuan tashih tiap periode, dipecah per status.</p>
            </div>
            <div class="p-8">
                <div class="flex flex-wrap gap-4 mb-6">
                    <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-block w-3 h-3 rounded-sm" style="background:#3b82f6"></span> Pending
                    </span>
                    <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-block w-3 h-3 rounded-sm" style="background:#10b981"></span> Disetujui
                    </span>
                    <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-block w-3 h-3 rounded-sm" style="background:#f59e0b"></span> Direvisi
                    </span>
                </div>
                <div class="relative" style="height:240px;">
                    <canvas id="barPeriodChart" role="img" aria-label="Stacked bar chart pengajuan per periode akademik">
                        Histogram pengajuan per periode.
                    </canvas>
                </div>
            </div>
        </div>
    </div>

    
    <div class="my-10 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Tren Pengajuan per Bulan</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pergerakan jumlah pengajuan tashih dalam 6 bulan terakhir.</p>
        </div>
        <div class="p-10">
            <div class="flex flex-wrap gap-4 mb-8">
                <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span class="inline-block w-3 h-3 rounded-sm" style="background:#3b82f6"></span> Pending
                </span>
                <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span class="inline-block w-3 h-3 rounded-sm" style="background:#10b981"></span> Disetujui
                </span>
                <span class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span class="inline-block w-3 h-3 rounded-sm" style="background:#f59e0b"></span> Direvisi
                </span>
            </div>
            <div class="relative" style="height:220px;">
                <canvas id="lineTrendChart" role="img" aria-label="Line chart tren pengajuan tashih 6 bulan terakhir">
                    Tren pengajuan bulanan.
                </canvas>
            </div>
        </div>
    </div>

    
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Ringkasan Pengajuan per Periode Akademik</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Detail jumlah pengajuan setiap periode akademik sesuai role Anda.</p>
        </div>
        <div class="p-10 overflow-x-auto">
            <table class="min-w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3">Periode Akademik</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right">Pending</th>
                        <th class="px-4 py-3 text-right">Disetujui</th>
                        <th class="px-4 py-3 text-right">Direvisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $examByPeriod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white"><?php echo e($period->name); ?> &mdash; <?php echo e($period->semester); ?></td>
                            <td class="px-4 py-4 text-right font-semibold"><?php echo e(number_format($period->total_count)); ?></td>
                            <td class="px-4 py-4 text-right text-blue-600 dark:text-blue-400"><?php echo e(number_format($period->submitted_count)); ?></td>
                            <td class="px-4 py-4 text-right text-emerald-600 dark:text-emerald-400"><?php echo e(number_format($period->approved_count)); ?></td>
                            <td class="px-4 py-4 text-right text-amber-600 dark:text-amber-400"><?php echo e(number_format($period->revised_count)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data periode yang tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<?php if($showMonev): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var d         = window._dashboardData || {};
    var isDark    = document.documentElement.classList.contains('dark')
                 || window.matchMedia('(prefers-color-scheme: dark)').matches;
    var gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.07)';
    var textColor = isDark ? '#9ca3af' : '#6b7280';
    var borderBg  = isDark ? '#1f2937' : '#ffffff';

    // 1. Donut
    var donutCtx = document.getElementById('donutStatusChart');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Disetujui', 'Direvisi'],
                datasets: [{
                    data: [d.examSubmitted, d.examApproved, d.examRevised],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
                    borderColor: borderBg,
                    borderWidth: 3,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return '  ' + ctx.label + ': ' + ctx.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Stacked Bar
    var barCtx = document.getElementById('barPeriodChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: d.chartLabels,
                datasets: [
                    { label: 'Pending',   data: d.chartPending,  backgroundColor: '#3b82f6', borderRadius: 3, borderSkipped: false },
                    { label: 'Disetujui', data: d.chartApproved, backgroundColor: '#10b981', borderRadius: 3, borderSkipped: false },
                    { label: 'Direvisi',  data: d.chartRevised,  backgroundColor: '#f59e0b', borderRadius: 3, borderSkipped: false }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        ticks: { color: textColor, font: { size: 11 }, autoSkip: false, maxRotation: 30 },
                        grid: { color: gridColor }
                    },
                    y: {
                        stacked: true,
                        ticks: { color: textColor, font: { size: 11 } },
                        grid: { color: gridColor },
                        beginAtZero: true
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    // 3. Line Chart
    var lineCtx = document.getElementById('lineTrendChart');
    if (lineCtx) {
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: d.trendLabels,
                datasets: [
                    {
                        label: 'Pending',
                        data: d.trendPending,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.10)',
                        fill: true, tension: 0.38,
                        pointRadius: 4, pointBackgroundColor: '#3b82f6',
                        borderDash: []
                    },
                    {
                        label: 'Disetujui',
                        data: d.trendApproved,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.07)',
                        fill: true, tension: 0.38,
                        pointRadius: 4, pointBackgroundColor: '#10b981',
                        borderDash: [6, 4]
                    },
                    {
                        label: 'Direvisi',
                        data: d.trendRevised,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245,158,11,0.07)',
                        fill: true, tension: 0.38,
                        pointRadius: 4, pointBackgroundColor: '#f59e0b',
                        borderDash: [2, 3]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: textColor, font: { size: 11 } }, grid: { color: gridColor } },
                    y: { ticks: { color: textColor, font: { size: 11 } }, grid: { color: gridColor }, beginAtZero: true }
                },
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\resources\views/pages/dashboard.blade.php ENDPATH**/ ?>