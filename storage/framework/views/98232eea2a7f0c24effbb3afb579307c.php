<?php $__env->startSection('title', 'Detail Pendaftaran #' . $registration->registration_number); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Header -->
    <div class="flex items-center justify-between">
        <a href="<?php echo e(route('registrations.index')); ?>" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Antrian</span>
        </a>

        <div class="flex items-center space-x-2">
            <a href="<?php echo e(route('registrations.ticket', $registration)); ?>" target="_blank" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-600/20 flex items-center space-x-2 transition-all">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Tiket Antrian</span>
            </a>

            <?php if(in_array($registration->status, ['dipanggil', 'pemeriksaan', 'menunggu'])): ?>
            <a href="<?php echo e(route('medical-records.create', ['registration_id' => $registration->id])); ?>" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center space-x-2 transition-all">
                <i class="fa-solid fa-stethoscope"></i>
                <span>Pemeriksaan Medis</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-100 pb-5 gap-4">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Nomor Registrasi</span>
                <h2 class="text-2xl font-black text-slate-900"><?php echo e($registration->registration_number); ?></h2>
                <p class="text-xs text-slate-500">Tanggal: <?php echo e(\Carbon\Carbon::parse($registration->registration_date)->isoFormat('D MMMM YYYY')); ?></p>
            </div>

            <div class="flex items-center space-x-3 bg-sky-50 px-5 py-3 rounded-2xl border border-sky-100">
                <div class="text-right">
                    <span class="text-[10px] font-bold text-sky-600 uppercase">Nomor Antrian</span>
                    <div class="text-3xl font-black text-sky-600 font-mono">#<?php echo e(str_pad($registration->queue_number, 2, '0', STR_PAD_LEFT)); ?></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Patient Info -->
            <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-100 space-y-2">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Informasi Pasien</h4>
                <div class="text-sm font-bold text-slate-900"><?php echo e($registration->patient->name ?? '-'); ?></div>
                <div class="text-xs text-slate-600">No. Rekam Medis: <strong class="text-sky-700 font-mono"><?php echo e($registration->patient->mr_number ?? '-'); ?></strong></div>
                <div class="text-xs text-slate-600">NIK: <?php echo e($registration->patient->nik ?? '-'); ?></div>
                <div class="text-xs text-slate-600">No. HP: <?php echo e($registration->patient->phone ?? '-'); ?></div>
            </div>

            <!-- Doctor & Clinic Info -->
            <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-100 space-y-2">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Poli & Dokter Tujuan</h4>
                <div class="text-sm font-bold text-slate-900"><?php echo e($registration->clinic->name ?? '-'); ?></div>
                <div class="text-xs text-slate-600">Dokter: <strong class="text-slate-800"><?php echo e($registration->doctor->name ?? '-'); ?></strong></div>
                <?php if($registration->doctor && $registration->doctor->specialization): ?>
                <div class="text-xs text-slate-600">Keahlian: <span class="font-bold text-sky-700"><?php echo e($registration->doctor->specialization); ?></span></div>
                <?php endif; ?>
                <div class="text-xs text-slate-600">Status Antrian: <span class="uppercase font-bold text-sky-700"><?php echo e($registration->status); ?></span></div>
            </div>
        </div>

        <?php if($registration->complaints): ?>
        <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-1">Keluhan / Alasan Kunjungan</h4>
            <p class="text-xs text-slate-700 leading-relaxed"><?php echo e($registration->complaints); ?></p>
        </div>
        <?php endif; ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/registrations/show.blade.php ENDPATH**/ ?>