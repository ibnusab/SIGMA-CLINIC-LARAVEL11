@extends('layouts.app')

@section('title', 'Inventaris Obat & Stok Farmasi')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('medicines.index') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Obat, Kode, atau Kategori..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>
            
            <select name="category" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Kategori --</option>
                <option value="Obat Bebas" {{ request('category') == 'Obat Bebas' ? 'selected' : '' }}>Obat Bebas</option>
                <option value="Obat Keras" {{ request('category') == 'Obat Keras' ? 'selected' : '' }}>Obat Keras (Resep)</option>
                <option value="Antibiotik" {{ request('category') == 'Antibiotik' ? 'selected' : '' }}>Antibiotik</option>
                <option value="Vitamin & Suplemen" {{ request('category') == 'Vitamin & Suplemen' ? 'selected' : '' }}>Vitamin & Suplemen</option>
                <option value="Alat Kesehatan" {{ request('category') == 'Alat Kesehatan' ? 'selected' : '' }}>Alat Kesehatan</option>
            </select>

            <select name="filter" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Stok --</option>
                <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>Stok Menipis / Restock</option>
            </select>
        </form>

        <a href="{{ route('medicines.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center justify-center space-x-2 transition-all">
            <i class="fa-solid fa-pills"></i>
            <span>Tambah Obat Baru</span>
        </a>
    </div>

    <!-- Medicines Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Daftar Stok Obat Apotek</h3>
            <span class="text-xs text-slate-500 font-medium">Total: {{ $medicines->total() }} Jenis Obat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">Kode & Nama Obat</th>
                        <th class="py-3 px-4">Kategori & Bentuk</th>
                        <th class="py-3 px-4">Sisa Stok</th>
                        <th class="py-3 px-4">Harga Beli / Jual</th>
                        <th class="py-3 px-4">Supplier & Kadaluarsa</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($medicines as $m)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $m->name }}
                            <div class="text-[10px] text-sky-600 font-mono">{{ $m->code }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800">
                                {{ $m->category }}
                            </span>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $m->unit }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($m->stock <= $m->min_stock)
                            <span class="px-2.5 py-1 bg-amber-100 text-amber-900 font-black rounded-lg text-xs animate-pulse">
                                {{ $m->stock }} {{ $m->unit }} (Low!)
                            </span>
                            @else
                            <span class="font-extrabold text-slate-800 text-sm">
                                {{ $m->stock }} {{ $m->unit }}
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="text-emerald-700 font-extrabold">Jual: Rp {{ number_format($m->selling_price, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-slate-400">Beli: Rp {{ number_format($m->purchase_price, 0, ',', '.') }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ $m->supplier->name ?? 'Distributor Utama' }}
                            <div class="text-[10px] text-rose-600 font-semibold mt-0.5">
                                Exp: {{ $m->expiry_date ? $m->expiry_date->format('d/m/Y') : '-' }}
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="{{ route('medicines.edit', $m) }}" class="text-sky-600 hover:text-sky-800 font-bold">
                                Edit / Restock
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-pills text-3xl mb-2"></i>
                            <p class="text-xs font-semibold">Obat tidak ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $medicines->links() }}
        </div>
    </div>

</div>
@endsection
