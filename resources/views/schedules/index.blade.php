@extends('layouts.app')

@section('title', 'Jadwal Praktek Dokter')

@section('content')
<div class="space-y-6">

    <!-- Filter & Action Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('schedules.index') }}" method="GET" class="w-full md:w-auto flex-1 flex flex-col sm:flex-row gap-3">
            <select name="doctor_id" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Dokter --</option>
                @foreach($doctors as $d)
                <option value="{{ $d->id }}" {{ request('doctor_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>

            <select name="day" onchange="this.form.submit()" class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none">
                <option value="">-- Semua Hari --</option>
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                <option value="{{ $h }}" {{ request('day') == $h ? 'selected' : '' }}>{{ $h }}</option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('schedules.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 flex items-center justify-center space-x-2 transition-all">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Tambah Jadwal Dokter</span>
        </a>
    </div>

    <!-- Schedules Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-2">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Mata Jadwal Praktek Dokter & Status Kuota Real-time</h3>
                <p class="text-[11px] text-slate-500">Kuota otomatis berkurang saat pasien mendaftar dan direset secara dinamis setiap hari.</p>
            </div>
            <div class="px-3 py-1 bg-sky-50 border border-sky-200 text-sky-800 rounded-xl text-xs font-bold flex items-center space-x-2">
                <i class="fa-solid fa-clock text-sky-600"></i>
                <span>Hari Ini: <strong>{{ $todayName ?? 'Hari Ini' }} ({{ \Carbon\Carbon::parse($todayDate ?? date('Y-m-d'))->isoFormat('D MMMM YYYY') }})</strong></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3 px-4">Hari Praktek</th>
                        <th class="py-3 px-4">Nama Dokter & Poli</th>
                        <th class="py-3 px-4">Jam Mulai - Selesai</th>
                        <th class="py-3 px-4">Kapasitas & Kuota Pasien</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($schedules as $s)
                    <tr class="hover:bg-slate-50/80 transition-colors {{ ($s->is_today ?? false) ? 'bg-sky-50/30' : '' }}">
                        <td class="py-3.5 px-4">
                            <span class="px-3 py-1 font-extrabold rounded-lg text-xs {{ ($s->is_today ?? false) ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-100 text-slate-800' }}">
                                {{ $s->day }}
                                @if($s->is_today ?? false)
                                <span class="text-[9px] uppercase font-black tracking-wider ml-1 bg-white/20 px-1 py-0.5 rounded">(Hari Ini)</span>
                                @endif
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $s->doctor->name ?? '-' }}
                            <div class="text-[10px] text-sky-600 font-semibold">{{ $s->doctor->clinic->name ?? 'Poli Umum' }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-extrabold text-slate-800 font-mono">
                            {{ substr($s->start_time, 0, 5) }} - {{ substr($s->end_time, 0, 5) }} WIB
                        </td>
                        <td class="py-3.5 px-4 space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-extrabold text-slate-800">{{ $s->quota }} Pasien / Hari</span>
                            </div>
                            <div class="flex items-center space-x-1.5 text-[11px]">
                                <span class="text-slate-500 font-medium">Terisi: <strong class="text-slate-800 font-bold">{{ $s->today_count ?? 0 }}</strong></span>
                                <span class="text-slate-300">•</span>
                                <span class="font-bold {{ ($s->today_remaining ?? $s->quota) <= 0 ? 'text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-200' : 'text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200' }}">
                                    @if(($s->today_remaining ?? $s->quota) <= 0)
                                        Sisa: PENUH (0)
                                    @else
                                        Sisa Kuota: {{ $s->today_remaining ?? $s->quota }} Pasien
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $s->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                {{ $s->is_active ? 'Aktif' : 'Libur' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="{{ route('schedules.edit', $s) }}" class="text-sky-600 hover:text-sky-800 font-bold">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-calendar-xmark text-3xl mb-2"></i>
                            <p class="text-xs font-semibold">Belum ada jadwal praktek terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $schedules->links() }}
        </div>
    </div>

</div>
@endsection
