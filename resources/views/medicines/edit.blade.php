@extends('layouts.app')

@section('title', 'Edit / Restock Obat')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Edit & Restock - {{ $medicine->name }}</h3>
                <p class="text-xs text-slate-500">Kode Obat: <strong class="font-mono text-sky-600">{{ $medicine->code }}</strong></p>
            </div>
            <a href="{{ route('medicines.index') }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('medicines.update', $medicine) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Obat</label>
                    <input type="text" name="code" value="{{ old('code', $medicine->code) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Obat</label>
                    <input type="text" name="name" value="{{ old('name', $medicine->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori</label>
                    <select name="category" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="Obat Bebas" {{ old('category', $medicine->category) === 'Obat Bebas' ? 'selected' : '' }}>Obat Bebas</option>
                        <option value="Obat Keras" {{ old('category', $medicine->category) === 'Obat Keras' ? 'selected' : '' }}>Obat Keras (Resep)</option>
                        <option value="Antibiotik" {{ old('category', $medicine->category) === 'Antibiotik' ? 'selected' : '' }}>Antibiotik</option>
                        <option value="Vitamin & Suplemen" {{ old('category', $medicine->category) === 'Vitamin & Suplemen' ? 'selected' : '' }}>Vitamin & Suplemen</option>
                        <option value="Alat Kesehatan" {{ old('category', $medicine->category) === 'Alat Kesehatan' ? 'selected' : '' }}>Alat Kesehatan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Satuan</label>
                    <input type="text" name="unit" value="{{ old('unit', $medicine->unit) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Beli Modal (Rp)</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', $medicine->purchase_price) }}" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Harga Jual Pasien (Rp)</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price', $medicine->selling_price) }}" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jumlah Stok Saat Ini</label>
                    <input type="number" name="stock" value="{{ old('stock', $medicine->stock) }}" required min="0" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-sky-700 focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Batas Minimum Stok</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', $medicine->min_stock) }}" required min="1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Supplier Farmasi</label>
                    <select name="supplier_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Tanpa Supplier --</option>
                        @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ old('supplier_id', $medicine->supplier_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Kadaluarsa</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $medicine->expiry_date ? $medicine->expiry_date->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="if(confirm('Hapus obat ini dari inventaris?')) document.getElementById('delete-med-form').submit()" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-all">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Obat
                </button>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('medicines.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all">
                        UPDATE OBAT
                    </button>
                </div>
            </div>

        </form>

        <form id="delete-med-form" action="{{ route('medicines.destroy', $medicine) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

</div>
@endsection
