@extends('layouts.app')

@section('title', 'Data Pasien (Rekam Medis)')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('patients.index') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Pasien, No. RM, NIK, atau HP..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>
            <select name="gender" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Gender --</option>
                <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition-all">
                Filter
            </button>
            @if(request()->hasAny(['search', 'gender']))
            <a href="{{ route('patients.index') }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-300 flex items-center justify-center">
                Reset
            </a>
            @endif
        </form>

        <a href="{{ route('patients.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center justify-center space-x-2 transition-all">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pasien Baru</span>
        </a>
    </div>

    <!-- Patients Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Daftar Pasien Terdaftar</h3>
            <span class="text-xs text-slate-500 font-medium">Total: {{ $patients->total() }} Pasien</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">No. RM</th>
                        <th class="py-3 px-4">Nama Pasien & NIK</th>
                        <th class="py-3 px-4">Gender & Umur</th>
                        <th class="py-3 px-4">Kontak & Alamat</th>
                        <th class="py-3 px-4">Gol. Darah & Alergi</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($patients as $p)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-black text-sky-600">
                            {{ $p->mr_number }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $p->name }}
                            <div class="text-[10px] text-slate-400 font-normal">NIK: {{ $p->nik }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $p->gender === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $p->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                            <div class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $p->age }} Tahun</div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            <i class="fa-solid fa-phone text-sky-500 mr-1"></i> {{ $p->phone }}
                            <div class="text-[10px] text-slate-400 truncate max-w-xs">{{ $p->address }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 bg-slate-100 font-bold text-slate-700 rounded text-[10px]">Gol: {{ $p->blood_type ?? '-' }}</span>
                            @if($p->allergies && $p->allergies !== 'Tidak ada')
                            <div class="text-[10px] text-rose-600 font-bold mt-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $p->allergies }}</div>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('registrations.create', ['patient_id' => $p->id]) }}" title="Daftar Kunjungan" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-calendar-plus text-xs"></i>
                                </a>
                                <a href="{{ route('patients.show', $p) }}" title="Lihat Rekam Medis" class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-600 hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('patients.card', $p) }}" target="_blank" title="Cetak Kartu" class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-id-card text-xs"></i>
                                </a>
                                <a href="{{ route('patients.edit', $p) }}" title="Edit Pasien" class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-users-slash text-3xl mb-2"></i>
                            <p class="text-xs font-semibold">Data pasien tidak ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $patients->links() }}
        </div>
    </div>

</div>
@endsection
