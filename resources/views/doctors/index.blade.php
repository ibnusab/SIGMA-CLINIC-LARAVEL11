@extends('layouts.app')

@section('title', 'Data Dokter & Spesialis')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('doctors.index') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Dokter, SIP, atau Spesialisasi..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>
            <select name="clinic_id" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Poli --</option>
                @foreach($clinics as $c)
                <option value="{{ $c->id }}" {{ request('clinic_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition-all">
                Filter
            </button>
        </form>

        <a href="{{ route('doctors.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center justify-center space-x-2 transition-all">
            <i class="fa-solid fa-user-doctor"></i>
            <span>Tambah Dokter Baru</span>
        </a>
    </div>

    <!-- Doctors Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($doctors as $d)
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:border-sky-300 transition-all">
            <div>
                <div class="flex items-start space-x-4 mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xl overflow-hidden flex-shrink-0 shadow-sm">
                        @if($d->photo)
                        <img src="{{ asset('storage/' . $d->photo) }}" class="w-full h-full object-cover" alt="{{ $d->name }}">
                        @else
                        <i class="fa-solid fa-user-doctor"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="px-2.5 py-0.5 rounded bg-sky-50 text-sky-700 font-extrabold text-[10px] uppercase">
                            {{ $d->clinic->name ?? 'Poli Umum' }}
                        </span>
                        <h3 class="text-sm font-bold text-slate-900 truncate mt-1">{{ $d->name }}</h3>
                        <p class="text-xs text-slate-500 font-medium">{{ $d->specialization }}</p>
                    </div>
                </div>

                <div class="space-y-2 py-3 border-y border-slate-100 text-xs text-slate-600">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">NIP / SIP:</span>
                        <strong class="font-mono text-slate-800">{{ $d->nip_sip }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Telepon / HP:</span>
                        <strong class="text-slate-800">{{ $d->phone }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Tarif Konsultasi:</span>
                        <strong class="text-emerald-700 font-black">Rp {{ number_format($d->consultation_fee, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Akun Email Login:</span>
                        <strong class="text-slate-700 truncate max-w-[150px]">{{ $d->user->email ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 flex items-center justify-end space-x-2">
                <a href="{{ route('doctors.edit', $d) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    <i class="fa-solid fa-pen mr-1"></i> Edit Dokter
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200/80">
            <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
            <p class="text-xs font-semibold">Data dokter tidak ditemukan.</p>
        </div>
        @endforelse
    </div>

    <div class="p-4 bg-white rounded-2xl border border-slate-200/80">
        {{ $doctors->links() }}
    </div>

</div>
@endsection
