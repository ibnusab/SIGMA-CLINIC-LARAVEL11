<?php $__env->startSection('title', 'Dashboard Ringkasan Executive'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Low Stock Alert Banner if any -->
    <?php if(count($lowStockMedicines) > 0): ?>
    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg font-bold">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-900">Peringatan Stok Obat Menipis!</h4>
                <p class="text-xs text-amber-800">Terdapat <strong><?php echo e(count($lowStockMedicines)); ?> jenis obat</strong> dengan sisa stok mendekati atau di bawah batas minimum.</p>
            </div>
        </div>
        <a href="<?php echo e(route('medicines.index')); ?>?filter=low_stock" class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition-all shadow">
            Cek Stok Farmasi
        </a>
    </div>
    <?php endif; ?>

    <!-- 5 Top Key Performance Indicator Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Pasien -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-sky-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Pasien</span>
                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900 tracking-tight"><?php echo e(number_format($totalPatients)); ?></div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">Terdaftar di Rekam Medis</div>
            </div>
        </div>

        <!-- Dokter Aktif -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-sky-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Dokter Aktif</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900 tracking-tight"><?php echo e(number_format($totalDoctors)); ?></div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">Spesialis Siap Layani</div>
            </div>
        </div>

        <!-- Poli Spesialis -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-sky-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Poli Klinik</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-clinic-medical"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900 tracking-tight"><?php echo e(number_format($totalClinics)); ?></div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">Layanan Spesialisasi</div>
            </div>
        </div>

        <!-- Kunjungan Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-sky-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Antrian Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900 tracking-tight"><?php echo e(number_format($todayVisits)); ?></div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">Pendaftaran Kunjungan</div>
            </div>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-sky-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Pendapatan Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <div>
                <div class="text-xl font-black text-slate-900 tracking-tight">Rp <?php echo e(number_format($todayRevenue, 0, ',', '.')); ?></div>
                <div class="text-[11px] text-slate-500 font-medium mt-1">Pemasukan Lunas</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Visits Chart -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Grafik Trend Kunjungan Pasien</h3>
                    <p class="text-xs text-slate-500">Jumlah pendaftaran 6 bulan terakhir</p>
                </div>
                <span class="text-xs font-bold text-sky-600 bg-sky-50 px-2.5 py-1 rounded-lg">Bulanan</span>
            </div>
            <div class="h-64">
                <canvas id="visitsChart"></canvas>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Grafik Trend Pendapatan Klinik</h3>
                    <p class="text-xs text-slate-500">Total pembayaran lunas 6 bulan terakhir</p>
                </div>
                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-lg">Rupiah (IDR)</span>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Active Queue & Doctor Schedules Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Live Queue List Today (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-list-ol"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Daftar Antrian Kunjungan Hari Ini</h3>
                        <p class="text-xs text-slate-500">Status antrian aktif pasien di klinik</p>
                    </div>
                </div>
                <a href="<?php echo e(route('registrations.index')); ?>" class="text-xs font-bold text-sky-600 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <?php if(count($todayQueue) > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <th class="pb-3 px-2">No. Antrian</th>
                            <th class="pb-3 px-2">Pasien</th>
                            <th class="pb-3 px-2">Poli & Dokter</th>
                            <th class="pb-3 px-2">Status</th>
                            <th class="pb-3 px-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <?php $__currentLoopData = $todayQueue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-2 font-black text-sky-600 text-sm">
                                #<?php echo e(str_pad($q->queue_number, 2, '0', STR_PAD_LEFT)); ?>

                            </td>
                            <td class="py-3 px-2 font-bold text-slate-800">
                                <?php echo e($q->patient->name ?? '-'); ?>

                                <div class="text-[10px] text-slate-400 font-normal"><?php echo e($q->patient->mr_number ?? ''); ?></div>
                            </td>
                            <td class="py-3 px-2 font-semibold text-slate-600">
                                <?php echo e($q->clinic->name ?? '-'); ?>

                                <div class="text-[10px] text-slate-400 font-normal"><?php echo e($q->doctor->name ?? '-'); ?></div>
                            </td>
                            <td class="py-3 px-2">
                                <?php
                                    $badgeClass = match($q->status) {
                                        'menunggu' => 'bg-amber-100 text-amber-800',
                                        'dipanggil' => 'bg-sky-100 text-sky-800 font-bold animate-pulse',
                                        'pemeriksaan' => 'bg-purple-100 text-purple-800',
                                        'selesai' => 'bg-emerald-100 text-emerald-800',
                                        default => 'bg-slate-100 text-slate-800',
                                    };
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide <?php echo e($badgeClass); ?>">
                                    <?php echo e($q->status); ?>

                                </span>
                            </td>
                            <td class="py-3 px-2 text-right">
                                <a href="<?php echo e(route('registrations.show', $q)); ?>" class="text-sky-600 hover:text-sky-800 font-bold text-xs">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="py-12 text-center text-slate-400">
                <i class="fa-solid fa-calendar-xmark text-3xl mb-2"></i>
                <p class="text-xs font-semibold">Belum ada pendaftaran antrian hari ini.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Today's Doctor Schedules (1 col) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Jadwal Dokter Hari Ini</h3>
                        <p class="text-xs text-slate-500">Praktek aktif hari ini</p>
                    </div>
                </div>

                <?php if(count($todaySchedules) > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $todaySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/60 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800"><?php echo e($sch->doctor->name ?? '-'); ?></h4>
                            <p class="text-[10px] text-sky-600 font-semibold"><?php echo e($sch->doctor->clinic->name ?? 'Poli Umum'); ?></p>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] font-extrabold text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200 shadow-xs">
                                <?php echo e(substr($sch->start_time, 0, 5)); ?> - <?php echo e(substr($sch->end_time, 0, 5)); ?>

                            </span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="py-8 text-center text-slate-400 text-xs">
                    Tidak ada jadwal dokter praktek hari ini.
                </div>
                <?php endif; ?>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <a href="<?php echo e(route('schedules.index')); ?>" class="text-xs font-bold text-sky-600 hover:underline">
                    Kelola Semua Jadwal Dokter &rarr;
                </a>
            </div>
        </div>

    </div>

</div>

<!-- Chart.js Render Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const months = <?php echo json_encode($monthLabels); ?>;
        const visitsData = <?php echo json_encode($monthlyVisitsData); ?>;
        const revenueData = <?php echo json_encode($monthlyRevenueData); ?>;

        // Visits Chart
        const ctx1 = document.getElementById('visitsChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Jumlah Pasien',
                    data: visitsData,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#0284c7'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Revenue Chart
        const ctx2 = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueData,
                    backgroundColor: '#8b5cf6',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/dashboard/index.blade.php ENDPATH**/ ?>