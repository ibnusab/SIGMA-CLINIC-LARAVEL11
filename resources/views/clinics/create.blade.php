@extends('layouts.app')

@section('title', 'Tambah Poli Klinik')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Tambah Poli Klinik</h3>
                <p class="text-xs text-slate-500">Masukkan data spesialisasi poli klinik baru</p>
            </div>
            <a href="{{ route('clinics.index') }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('clinics.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Poli <span class="text-rose-500">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="POL-UMM / POL-GGI">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Poli <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Poli Umum / Poli Gigi & Mulut">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Lokasi Ruangan</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Lantai 1, Ruang 101">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Layanan Poli</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Keterangan singkat pelayanan poli...">{{ old('description') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('clinics.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all">
                    SIMPAN POLI
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
