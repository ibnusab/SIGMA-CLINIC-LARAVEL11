@extends('layouts.app')

@section('title', 'Edit Tindakan Medis')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Edit Tindakan - {{ $treatment->name }}</h3>
                <p class="text-xs text-slate-500">Ubah nama atau tarif tindakan medis</p>
            </div>
            <a href="{{ route('treatments.index') }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('treatments.update', $treatment) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Tindakan</label>
                <input type="text" name="code" value="{{ old('code', $treatment->code) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Tindakan</label>
                <input type="text" name="name" value="{{ old('name', $treatment->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori</label>
                <input type="text" name="category" value="{{ old('category', $treatment->category) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tarif Layanan (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $treatment->price) }}" required min="0" step="5000" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi Layanan</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">{{ old('description', $treatment->description) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="if(confirm('Hapus tindakan ini?')) document.getElementById('delete-treat-form').submit()" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-all">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Tindakan
                </button>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('treatments.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all">
                        UPDATE TINDAKAN
                    </button>
                </div>
            </div>

        </form>

        <form id="delete-treat-form" action="{{ route('treatments.destroy', $treatment) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

</div>
@endsection
