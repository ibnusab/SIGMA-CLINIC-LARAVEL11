<?php $__env->startSection('title', 'Edit / Restock Obat'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Edit & Restock - <?php echo e($medicine->name); ?></h3>
                <p class="text-xs text-slate-500">Kode Obat: <strong class="font-mono text-sky-600"><?php echo e($medicine->code); ?></strong></p>
            </div>
            <a href="<?php echo e(route('medicines.index')); ?>" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="<?php echo e(route('medicines.update', $medicine)); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Obat</label>
                    <input type="text" name="code" value="<?php echo e(old('code', $medicine->code)); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Obat</label>
                    <input type="text" name="name" value="<?php echo e(old('name', $medicine->name)); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori</label>
                    <select name="category" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="Obat Bebas" <?php echo e(old('category', $medicine->category) === 'Obat Bebas' ? 'selected' : ''); ?>>Obat Bebas</option>
                        <option value="Obat Keras" <?php echo e(old('category', $medicine->category) === 'Obat Keras' ? 'selected' : ''); ?>>Obat Keras (Resep)</option>
                        <option value="Antibiotik" <?php echo e(old('category', $medicine->category) === 'Antibiotik' ? 'selected' : ''); ?>>Antibiotik</option>
                        <option value="Vitamin & Suplemen" <?php echo e(old('category', $medicine->category) === 'Vitamin & Suplemen' ? 'selected' : ''); ?>>Vitamin & Suplemen</option>
                        <option value="Alat Kesehatan" <?php echo e(old('category', $medicine->category) === 'Alat Kesehatan' ? 'selected' : ''); ?>>Alat Kesehatan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Satuan</label>
                    <input type="text" name="unit" value="<?php echo e(old('unit', $medicine->unit)); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Beli Modal (Rp)</label>
                    <input type="number" name="purchase_price" value="<?php echo e(old('purchase_price', $medicine->purchase_price)); ?>" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Jual Pasien (Rp)</label>
                    <input type="number" name="selling_price" value="<?php echo e(old('selling_price', $medicine->selling_price)); ?>" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jumlah Stok Saat Ini</label>
                    <input type="number" name="stock" value="<?php echo e(old('stock', $medicine->stock)); ?>" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-sky-700 focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Batas Minimum Stok</label>
                    <input type="number" name="min_stock" value="<?php echo e(old('min_stock', $medicine->min_stock)); ?>" required min="1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Supplier Farmasi</label>
                    <select name="supplier_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Tanpa Supplier --</option>
                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php echo e(old('supplier_id', $medicine->supplier_id) == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Kadaluarsa</label>
                    <input type="date" name="expiry_date" value="<?php echo e(old('expiry_date', $medicine->expiry_date ? $medicine->expiry_date->format('Y-m-d') : '')); ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="if(confirm('Hapus obat ini dari inventaris?')) document.getElementById('delete-med-form').submit()" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-all">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Obat
                </button>

                <div class="flex items-center space-x-3">
                    <a href="<?php echo e(route('medicines.index')); ?>" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all">
                        UPDATE OBAT
                    </button>
                </div>
            </div>

        </form>

        <form id="delete-med-form" action="<?php echo e(route('medicines.destroy', $medicine)); ?>" method="POST" class="hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/medicines/edit.blade.php ENDPATH**/ ?>