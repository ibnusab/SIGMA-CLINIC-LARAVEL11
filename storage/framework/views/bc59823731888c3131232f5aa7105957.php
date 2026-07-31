<?php $__env->startSection('title', 'Rincian Billing & Kasir - ' . $payment->payment_number); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        
        <div class="flex items-center justify-between pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-mono font-black text-sky-700 bg-sky-100 px-3 py-1 rounded-lg">
                    <?php echo e($payment->payment_number); ?>

                </span>
                <h3 class="text-lg font-extrabold text-slate-900 mt-2">Tagihan Layanan Kasir Klinik</h3>
            </div>
            
            <a href="<?php echo e(route('payments.index')); ?>" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <!-- Patient Header -->
        <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <div>Nama Pasien: <strong class="text-slate-900 block font-bold text-sm"><?php echo e($payment->patient->name ?? '-'); ?></strong> (RM: <?php echo e($payment->patient->mr_number ?? '-'); ?>)</div>
            <div>Dokter Pemeriksa: <strong class="text-slate-900 block font-bold text-sm"><?php echo e($payment->registration->doctor->name ?? '-'); ?></strong></div>
            <div>Status Kunjungan: <strong class="uppercase font-bold text-sky-700"><?php echo e($payment->registration->visit_type ?? 'Umum'); ?></strong></div>
            <div>Status Tagihan: 
                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase <?php echo e(in_array(strtolower($payment->status), ['paid', 'lunas']) ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900'); ?>">
                    <?php echo e($payment->status); ?>

                </span>
            </div>
        </div>

        <!-- Billing Breakdown Table -->
        <div>
            <h4 class="font-extrabold text-slate-900 uppercase text-xs tracking-wider mb-3">Rincian Komponen Biaya</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100 font-bold uppercase text-[10px] text-slate-500">
                            <th class="p-3">Rincian Layanan / Obat</th>
                            <th class="p-3 text-right">Jumlah</th>
                            <th class="p-3 text-right">Harga Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        <tr>
                            <td class="p-3 font-bold text-slate-800">Biaya Konsultasi Dokter (<?php echo e($payment->registration->doctor->name ?? 'Dokter'); ?>)</td>
                            <td class="p-3 text-right">1 Jasa</td>
                            <td class="p-3 text-right font-bold text-slate-900">Rp <?php echo e(number_format($payment->consultation_fee, 0, ',', '.')); ?></td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-slate-800">Total Tindakan Medis</td>
                            <td class="p-3 text-right">-</td>
                            <td class="p-3 text-right font-bold text-slate-900">Rp <?php echo e(number_format($payment->treatment_fee, 0, ',', '.')); ?></td>
                        </tr>
                        <tr>
                            <td class="p-3 font-bold text-slate-800">Total Pengambilan Obat Apotek</td>
                            <td class="p-3 text-right">-</td>
                            <td class="p-3 text-right font-bold text-slate-900">Rp <?php echo e(number_format($payment->medicine_fee, 0, ',', '.')); ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-900 text-white font-extrabold text-sm">
                            <td colspan="2" class="p-4 text-right uppercase tracking-wider">TOTAL BILLING LUNAS:</td>
                            <td class="p-4 text-right text-emerald-400">Rp <?php echo e(number_format($payment->total_amount, 0, ',', '.')); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment Action Box -->
        <?php if(!in_array(strtolower($payment->status), ['paid', 'lunas'])): ?>
        <div class="bg-sky-50 p-6 rounded-3xl border border-sky-100 space-y-4">
            <h4 class="font-extrabold text-sky-950 text-sm flex items-center space-x-2">
                <i class="fa-solid fa-cash-register text-sky-600"></i>
                <span>Proses Pembayaran Kasir</span>
            </h4>

            <form action="<?php echo e(route('payments.pay', $payment)); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                        <select name="payment_method" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl font-semibold focus:outline-none">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="qris">QRIS (Digital Scan)</option>
                            <option value="debit">Kartu Debit / EDC</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="bpjs">Klaim BPJS / Asuransi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Jumlah Uang Diterima Pasien (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="paid_amount" value="<?php echo e($payment->total_amount); ?>" required min="<?php echo e($payment->total_amount); ?>" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl font-black text-slate-900 focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-check-double"></i>
                        <span>KONFIRMASI LUNAS & CETAK KWITANSI</span>
                    </button>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-emerald-900 flex items-center justify-between text-xs">
            <div>
                <strong class="font-bold block text-sm">Pembayaran Telah Lunas</strong>
                <span>Metode: <strong class="uppercase"><?php echo e($payment->payment_method); ?></strong> | Lunas pada: <?php echo e(\Carbon\Carbon::parse($payment->paid_at)->isoFormat('D MMMM YYYY, HH:mm')); ?> WIB</span>
            </div>

            <a href="<?php echo e(route('payments.invoice', $payment)); ?>" target="_blank" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow transition-all flex items-center space-x-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Kwitansi / Invoice</span>
            </a>
        </div>
        <?php endif; ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/payments/show.blade.php ENDPATH**/ ?>