@extends('layouts.app')

@section('title', 'Edit Data Pasien')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Edit Pasien - {{ $patient->name }}</h3>
                <p class="text-xs text-slate-500">No. Rekam Medis: <strong>{{ $patient->mr_number }}</strong></p>
            </div>
            <a href="{{ route('patients.show', $patient) }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Batal
            </a>
        </div>

        <form action="{{ route('patients.update', $patient) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIK (16 Digit)</label>
                    <input type="text" name="nik" value="{{ old('nik', $patient->nik) }}" maxlength="16" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Pasien</label>
                    <input type="text" name="name" value="{{ old('name', $patient->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Kelamin</label>
                    <select name="gender" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="L" {{ old('gender', $patient->gender) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender', $patient->gender) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $patient->birth_date->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor HP / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Golongan Darah</label>
                    <select name="blood_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Belum Tahu --</option>
                        <option value="A" {{ old('blood_type', $patient->blood_type) === 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('blood_type', $patient->blood_type) === 'B' ? 'selected' : '' }}>B</option>
                        <option value="AB" {{ old('blood_type', $patient->blood_type) === 'AB' ? 'selected' : '' }}>AB</option>
                        <option value="O" {{ old('blood_type', $patient->blood_type) === 'O' ? 'selected' : '' }}>O</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap</label>
                <textarea name="address" rows="3" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">{{ old('address', $patient->address) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Riwayat Alergi</label>
                <input type="text" name="allergies" value="{{ old('allergies', $patient->allergies) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="if(confirm('Yakin ingin menghapus pasien ini?')) document.getElementById('delete-form').submit()" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-all">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Pasien
                </button>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('patients.show', $patient) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>UPDATE PASIEN</span>
                    </button>
                </div>
            </div>

        </form>

        <form id="delete-form" action="{{ route('patients.destroy', $patient) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

</div>
@endsection
