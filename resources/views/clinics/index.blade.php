@extends('layouts.app')

@section('title', 'Master Data Poli Klinik')

@section('content')
<div class="space-y-6">

    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Daftar Poli Spesialis</h3>
            <p class="text-xs text-slate-500">Kelola poli klinik tempat pelayanan pemeriksaan</p>
        </div>

        <a href="{{ route('clinics.create') }}" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center space-x-2 transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Poli Baru</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($clinics as $c)
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-sky-300 transition-all">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2.5 py-0.5 rounded bg-sky-100 text-sky-800 font-black text-xs font-mono">
                        {{ $c->code }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $c->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                        {{ $c->is_active ? 'Aktif' : 'Non-aktif' }}
                    </span>
                </div>

                <h3 class="text-base font-bold text-slate-900 mb-1">{{ $c->name }}</h3>
                <p class="text-xs text-slate-500 mb-3"><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $c->location ?? 'Lantai 1' }}</p>
                <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 font-medium leading-relaxed">{{ $c->description ?? 'Tidak ada deskripsi' }}</p>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Dokter: <strong>{{ $c->doctors_count }}</strong></span>
                <a href="{{ route('clinics.edit', $c) }}" class="text-sky-600 font-bold hover:underline">
                    Edit Poli &rarr;
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200/80">
            <p class="text-xs font-semibold">Belum ada poli klinik terdaftar.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
