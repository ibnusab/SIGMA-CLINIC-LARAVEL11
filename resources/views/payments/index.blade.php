@extends('layouts.app')

@section('title', 'Kasir & Pembayaran Pasien')

@section('content')
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('payments.index') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <select name="status" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Status Tagihan --</option>
                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas (Unpaid)</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Lunas (Paid)</option>
            </select>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Mata Transaksi Kasir Pembayaran</h3>
            <span class="text-xs text-slate-500 font-medium">Total: {{ $payments->total() }} Tagihan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">No. Transaksi</th>
                        <th class="py-3 px-4">Pasien & No. RM</th>
                        <th class="py-3 px-4">Dokter & Poli</th>
                        <th class="py-3 px-4">Total Billing Tagihan</th>
                        <th class="py-3 px-4">Status Pembayaran</th>
                        <th class="py-3 px-4 text-center">Aksi / Kasir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($payments as $pay)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            <span class="font-mono text-xs block text-sky-700 font-black">{{ $pay->payment_number }}</span>
                            <div class="text-[10px] text-slate-400 font-normal">{{ \Carbon\Carbon::parse($pay->created_at)->isoFormat('D MMM YYYY, HH:mm') }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $pay->patient->name ?? '-' }}
                            <div class="text-[10px] text-slate-400 font-normal">RM: {{ $pay->patient->mr_number ?? '-' }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">
                            {{ $pay->registration->doctor->name ?? '-' }}
                            <div class="text-[10px] text-sky-600 font-normal">{{ $pay->registration->clinic->name ?? 'Poli' }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-black text-emerald-700 text-sm">
                            Rp {{ number_format($pay->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($pay->status === 'paid')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-800">
                                LUNAS
                            </span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-900 animate-pulse">
                                UNPAID / BELUM BAYAR
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('payments.show', $pay) }}" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-bold transition-all shadow">
                                    {{ $pay->status === 'paid' ? 'Detail Billing' : 'Proses Kasir' }}
                                </a>
                                @if($pay->status === 'paid')
                                <a href="{{ route('payments.invoice', $pay) }}" target="_blank" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold transition-all">
                                    <i class="fa-solid fa-receipt"></i> Kwitansi
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-receipt text-3xl mb-2"></i>
                            <p class="text-xs font-semibold">Belum ada tagihan transaksi kasir.</p>
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
