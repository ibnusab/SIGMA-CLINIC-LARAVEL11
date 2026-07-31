@extends('layouts.app')

@section('title', 'Detail Resep ' . $prescription->prescription_number)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-mono font-black text-purple-700 bg-purple-100 px-3 py-1 rounded-lg">
                    {{ $prescription->prescription_number }}
                </span>
                <h3 class="text-lg font-extrabold text-slate-900 mt-2">Detail Resep Obat Pasien</h3>
            </div>
            
            <a href="{{ route('prescriptions.index') }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <div>Pasien: <strong class="text-slate-900 block font-bold text-sm">{{ $prescription->patient->name ?? '-' }}</strong> (RM: {{ $prescription->patient->mr_number ?? '-' }})</div>
            <div>Dokter Peresep: <strong class="text-slate-900 block font-bold text-sm">{{ $prescription->doctor->name ?? '-' }}</strong></div>
            <div>Status Resep: <strong class="uppercase font-bold text-purple-700">{{ $prescription->status }}</strong></div>
            <div>Total Biaya: <strong class="text-emerald-700 font-extrabold text-sm">Rp {{ number_format($prescription->total_amount ?? $prescription->total_price ?? 0, 0, ',', '.') }}</strong></div>
        </div>

        <div>
            <h4 class="font-extrabold text-slate-900 uppercase text-xs tracking-wider mb-3">Item Obat Dalam Resep</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100 font-bold uppercase text-[10px] text-slate-500">
                            <th class="p-3">Nama Obat</th>
                            <th class="p-3">Aturan Pakai</th>
                            <th class="p-3">Jumlah</th>
                            <th class="p-3 text-right">Harga Satuan</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($prescription->items as $item)
                        <tr>
                            <td class="p-3 font-bold text-slate-800">{{ $item->medicine->name ?? '-' }}</td>
                            <td class="p-3 text-purple-700 font-semibold">{{ $item->instruction }}</td>
                            <td class="p-3 font-bold">{{ $item->quantity }} {{ $item->medicine->unit ?? 'Pcs' }}</td>
                            <td class="p-3 text-right">Rp {{ number_format($item->price ?? $item->unit_price ?? 0, 0, ',', '.') }}</td>
                            <td class="p-3 text-right font-extrabold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Update Status Action Button for Pharmacist -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
            <div class="text-xs text-slate-500 font-medium">
                Pencet tombol di bawah untuk mengubah status penyiapan obat di Apotek
            </div>

            <form action="{{ route('prescriptions.update-status', $prescription) }}" method="POST" class="flex space-x-2">
                @csrf
                @method('PATCH')
                @if($prescription->status === 'pending')
                <button type="submit" name="status" value="processed" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                    Tandai Selesai Diproses Apotek
                </button>
                @elseif($prescription->status === 'processed')
                <button type="submit" name="status" value="taken" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                    Tandai Obat Sudah Diambil Pasien
                </button>
                @else
                <span class="px-4 py-2 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-xl">
                    <i class="fa-solid fa-check-circle mr-1"></i> Resep Telah Selesai
                </span>
                @endif
            </form>
        </div>

    </div>

</div>
@endsection
