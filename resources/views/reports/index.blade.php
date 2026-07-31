@extends('layouts.app')

@section('title', 'Pusat Laporan & Rekapitulasi Klinik')

@section('content')
<div class="space-y-6">

    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <h3 class="text-base font-extrabold text-slate-900 mb-1">Pusat Laporan & Analytics SIGMA CLINIC</h3>
        <p class="text-xs text-slate-500">Pilih jenis laporan untuk melihat statistik detail dan cetak dokumen PDF resmi.</p>
    </div>

    <!-- 3 Major Report Modules Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Laporan Kunjungan Pasien -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-sky-300 transition-all">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center text-xl font-bold mb-4">
                    <i class="fa-solid fa-users-between-lines"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1">Laporan Kunjungan Pasien</h4>
                <p class="text-xs text-slate-500 mb-4">Rekapitulasi pendaftaran antrian pasien per periode tanggal, poli, dan status kunjungan.</p>
            </div>
            <a href="{{ route('reports.kunjungan') }}" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold text-center block transition-all shadow-md shadow-sky-600/20">
                Buka Laporan Kunjungan &rarr;
            </a>
        </div>

        <!-- Laporan Pendapatan Keuangan -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-emerald-300 transition-all">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-bold mb-4">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1">Laporan Pendapatan Kasir</h4>
                <p class="text-xs text-slate-500 mb-4">Rekapitulasi total omzet pemasukan dari jasa dokter, tindakan, dan penjualan obat apotek.</p>
            </div>
            <a href="{{ route('reports.pendapatan') }}" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold text-center block transition-all shadow-md shadow-emerald-600/20">
                Buka Laporan Keuangan &rarr;
            </a>
        </div>

        <!-- Laporan Stok Obat & Farmasi -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-purple-300 transition-all">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl font-bold mb-4">
                    <i class="fa-solid fa-boxes-packing"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900 mb-1">Laporan Inventaris Stok Obat</h4>
                <p class="text-xs text-slate-500 mb-4">Laporan sisa persediaan obat, obat menipis, expired date, serta nilai total aset farmasi.</p>
            </div>
            <a href="{{ route('reports.obat') }}" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold text-center block transition-all shadow-md shadow-purple-600/20">
                Buka Laporan Inventaris &rarr;
            </a>
        </div>

    </div>

</div>
@endsection
