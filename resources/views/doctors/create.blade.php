@extends('layouts.app')

@section('title', 'Tambah Dokter Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Input Dokter Baru</h3>
                <p class="text-xs text-slate-500">Sistem akan otomatis membuatkan akun login dokter berbasis email.</p>
            </div>
            <a href="{{ route('doctors.index') }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Dokter & Gelar <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="dr. Budi Santoso, Sp.PD">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIP / SIP Resmi <span class="text-rose-500">*</span></label>
                    <input type="text" name="nip_sip" value="{{ old('nip_sip') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="SIP-2026/01/440/PDL">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Poli Klinik <span class="text-rose-500">*</span></label>
                    <select name="clinic_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Pilih Poli Spesialis --</option>
                        @foreach($clinics as $c)
                        <option value="{{ $c->id }}" {{ old('clinic_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Spesialisasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="specialization" value="{{ old('specialization') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Spesialis Penyakit Dalam / Umum">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Telepon / HP <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="081234567890">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tarif Konsultasi (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="consultation_fee" value="{{ old('consultation_fee', 50000) }}" required min="0" step="5000" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <!-- Credentials for Login -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-sky-700 mb-3">Akun Login Dokter</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Kredensial <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="dokter.nama@sigmaclinic.com">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password Login <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Minimal 6 karakter">
                    </div>
                </div>
            </div>

            <!-- Photo Upload -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Foto Dokter (Opsional)</label>
                <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('doctors.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>SIMPAN DOKTER</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
