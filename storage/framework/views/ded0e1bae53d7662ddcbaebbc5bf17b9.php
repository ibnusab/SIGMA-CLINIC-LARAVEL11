<?php $__env->startSection('title', 'Edit Pengguna & Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Ubah Data Pengguna & Password — <?php echo e($user->name); ?></h3>
                <p class="text-xs text-slate-500">Kosongkan kolom password jika tidak ingin mengganti password akun</p>
            </div>
            <a href="<?php echo e(route('users.index')); ?>" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="<?php echo e(route('users.update', $user)); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Pengguna <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Email Login <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Kosongkan jika tidak diganti">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Ulangi password baru">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Peran Akses (Role System) <span class="text-rose-500">*</span></label>
                    <select name="role" id="role_select" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <?php $__currentLoopData = $rolesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('role', $user->role) == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Handphone / WhatsApp</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <!-- Doctor Specific Details -->
            <div id="doctor_fields" class="p-4 bg-emerald-50/70 border border-emerald-200 rounded-2xl space-y-4 <?php echo e(old('role', $user->role) === 'dokter' ? '' : 'hidden'); ?>">
                <div class="flex items-center space-x-2 text-emerald-900 font-bold text-xs">
                    <i class="fa-solid fa-user-doctor"></i>
                    <span>Informasi Detail Dokter & Relasi Poli Klinik:</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Poli Klinik Tujuan <span class="text-rose-500">*</span></label>
                        <select name="clinic_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500">
                            <option value="">-- Pilih Poli --</option>
                            <?php $__currentLoopData = $clinics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>" <?php echo e(old('clinic_id', $user->doctor->clinic_id ?? '') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?> (<?php echo e($c->code); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Spesialisasi / Keahlian</label>
                        <input type="text" name="specialization" value="<?php echo e(old('specialization', $user->doctor->specialization ?? '')); ?>" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">NIP / SIP Dokter</label>
                        <input type="text" name="nip_sip" value="<?php echo e(old('nip_sip', $user->doctor->nip_sip ?? '')); ?>" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tarif Konsultasi (Rp)</label>
                        <input type="number" name="consultation_fee" value="<?php echo e(old('consultation_fee', $user->doctor->consultation_fee ?? 50000)); ?>" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Keaktifan Akun <span class="text-rose-500">*</span></label>
                <select name="is_active" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    <option value="1" <?php echo e(old('is_active', $user->is_active) ? 'selected' : ''); ?>>Aktif (Bisa Akses Sistem)</option>
                    <option value="0" <?php echo e(!old('is_active', $user->is_active) ? 'selected' : ''); ?>>Non-Aktif (Blokir Akses)</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="<?php echo e(route('users.index')); ?>" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role_select');
    const doctorFields = document.getElementById('doctor_fields');

    function toggleDoctorFields() {
        if (roleSelect.value === 'dokter') {
            doctorFields.classList.remove('hidden');
        } else {
            doctorFields.classList.add('hidden');
        }
    }

    roleSelect.addEventListener('change', toggleDoctorFields);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/users/edit.blade.php ENDPATH**/ ?>