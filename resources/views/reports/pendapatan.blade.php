@extends('layouts.app')

@section('title', 'Laporan Pendapatan Keuangan')

@section('content')
<div class="space-y-6">

    <!-- Filter & PDF Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('reports.pendapatan') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-slate-500">Dari:</span>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-slate-500">Sampai:</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition-all">
                Tampilkan
            </button>
        </form>

        <a href="{{ route('reports.export-pdf') }}?type=pendapatan&start_date={{ $startDate }}&end_date={{ $endDate }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-600/20 flex items-center space-x-2 transition-all">
            <i class="fa-solid fa-file-pdf"></i>
            <span>Export Laporan PDF</span>
        </a>
    </div>

    <!-- Summary Revenue Card -->
    <div class="bg-emerald-950 text-white p-6 rounded-3xl shadow-lg flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Total OMZET LUNAS Periode Ini</span>
            <div class="text-3xl font-black mt-1 text-emerald-300">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-900 text-emerald-300 flex items-center justify-center text-2xl font-bold">
            <i class="fa-solid fa-sack-dollar"></i>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">Tgl Pembayaran</th>
                        <th class="py-3 px-4">No. Transaksi</th>
                        <th class="py-3 px-4">Nama Pasien</th>
                        <th class="py-3 px-4">Metode Bayar</th>
                        <th class="py-3 px-4 text-right">Total Transaksi (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($payments as $pay)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-semibold text-slate-700">
                            {{ \Carbon\Carbon::parse($pay->paid_at)->isoFormat('D MMM YYYY, HH:mm') }}
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-600">
                            {{ $pay->payment_number }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $pay->patient->name ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 uppercase font-bold text-slate-700">
                            {{ $pay->payment_method }}
                        </td>
                        <td class="py-3.5 px-4 text-right font-black text-emerald-700 text-sm">
                            Rp {{ number_format($pay->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            Tidak ada transaksi lunas pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $payments->links() }}
        </div>
    </div>

</div>
@endsection
