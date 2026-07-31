<?php $__env->startSection('title', 'Pengaturan Klinik & Sistem SIGMA'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Pengaturan Identitas SIGMA CLINIC</h3>
                <p class="text-xs text-slate-500">Konfigurasi nama klinik, nomor izin SIP, dan alamat kop cetakan</p>
            </div>
            <span class="px-3 py-1 bg-sky-100 text-sky-800 font-extrabold text-[10px] rounded-lg font-mono">
                v11.0.0 (Laravel 11)
            </span>
        </div>

        <form action="<?php echo e(route('settings.update')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Resmi Klinik <span class="text-rose-500">*</span></label>
                    <input type="text" name="clinic_name" value="<?php echo e(old('clinic_name', $settings['clinic_name'] ?? 'SIGMA CLINIC UTAMA')); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Telepon Hotline <span class="text-rose-500">*</span></label>
                    <input type="text" name="clinic_phone" value="<?php echo e(old('clinic_phone', $settings['clinic_phone'] ?? '(021) 7890123')); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Resmi</label>
                    <input type="email" name="clinic_email" value="<?php echo e(old('clinic_email', $settings['clinic_email'] ?? 'info@sigmaclinic.id')); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Izin Operasional Klinik (SIP)</label>
                    <input type="text" name="clinic_sip" value="<?php echo e(old('clinic_sip', $settings['clinic_sip'] ?? '440/123/DISKES/2026')); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap (Ditampilkan di Kop Rekam Medis & Invoice)</label>
                <textarea name="clinic_address" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500"><?php echo e(old('clinic_address', $settings['clinic_address'] ?? 'Jl. Kesehatan No. 88, Jakarta Selatan')); ?></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all">
                    SIMPAN KONFIGURASI
                </button>
            </div>

        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/settings/index.blade.php ENDPATH**/ ?>