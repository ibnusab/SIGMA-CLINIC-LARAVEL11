@extends('layouts.app')

@section('title', 'Kelola Pengguna & Akun System')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen Pengguna & Hak Akses Role</h1>
            <p class="text-xs text-slate-500">Kelola seluruh akun login, ganti password, dan relasi peran (Role) sistem klinik</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-md shadow-sky-600/20 transition-all">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengguna Baru</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6 md:col-span-7">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengguna, email, atau nomor HP..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-sky-500">
                </div>
            </div>
            <div class="sm:col-span-4 md:col-span-3">
                <select name="role" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-sky-500">
                    <option value="">-- Semua Peran (Role) --</option>
                    @foreach($rolesList as $key => $label)
                        <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2 md:col-span-2 flex space-x-2">
                <button type="submit" class="w-full py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('users.index') }}" class="p-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors" title="Reset">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Data Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Daftar Akun Pengguna Terdaftar</h3>
            <span class="text-xs font-medium text-slate-500">Total: <strong>{{ $users->total() }}</strong> Akun</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-4">Nama Pengguna</th>
                        <th class="py-3 px-4">Email Login</th>
                        <th class="py-3 px-4">Peran (Role)</th>
                        <th class="py-3 px-4">Relasi Spesialis/Poli</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi & Password</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-extrabold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-extrabold text-slate-900">{{ $u->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $u->phone ?? 'Tidak ada HP' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 font-mono font-semibold text-slate-700">
                            {{ $u->email }}
                        </td>
                        <td class="py-3.5 px-4">
                            @php
                                $r = strtolower($u->role);
                                $badgeClass = match($r) {
                                    'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'dokter' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'apoteker' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'resepsionis' => 'bg-sky-100 text-sky-800 border-sky-200',
                                    default => 'bg-slate-100 text-slate-800 border-slate-200',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase border {{ $badgeClass }}">
                                {{ $rolesList[$r] ?? ucfirst($r) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($u->doctor)
                                <div class="font-bold text-sky-900">{{ $u->doctor->clinic->name ?? 'Poli' }}</div>
                                <div class="text-[10px] text-slate-500 font-semibold">{{ $u->doctor->specialization }}</div>
                            @else
                                <span class="text-slate-400 italic text-[11px]">- (Staf Non-Dokter)</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            @if($u->is_active)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-rose-100 text-rose-800">
                                    Non-Aktif
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center space-x-1.5">
                                <a href="{{ route('users.show', $u) }}" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs transition-colors" title="Detail Profile">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $u) }}" class="p-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 rounded-lg text-xs font-bold transition-colors flex items-center space-x-1" title="Edit Data & Password">
                                    <i class="fa-solid fa-key"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                                @if(Auth::id() !== $u->id)
                                <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ $u->name }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition-colors" title="Hapus Akun">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                            Tidak ada data pengguna ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
