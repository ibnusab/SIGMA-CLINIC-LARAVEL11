<?php $__env->startSection('title', 'Supplier Farmasi & Pemasok Obat'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Daftar Distributor Supplier Obat</h3>
            <p class="text-xs text-slate-500">Master supplier pemasok obat dan alat kesehatan</p>
        </div>

        <a href="<?php echo e(route('suppliers.create')); ?>" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center space-x-2 transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Supplier Baru</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Nama Supplier</th>
                        <th class="py-3 px-4">Kontak Telepon & Email</th>
                        <th class="py-3 px-4">Alamat Kantor</th>
                        <th class="py-3 px-4">Obat Disuplay</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    <?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-600"><?php echo e($s->code); ?></td>
                        <td class="py-3.5 px-4 font-bold text-slate-900"><?php echo e($s->name); ?></td>
                        <td class="py-3.5 px-4 text-slate-700">
                            <i class="fa-solid fa-phone text-sky-500 mr-1"></i> <?php echo e($s->phone); ?>

                            <div class="text-[10px] text-slate-400"><?php echo e($s->email ?? '-'); ?></div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 truncate max-w-xs"><?php echo e($s->address ?? '-'); ?></td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-800">
                                <?php echo e($s->medicines_count); ?> Jenis Obat
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="<?php echo e(route('suppliers.edit', $s)); ?>" class="text-sky-600 hover:text-sky-800 font-bold">
                                Edit Supplier
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <p class="text-xs font-semibold">Belum ada supplier terdaftar.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            <?php echo e($suppliers->links()); ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/suppliers/index.blade.php ENDPATH**/ ?>