<?php $__env->startSection('title', 'Daftar Antrian Kunjungan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Pendaftaran Kunjungan Pasien</h3>
                <p class="text-xs text-slate-500">Nomor antrian akan di-generate otomatis dan kuota dokter diperbarui secara real-time</p>
            </div>
            <a href="<?php echo e(route('registrations.index')); ?>" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <?php if($errors->any()): ?>
        <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold space-y-1">
            <div class="flex items-center space-x-2 text-rose-700">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
                <span>Gagal Melakukan Pendaftaran:</span>
            </div>
            <ul class="list-disc list-inside font-medium text-rose-700">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('registrations.store')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>

            <!-- Patient Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Pasien Terdaftar <span class="text-rose-500">*</span></label>
                <select name="patient_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    <option value="">-- Pilih Pasien (Ketik / Cari Nama) --</option>
                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" <?php echo e((old('patient_id') == $p->id || $selectedPatientId == $p->id) ? 'selected' : ''); ?>>
                        [<?php echo e($p->mr_number); ?>] <?php echo e($p->name); ?> - NIK: <?php echo e($p->nik); ?> (HP: <?php echo e($p->phone); ?>)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="mt-1.5 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Pasien belum terdaftar?</span>
                    <a href="<?php echo e(route('patients.create')); ?>" class="text-sky-600 font-bold hover:underline">+ Registrasi Pasien Baru First</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Kunjungan <span class="text-rose-500">*</span></label>
                    <input type="date" name="registration_date" value="<?php echo e(old('registration_date', $regDate ?? date('Y-m-d'))); ?>" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    <p class="text-[10px] text-slate-400 mt-1">Hari: <strong class="text-slate-700"><?php echo e($dayName ?? 'Hari ini'); ?></strong></p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Poli Klinik <span class="text-rose-500">*</span></label>
                    <select name="clinic_id" id="clinic_select" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Pilih Poli Klinik --</option>
                        <?php $__currentLoopData = $clinics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php echo e(old('clinic_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?> (<?php echo e($c->code); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Dokter Spesialis & Sisa Kuota <span class="text-rose-500">*</span></label>
                    <select name="doctor_id" id="doctor_select" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Pilih Dokter --</option>
                        <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($d->id); ?>" 
                                data-clinic-id="<?php echo e($d->clinic_id); ?>" 
                                data-clinic-name="<?php echo e($d->clinic->name ?? 'Poli'); ?>"
                                data-specialization="<?php echo e($d->specialization); ?>"
                                data-fee="<?php echo e(number_format($d->consultation_fee, 0, ',', '.')); ?>"
                                <?php echo e(old('doctor_id') == $d->id ? 'selected' : ''); ?>>
                            <?php echo e($d->name); ?> — <?php echo e($d->specialization); ?> (<?php echo e($d->clinic->name ?? 'Poli'); ?>) 
                            <?php if(isset($d->schedule_info) && $d->schedule_info): ?>
                                - Sisa Kuota Hari Ini: <?php echo e($d->remaining_quota); ?> / <?php echo e($d->schedule_info->quota); ?> Pasien
                            <?php else: ?>
                                - (Jadwal Praktek Aktif)
                            <?php endif; ?>
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Kunjungan / Penjamin <span class="text-rose-500">*</span></label>
                    <select name="visit_type" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="umum" <?php echo e(old('visit_type') === 'umum' ? 'selected' : ''); ?>>Umum (Mandiri)</option>
                        <option value="bpjs" <?php echo e(old('visit_type') === 'bpjs' ? 'selected' : ''); ?>>BPJS Kesehatan</option>
                        <option value="asuransi" <?php echo e(old('visit_type') === 'asuransi' ? 'selected' : ''); ?>>Asuransi Swasta</option>
                    </select>
                </div>
            </div>

            <!-- Doctor & Clinic Match Info Card -->
            <div id="doctor_info_card" class="p-4 bg-sky-50/80 rounded-2xl border border-sky-200/80 text-xs text-sky-950 space-y-1 transition-all">
                <div class="flex items-center space-x-2 font-bold text-sky-900">
                    <i class="fa-solid fa-circle-info text-sky-600"></i>
                    <span>Informasi Otomatis Dokter & Poli Terpilih:</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-1 font-medium text-slate-700">
                    <div>Poli: <strong id="info_clinic" class="text-slate-900 font-extrabold">-</strong></div>
                    <div>Spesialisasi / Keahlian: <strong id="info_spec" class="text-sky-700 font-extrabold">-</strong></div>
                    <div>Biaya Konsultasi: <strong id="info_fee" class="text-emerald-700 font-black">Rp -</strong></div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keluhan Utama Pasien <span class="text-rose-500">*</span></label>
                <textarea name="complaint" rows="3" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Tuliskan keluhan utama pasien saat ini..."><?php echo e(old('complaint')); ?></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="<?php echo e(route('registrations.index')); ?>" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-print"></i>
                    <span>PROSES PENDAFTARAN & TIKET</span>
                </button>
            </div>

        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clinicSelect = document.getElementById('clinic_select');
    const doctorSelect = document.getElementById('doctor_select');
    const infoClinic = document.getElementById('info_clinic');
    const infoSpec = document.getElementById('info_spec');
    const infoFee = document.getElementById('info_fee');

    if (!clinicSelect || !doctorSelect) return;

    const doctorOptions = Array.from(doctorSelect.options);

    function updateInfoCard() {
        const selectedOpt = doctorSelect.options[doctorSelect.selectedIndex];
        if (selectedOpt && selectedOpt.value) {
            const clinicName = selectedOpt.getAttribute('data-clinic-name') || '-';
            const spec = selectedOpt.getAttribute('data-specialization') || '-';
            const fee = selectedOpt.getAttribute('data-fee') || '0';

            infoClinic.textContent = clinicName;
            infoSpec.textContent = spec;
            infoFee.textContent = 'Rp ' + fee;

            // Auto-sync clinic select if different
            const doctorClinicId = selectedOpt.getAttribute('data-clinic-id');
            if (doctorClinicId && clinicSelect.value !== doctorClinicId) {
                clinicSelect.value = doctorClinicId;
            }
        } else {
            const selectedClinicOpt = clinicSelect.options[clinicSelect.selectedIndex];
            infoClinic.textContent = selectedClinicOpt && selectedClinicOpt.value ? selectedClinicOpt.text : '-';
            infoSpec.textContent = '-';
            infoFee.textContent = 'Rp -';
        }
    }

    function filterDoctorsByClinic() {
        const selectedClinicId = clinicSelect.value;
        let visibleCount = 0;
        let lastVisibleDoc = null;

        doctorOptions.forEach(opt => {
            if (!opt.value) {
                opt.style.display = '';
                return;
            }

            const docClinicId = opt.getAttribute('data-clinic-id');
            if (!selectedClinicId || docClinicId === selectedClinicId) {
                opt.style.display = '';
                visibleCount++;
                lastVisibleDoc = opt;
            } else {
                opt.style.display = 'none';
            }
        });

        // Check if current selected doctor is hidden
        const currentDocOpt = doctorSelect.options[doctorSelect.selectedIndex];
        if (currentDocOpt && currentDocOpt.value && currentDocOpt.style.display === 'none') {
            doctorSelect.value = '';
        }

        // Auto select doctor if only 1 exists for this clinic
        if (selectedClinicId && visibleCount === 1 && lastVisibleDoc) {
            doctorSelect.value = lastVisibleDoc.value;
        }

        updateInfoCard();
    }

    clinicSelect.addEventListener('change', filterDoctorsByClinic);
    doctorSelect.addEventListener('change', function() {
        const selectedOpt = doctorSelect.options[doctorSelect.selectedIndex];
        if (selectedOpt && selectedOpt.value) {
            const docClinicId = selectedOpt.getAttribute('data-clinic-id');
            if (docClinicId) {
                clinicSelect.value = docClinicId;
            }
        }
        updateInfoCard();
    });

    // Initial sync
    filterDoctorsByClinic();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/registrations/create.blade.php ENDPATH**/ ?>