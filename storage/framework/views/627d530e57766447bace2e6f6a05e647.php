<?php $__env->startSection('title', 'Kasir & Pembayaran Pasien'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="<?php echo e(route('payments.index')); ?>" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Status Tagihan --</option>
                <option value="unpaid" <?php echo e(request('status') == 'unpaid' ? 'selected' : ''); ?>>Belum Lunas (Unpaid)</option>
                <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Sudah Lunas (Paid)</option>
            </select>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Mata Transaksi Kasir Pembayaran</h3>
            <span class="text-xs text-slate-500 font-medium">Total: <?php echo e($payments->total()); ?> Tagihan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">No. Transaksi</th>
                        <th class="py-3 px-4">Pasien & No. RM</th>
                        <th class="py-3 px-4">Dokter & Poli</th>
                        <th class="py-3 px-4">Total Billing Tagihan</th>
                        <th class="py-3 px-4">Status Pembayaran</th>
                        <th class="py-3 px-4 text-center">Aksi / Kasir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            <span class="font-mono text-xs block text-sky-700 font-black"><?php echo e($pay->payment_number); ?></span>
                            <div class="text-[10px] text-slate-400 font-normal"><?php echo e(\Carbon\Carbon::parse($pay->created_at)->isoFormat('D MMM YYYY, HH:mm')); ?></div>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            <?php echo e($pay->patient->name ?? '-'); ?>

                            <div class="text-[10px] text-slate-400 font-normal">RM: <?php echo e($pay->patient->mr_number ?? '-'); ?></div>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">
                            <?php echo e($pay->registration->doctor->name ?? '-'); ?>

                            <div class="text-[10px] text-sky-600 font-normal"><?php echo e($pay->registration->clinic->name ?? 'Poli'); ?></div>
                        </td>
                        <td class="py-3.5 px-4 font-black text-emerald-700 text-sm">
                            Rp <?php echo e(number_format($pay->total_amount, 0, ',', '.')); ?>

                        </td>
                        <td class="py-3.5 px-4">
                            <?php if($pay->status === 'paid'): ?>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-800">
                                LUNAS
                            </span>
                            <?php else: ?>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-900 animate-pulse">
                                UNPAID / BELUM BAYAR
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="<?php echo e(route('payments.show', $pay)); ?>" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-bold transition-all shadow">
                                    <?php echo e($pay->status === 'paid' ? 'Detail Billing' : 'Proses Kasir'); ?>

                                </a>
                                <?php if($pay->status === 'paid'): ?>
                                <a href="<?php echo e(route('payments.invoice', $pay)); ?>" target="_blank" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold transition-all">
                                    <i class="fa-solid fa-receipt"></i> Kwitansi
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-receipt text-3xl mb-2"></i>
                            <p class="text-xs font-semibold">Belum ada tagihan transaksi kasir.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            <?php echo e($payments->links()); ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/payments/index.blade.php ENDPATH**/ ?>