@extends('layouts.app')

@section('title', 'Laporan Stok & Inventaris Obat')

@section('content')
<div class="space-y-6">

    <!-- Action Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Laporan Opnam Inventaris Obat Farmasi</h3>
            <p class="text-xs text-slate-500">Rekapitulasi total sisa obat, nilai aset modal, dan tanggal expired</p>
        </div>

        <a href="{{ route('reports.export-pdf') }}?type=obat" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-600/20 flex items-center space-x-2 transition-all">
            <i class="fa-solid fa-file-pdf"></i>
            <span>Export PDF Inventaris</span>
        </a>
    </div>

    <!-- Medicines Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">Kode & Nama Obat</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Sisa Stok</th>
                        <th class="py-3 px-4">Harga Beli Modal</th>
                        <th class="py-3 px-4">Total Aset Stock (Rp)</th>
                        <th class="py-3 px-4">Supplier & Expired</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @php $totalAssetValue = 0; @endphp
                    @forelse($medicines as $m)
                    @php $assetVal = $m->stock * $m->purchase_price; $totalAssetValue += $assetVal; @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $m->name }}
                            <div class="text-[10px] text-sky-600 font-mono">{{ $m->code }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-700">{{ $m->category }}</td>
                        <td class="py-3.5 px-4">
                            @if($m->stock <= $m->min_stock)
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-900 font-black rounded text-[10px]">
                                {{ $m->stock }} {{ $m->unit }} (Low!)
                            </span>
                            @else
                            <span class="font-extrabold text-slate-900">{{ $m->stock }} {{ $m->unit }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-slate-700">Rp {{ number_format($m->purchase_price, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-4 font-extrabold text-emerald-700">Rp {{ number_format($assetVal, 0, ',', '.') }}</td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ $m->supplier->name ?? '-' }}
                            <div class="text-[10px] text-rose-600 font-semibold mt-0.5">
                                Exp: {{ $m->expiry_date ? $m->expiry_date->format('d/m/Y') : '-' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            Belum ada obat terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-900 text-white font-extrabold text-xs">
                        <td colspan="4" class="py-3 px-4 text-right uppercase">ESTIMASI TOTAL VALUE ASET STOK:</td>
                        <td colspan="2" class="py-3 px-4 text-emerald-400 text-sm">Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection
