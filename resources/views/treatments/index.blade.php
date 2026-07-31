@extends('layouts.app')

@section('title', 'Tarif Tindakan Medis')

@section('content')
<div class="space-y-6">

    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Daftar Tarif & Tindakan Klinik</h3>
            <p class="text-xs text-slate-500">Kelola daftar tindakan medis dan harga layanan</p>
        </div>

        <a href="{{ route('treatments.create') }}" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center space-x-2 transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Tindakan Baru</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Nama Tindakan</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Tarif Layanan</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($treatments as $t)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-600">{{ $t->code }}</td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">{{ $t->name }}</td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800">
                                {{ $t->category }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-extrabold text-emerald-700 text-sm">
                            Rp {{ number_format($t->price, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-500">{{ $t->description ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="{{ route('treatments.edit', $t) }}" class="text-sky-600 hover:text-sky-800 font-bold">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <p class="text-xs font-semibold">Belum ada data tindakan medis.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $treatments->links() }}
        </div>
    </div>

</div>
@endsection
