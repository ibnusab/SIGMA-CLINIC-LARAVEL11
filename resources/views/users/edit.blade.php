@extends('layouts.app')

@section('title', 'Edit Pengguna & Password')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Ubah Data Pengguna & Password — {{ $user->name }}</h3>
                <p class="text-xs text-slate-500">Kosongkan kolom password jika tidak ingin mengganti password akun</p>
            </div>
            <a href="{{ route('users.index') }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Pengguna <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Email Login <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password Baru (Opsional)</label>
                    <div class="relative">
                        <input type="password" id="edit_pass" name="password" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Kosongkan jika tidak diganti">
                        <button type="button" onclick="togglePasswordVisibility('edit_pass', 'icon_edit_pass')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1.5 flex items-center justify-center transition-colors" title="Lihat Password">
                            <i id="icon_edit_pass" class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" id="edit_pass_confirm" name="password_confirmation" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Ulangi password baru">
                        <button type="button" onclick="togglePasswordVisibility('edit_pass_confirm', 'icon_edit_confirm')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1.5 flex items-center justify-center transition-colors" title="Lihat Password">
                            <i id="icon_edit_confirm" class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Peran Akses (Role System) <span class="text-rose-500">*</span></label>
                    <select name="role" id="role_select" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        @foreach($rolesList as $key => $label)
                            <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Handphone / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <!-- Doctor Specific Details -->
            <div id="doctor_fields" class="p-4 bg-emerald-50/70 border border-emerald-200 rounded-2xl space-y-4 {{ old('role', $user->role) === 'dokter' ? '' : 'hidden' }}">
                <div class="flex items-center space-x-2 text-emerald-900 font-bold text-xs">
                    <i class="fa-solid fa-user-doctor"></i>
                    <span>Informasi Detail Dokter & Relasi Poli Klinik:</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Poli Klinik Tujuan <span class="text-rose-500">*</span></label>
                        <select name="clinic_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500">
                            <option value="">-- Pilih Poli --</option>
                            @foreach($clinics as $c)
                                <option value="{{ $c->id }}" {{ old('clinic_id', $user->doctor->clinic_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Spesialisasi / Keahlian</label>
                        <input type="text" name="specialization" value="{{ old('specialization', $user->doctor->specialization ?? '') }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">NIP / SIP Dokter</label>
                        <input type="text" name="nip_sip" value="{{ old('nip_sip', $user->doctor->nip_sip ?? '') }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tarif Konsultasi (Rp)</label>
                        <input type="number" name="consultation_fee" value="{{ old('consultation_fee', $user->doctor->consultation_fee ?? 50000) }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Keaktifan Akun <span class="text-rose-500">*</span></label>
                <select name="is_active" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif (Bisa Akses Sistem)</option>
                    <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Non-Aktif (Blokir Akses)</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
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
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

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
@endsection
