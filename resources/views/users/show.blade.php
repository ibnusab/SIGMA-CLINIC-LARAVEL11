@extends('layouts.app')

@section('title', 'Detail Akun Pengguna')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-600 text-white font-extrabold flex items-center justify-center text-xl shadow-md shadow-sky-600/20">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-500">ID User: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }} • Terdaftar {{ $user->created_at ? $user->created_at->isoFormat('D MMMM YYYY') : '-' }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    <i class="fa-solid fa-key mr-1"></i> Edit & Password
                </a>
                <a href="{{ route('users.index') }}" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
            <div class="space-y-4">
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Email Login:</span>
                    <p class="font-mono font-bold text-slate-900 text-sm mt-0.5">{{ $user->email }}</p>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Nomor Telepon:</span>
                    <p class="font-semibold text-slate-800 mt-0.5">{{ $user->phone ?? 'Belum diisi' }}</p>
                </div>

                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Hak Akses (Role):</span>
                    <div class="mt-1">
                        @php
                            $r = strtolower($user->role);
                            $badgeClass = match($r) {
                                'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'dokter' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'apoteker' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'resepsionis' => 'bg-sky-100 text-sky-800 border-sky-200',
                                default => 'bg-slate-100 text-slate-800 border-slate-200',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-xl text-xs font-extrabold uppercase border inline-block {{ $badgeClass }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Status Akun:</span>
                    <p class="mt-0.5">
                        @if($user->is_active)
                            <span class="px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-emerald-100 text-emerald-800 inline-block">
                                <i class="fa-solid fa-circle-check mr-1"></i> Aktif (Bisa Login)
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-xl text-xs font-extrabold uppercase bg-rose-100 text-rose-800 inline-block">
                                <i class="fa-solid fa-circle-xmark mr-1"></i> Non-Aktif (Terblokir)
                            </span>
                        @endif
                    </p>
                </div>

                @if($user->doctor)
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <span class="text-sky-900 font-extrabold text-xs block">
                        <i class="fa-solid fa-hospital-user mr-1 text-sky-600"></i> Relasi Profil Dokter
                    </span>
                    <div class="text-slate-700">Poli: <strong class="text-slate-900">{{ $user->doctor->clinic->name ?? 'Poli Umum' }}</strong></div>
                    <div class="text-slate-700">Spesialisasi: <strong class="text-sky-700">{{ $user->doctor->specialization }}</strong></div>
                    <div class="text-slate-700">NIP/SIP: <span class="font-mono">{{ $user->doctor->nip_sip }}</span></div>
                    <div class="text-slate-700">Tarif Konsultasi: <strong class="text-emerald-700">Rp {{ number_format($user->doctor->consultation_fee, 0, ',', '.') }}</strong></div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
