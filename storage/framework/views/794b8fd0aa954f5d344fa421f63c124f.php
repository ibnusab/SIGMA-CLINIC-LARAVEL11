<?php $__env->startSection('title', 'Pendaftaran & Antrian Pasien'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="<?php echo e(route('registrations.index')); ?>" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <input type="date" name="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
            
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Status --</option>
                <option value="menunggu" <?php echo e(request('status') == 'menunggu' ? 'selected' : ''); ?>>Menunggu</option>
                <option value="dipanggil" <?php echo e(request('status') == 'dipanggil' ? 'selected' : ''); ?>>Dipanggil</option>
                <option value="pemeriksaan" <?php echo e(request('status') == 'pemeriksaan' ? 'selected' : ''); ?>>Pemeriksaan</option>
                <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                <option value="batal" <?php echo e(request('status') == 'batal' ? 'selected' : ''); ?>>Batal</option>
            </select>

            <select name="clinic_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Poli --</option>
                <?php $__currentLoopData = $clinics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c->id); ?>" <?php echo e(request('clinic_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>

        <a href="<?php echo e(route('registrations.create')); ?>" class="w-full md:w-auto px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center justify-center space-x-2 transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Daftar Antrian Baru</span>
        </a>
    </div>

    <!-- Registrations Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">
                Antrian Kunjungan Pasien (<?php echo e(\Carbon\Carbon::parse(request('date', date('Y-m-d')))->isoFormat('D MMMM YYYY')); ?>)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">No. Antrian</th>
                        <th class="py-3 px-4">Pasien</th>
                        <th class="py-3 px-4">Poli & Dokter</th>
                        <th class="py-3 px-4">Jenis & Keluhan</th>
                        <th class="py-3 px-4">Status Antrian</th>
                        <th class="py-3 px-4 text-center">Aksi / Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4">
                            <div class="text-base font-black text-sky-600 font-mono">
                                #<?php echo e(str_pad($r->queue_number, 2, '0', STR_PAD_LEFT)); ?>

                            </div>
                            <div class="text-[10px] text-slate-400 font-semibold"><?php echo e($r->registration_number); ?></div>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            <?php echo e($r->patient->name ?? '-'); ?>

                            <div class="text-[10px] text-slate-400 font-normal">RM: <?php echo e($r->patient->mr_number ?? '-'); ?></div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-slate-800"><?php echo e($r->clinic->name ?? '-'); ?></span>
                            <div class="text-[10px] text-slate-500 font-medium"><?php echo e($r->doctor->name ?? '-'); ?></div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase <?php echo e($r->visit_type_badge); ?>">
                                <?php echo e(strtoupper(!empty($r->visit_type) ? $r->visit_type : 'umum')); ?>

                            </span>
                            <div class="text-[10px] text-slate-600 font-medium truncate max-w-xs mt-1" title="<?php echo e($r->complaint ?? $r->complaints ?? 'Pemeriksaan Rutin'); ?>">
                                <?php echo e($r->complaint ?? $r->complaints ?? 'Pemeriksaan Rutin'); ?>

                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <?php
                                $badgeClass = match($r->status) {
                                    'menunggu' => 'bg-amber-100 text-amber-800',
                                    'dipanggil' => 'bg-sky-100 text-sky-800 font-black animate-pulse',
                                    'pemeriksaan' => 'bg-purple-100 text-purple-800',
                                    'selesai' => 'bg-emerald-100 text-emerald-800',
                                    'batal' => 'bg-rose-100 text-rose-800',
                                    default => 'bg-slate-100 text-slate-800',
                                };
                            ?>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide <?php echo e($badgeClass); ?>">
                                <?php echo e($r->status); ?>

                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center space-x-1.5">
                                <!-- Status Changer Form -->
                                <form action="<?php echo e(route('registrations.update-status', $r)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="status" onchange="this.form.submit()" class="px-2 py-1 bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold focus:outline-none">
                                        <option value="menunggu" <?php echo e($r->status === 'menunggu' ? 'selected' : ''); ?>>Menunggu</option>
                                        <option value="dipanggil" <?php echo e($r->status === 'dipanggil' ? 'selected' : ''); ?>>Dipanggil</option>
                                        <option value="pemeriksaan" <?php echo e($r->status === 'pemeriksaan' ? 'selected' : ''); ?>>Pemeriksaan</option>
                                        <option value="selesai" <?php echo e($r->status === 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                                        <option value="batal" <?php echo e($r->status === 'batal' ? 'selected' : ''); ?>>Batal</option>
                                    </select>
                                </form>

                                <a href="<?php echo e(route('registrations.ticket', $r)); ?>" target="_blank" title="Cetak Tiket Antrian" class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-ticket text-xs"></i>
                                </a>

                                <?php if(in_array(Auth::user()->role ?? 'admin', ['admin', 'dokter']) && in_array($r->status, ['dipanggil', 'pemeriksaan'])): ?>
                                <a href="<?php echo e(route('medical-records.create', ['registration_id' => $r->id])); ?>" title="Mulai Pemeriksaan Dokter" class="w-7 h-7 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition-all shadow">
                                    <i class="fa-solid fa-stethoscope text-xs"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-clipboard-question text-3xl mb-2"></i>
                            <p class="text-xs font-semibold">Tidak ada data pendaftaran antrian pada tanggal ini.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            <?php echo e($registrations->links()); ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/registrations/index.blade.php ENDPATH**/ ?>