@extends('layouts.app')

@section('title', 'Laporan Kunjungan Pasien')

@section('content')
<div class="space-y-6">

    <!-- Filter & PDF Export Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('reports.kunjungan') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
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

        <a href="{{ route('reports.export-pdf') }}?type=kunjungan&start_date={{ $startDate }}&end_date={{ $endDate }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-600/20 flex items-center space-x-2 transition-all">
            <i class="fa-solid fa-file-pdf"></i>
            <span>Export Laporan PDF</span>
        </a>
    </div>

    <!-- Summary KPI Card -->
    <div class="bg-sky-900 text-white p-6 rounded-3xl shadow-lg flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-sky-300 uppercase tracking-widest">Total Kunjungan Pasien Periode Ini</span>
            <div class="text-3xl font-black mt-1">{{ number_format($totalVisits) }} Kunjungan</div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-sky-800 text-sky-300 flex items-center justify-center text-2xl font-bold">
            <i class="fa-solid fa-hospital-user"></i>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">Tgl Kunjungan</th>
                        <th class="py-3 px-4">No. Antrian / Reg</th>
                        <th class="py-3 px-4">Nama Pasien & RM</th>
                        <th class="py-3 px-4">Poli & Dokter</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($registrations as $r)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($r->registration_date)->isoFormat('D MMM YYYY') }}
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-600">
                            #{{ str_pad($r->queue_number, 2, '0', STR_PAD_LEFT) }} ({{ $r->registration_number }})
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $r->patient->name ?? '-' }}
                            <div class="text-[10px] text-slate-400 font-normal">RM: {{ $r->patient->mr_number ?? '-' }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <strong class="text-slate-800">{{ $r->clinic->name ?? '-' }}</strong>
                            <div class="text-[10px] text-slate-500">{{ $r->doctor->name ?? '-' }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-sky-100 text-sky-800">
                                {{ $r->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            Tidak ada data kunjungan pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $registrations->links() }}
        </div>
    </div>

</div>
@endsection
