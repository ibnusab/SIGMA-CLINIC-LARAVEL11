<?php $__env->startSection('title', 'Resep & Pengeluaran Obat Apotek'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="<?php echo e(route('prescriptions.index')); ?>" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Status Resep --</option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Menunggu Diproses Apotek</option>
                <option value="processed" <?php echo e(request('status') == 'processed' ? 'selected' : ''); ?>>Selesai Disiapkan (Diproses)</option>
                <option value="taken" <?php echo e(request('status') == 'taken' ? 'selected' : ''); ?>>Obat Sudah Diambil Pasien</option>
            </select>
        </form>

        <a href="<?php echo e(route('prescriptions.create')); ?>" class="w-full md:w-auto px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-600/20 flex items-center justify-center space-x-2 transition-all">
            <i class="fa-solid fa-file-prescription"></i>
            <span>Buat Resep Obat Manual</span>
        </a>
    </div>

    <!-- Prescriptions Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Mata Resep Dokter & Apotek</h3>
            <span class="text-xs text-slate-500 font-medium">Total: <?php echo e($prescriptions->total()); ?> Resep</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">No. Resep & Waktu</th>
                        <th class="py-3 px-4">Pasien & No. RM</th>
                        <th class="py-3 px-4">Dokter Peresep</th>
                        <th class="py-3 px-4">Total Biaya Obat</th>
                        <th class="py-3 px-4">Status Apotek</th>
                        <th class="py-3 px-4 text-center">Aksi / Penyiapan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $prescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-purple-950">
                            <span class="font-mono text-xs block text-purple-700 font-black"><?php echo e($p->prescription_number); ?></span>
                            <div class="text-[10px] text-slate-400 font-normal"><?php echo e(\Carbon\Carbon::parse($p->created_at)->isoFormat('D MMM YYYY, HH:mm')); ?></div>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            <?php echo e($p->patient->name ?? '-'); ?>

                            <div class="text-[10px] text-slate-400 font-normal">RM: <?php echo e($p->patient->mr_number ?? '-'); ?></div>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">
                            <?php echo e($p->doctor->name ?? '-'); ?>

                        </td>
                        <td class="py-3.5 px-4 font-extrabold text-emerald-700">
                            Rp <?php echo e(number_format($p->total_amount ?? $p->total_price ?? 0, 0, ',', '.')); ?>

                        </td>
                        <td class="py-3.5 px-4">
                            <?php
                                $badgeClass = match($p->status) {
                                    'pending' => 'bg-amber-100 text-amber-900 animate-pulse font-bold',
                                    'processed' => 'bg-purple-100 text-purple-800 font-bold',
                                    'taken' => 'bg-emerald-100 text-emerald-800 font-bold',
                                    default => 'bg-slate-100 text-slate-800',
                                };
                            ?>
                            <span class="px-2.5 py-1 rounded-full text-[10px] uppercase tracking-wide <?php echo e($badgeClass); ?>">
                                <?php echo e($p->status); ?>

                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="<?php echo e(route('prescriptions.show', $p)); ?>" class="px-3 py-1.5 bg-purple-50 text-purple-700 hover:bg-purple-600 hover:text-white rounded-lg text-xs font-bold transition-all inline-block">
                                Prosedur Resep &rarr;
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-file-prescription text-3xl mb-2"></i>
                            <p class="text-xs font-semibold">Belum ada resep terdaftar.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            <?php echo e($prescriptions->links()); ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/prescriptions/index.blade.php ENDPATH**/ ?>