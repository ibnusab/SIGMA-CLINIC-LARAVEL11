<?php $__env->startSection('title', 'Pemeriksaan Dokter (Rekam Medis)'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <!-- Filter & Action Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="<?php echo e(route('medical-records.index')); ?>" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari No. Medis, Diagnosa, Nama Pasien, No. RM..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition-all">
                Cari
            </button>
        </form>

        <a href="<?php echo e(route('medical-records.create')); ?>" class="w-full md:w-auto px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center justify-center space-x-2 transition-all">
            <i class="fa-solid fa-stethoscope"></i>
            <span>Input Pemeriksaan Medis</span>
        </a>
    </div>

    <!-- Medical Records Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Mata Rekam Medis Pemeriksaan</h3>
            <span class="text-xs text-slate-500 font-medium">Total: <?php echo e($records->total()); ?> Record</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">No. Rekam Medis</th>
                        <th class="py-3 px-4">Tanggal & Waktu</th>
                        <th class="py-3 px-4">Pasien</th>
                        <th class="py-3 px-4">Dokter Pemeriksa</th>
                        <th class="py-3 px-4">Diagnosa Medis</th>
                        <th class="py-3 px-4 text-center">Aksi / PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4">
                            <span class="font-black text-sky-600 font-mono text-xs">
                                <?php echo e($mr->record_number); ?>

                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-700">
                            <?php echo e(\Carbon\Carbon::parse($mr->examination_date)->isoFormat('D MMM YYYY, HH:mm')); ?> WIB
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            <?php echo e($mr->patient->name ?? '-'); ?>

                            <div class="text-[10px] text-slate-400 font-normal">RM: <?php echo e($mr->patient->mr_number ?? '-'); ?></div>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">
                            <?php echo e($mr->doctor->name ?? '-'); ?>

                            <div class="text-[10px] text-sky-600 font-normal"><?php echo e($mr->doctor->clinic->name ?? 'Poli'); ?></div>
                        </td>
                        <td class="py-3.5 px-4">
                            <strong class="text-slate-900 block truncate max-w-xs"><?php echo e($mr->diagnosis); ?></strong>
                            <div class="text-[10px] text-slate-400 truncate max-w-xs">Keluhan: <?php echo e($mr->complaints); ?></div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="<?php echo e(route('medical-records.show', $mr)); ?>" class="px-2.5 py-1.5 bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white rounded-lg text-xs font-bold transition-all">
                                    Detail
                                </a>
                                <a href="<?php echo e(route('medical-records.pdf', $mr)); ?>" class="px-2.5 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg text-xs font-bold transition-all">
                                    <i class="fa-solid fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-folder-xmark text-3xl mb-2"></i>
                            <p class="text-xs font-semibold">Belum ada rekam medis terdaftar.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            <?php echo e($records->links()); ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/medical-records/index.blade.php ENDPATH**/ ?>