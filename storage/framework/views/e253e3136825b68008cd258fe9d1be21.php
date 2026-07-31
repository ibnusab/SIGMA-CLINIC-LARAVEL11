<?php $__env->startSection('title', 'Detail Akun Pengguna'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-600 text-white font-extrabold flex items-center justify-center text-xl shadow-md shadow-sky-600/20">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900"><?php echo e($user->name); ?></h3>
                    <p class="text-xs text-slate-500">ID User: #<?php echo e(str_pad($user->id, 4, '0', STR_PAD_LEFT)); ?> • Terdaftar <?php echo e($user->created_at ? $user->created_at->isoFormat('D MMMM YYYY') : '-'); ?></p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="<?php echo e(route('users.edit', $user)); ?>" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    <i class="fa-solid fa-key mr-1"></i> Edit & Password
                </a>
                <a href="<?php echo e(route('users.index')); ?>" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
            <div class="space-y-4">
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Email Login:</span>
                    <p class="font-mono font-bold text-slate-900 text-sm mt-0.5"><?php echo e($user->email); ?></p>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Nomor Telepon:</span>
                    <p class="font-semibold text-slate-800 mt-0.5"><?php echo e($user->phone ?? 'Belum diisi'); ?></p>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Hak Akses (Role):</span>
                    <div class="mt-1">
                        <?php
                            $r = strtolower($user->role);
                            $badgeClass = match($r) {
                                'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'dokter' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'apoteker' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'resepsionis' => 'bg-sky-100 text-sky-800 border-sky-200',
                                default => 'bg-slate-100 text-slate-800 border-slate-200',
                            };
                        ?>
                        <span class="px-3 py-1 rounded-xl text-xs font-extrabold uppercase border inline-block <?php echo e($badgeClass); ?>">
                            <?php echo e(strtoupper($user->role)); ?>

                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Status Akun:</span>
                    <p class="mt-0.5">
                        <?php if($user->is_active): ?>
                            <span class="px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-emerald-100 text-emerald-800 inline-block">
                                <i class="fa-solid fa-circle-check mr-1"></i> Aktif (Bisa Login)
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-rose-100 text-rose-800 inline-block">
                                <i class="fa-solid fa-circle-xmark mr-1"></i> Non-Aktif (Terblokir)
                            </span>
                        <?php endif; ?>
                    </p>
                </div>

                <?php if($user->doctor): ?>
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <span class="text-sky-900 font-extrabold text-xs block">
                        <i class="fa-solid fa-hospital-user mr-1 text-sky-600"></i> Relasi Profil Dokter
                    </span>
                    <div class="text-slate-700">Poli: <strong class="text-slate-900"><?php echo e($user->doctor->clinic->name ?? 'Poli Umum'); ?></strong></div>
                    <div class="text-slate-700">Spesialisasi: <strong class="text-sky-700"><?php echo e($user->doctor->specialization); ?></strong></div>
                    <div class="text-slate-700">NIP/SIP: <span class="font-mono"><?php echo e($user->doctor->nip_sip); ?></span></div>
                    <div class="text-slate-700">Tarif Konsultasi: <strong class="text-emerald-700">Rp <?php echo e(number_format($user->doctor->consultation_fee, 0, ',', '.')); ?></strong></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/users/show.blade.php ENDPATH**/ ?>