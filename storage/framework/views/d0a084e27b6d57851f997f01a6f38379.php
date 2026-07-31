<?php $__env->startSection('title', 'Tambah Obat Baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Tambah Obat Baru</h3>
                <p class="text-xs text-slate-500">Input data stok inventaris farmasi dan harga obat</p>
            </div>
            <a href="<?php echo e(route('medicines.index')); ?>" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="<?php echo e(route('medicines.store')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Obat <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" value="<?php echo e(old('code')); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="OBT-PCT-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Obat <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Paracetamol 500mg Tablet">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Obat <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Obat Bebas" <?php echo e(old('category') === 'Obat Bebas' ? 'selected' : ''); ?>>Obat Bebas</option>
                        <option value="Obat Keras" <?php echo e(old('category') === 'Obat Keras' ? 'selected' : ''); ?>>Obat Keras (Resep)</option>
                        <option value="Antibiotik" <?php echo e(old('category') === 'Antibiotik' ? 'selected' : ''); ?>>Antibiotik</option>
                        <option value="Vitamin & Suplemen" <?php echo e(old('category') === 'Vitamin & Suplemen' ? 'selected' : ''); ?>>Vitamin & Suplemen</option>
                        <option value="Alat Kesehatan" <?php echo e(old('category') === 'Alat Kesehatan' ? 'selected' : ''); ?>>Alat Kesehatan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Satuan <span class="text-rose-500">*</span></label>
                    <input type="text" name="unit" value="<?php echo e(old('unit', 'Tablet')); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Tablet / Botol / Strip / Ampul">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Beli Modal (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="purchase_price" value="<?php echo e(old('purchase_price', 1000)); ?>" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Jual Pasien (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="selling_price" value="<?php echo e(old('selling_price', 2500)); ?>" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Stok Awal <span class="text-rose-500">*</span></label>
                    <input type="number" name="stock" value="<?php echo e(old('stock', 100)); ?>" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Batas Minimum Stok (Peringatan Restock) <span class="text-rose-500">*</span></label>
                    <input type="number" name="min_stock" value="<?php echo e(old('min_stock', 20)); ?>" required min="1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Supplier Farmasi</label>
                    <select name="supplier_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Tanpa Supplier / Lainnya --</option>
                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php echo e(old('supplier_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Kadaluarsa (Expired)</label>
                    <input type="date" name="expiry_date" value="<?php echo e(old('expiry_date')); ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi & Efek Samping Obat</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Keterangan dosis umum, indikasi obat..."><?php echo e(old('description')); ?></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="<?php echo e(route('medicines.index')); ?>" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all">
                    SIMPAN OBAT
                </button>
            </div>

        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/medicines/create.blade.php ENDPATH**/ ?>