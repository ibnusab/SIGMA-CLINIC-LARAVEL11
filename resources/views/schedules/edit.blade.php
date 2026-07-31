@extends('layouts.app')

@section('title', 'Edit Jadwal Dokter')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Edit Jadwal Dokter</h3>
                <p class="text-xs text-slate-500">Ubah jam operasional atau kuota praktek</p>
            </div>
            <a href="{{ route('schedules.index') }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('schedules.update', $schedule) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Dokter</label>
                <select name="doctor_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ old('doctor_id', $schedule->doctor_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Hari Praktek</label>
                <select name="day" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                    <option value="{{ $h }}" {{ old('day', $schedule->day) === $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jam Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time', substr($schedule->start_time, 0, 5)) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jam Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time', substr($schedule->end_time, 0, 5)) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kuota Pasien</label>
                <input type="number" name="quota" value="{{ old('quota', $schedule->quota) }}" required min="1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Jadwal</label>
                <select name="is_active" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    <option value="1" {{ old('is_active', $schedule->is_active) ? 'selected' : '' }}>Aktif Operasional</option>
                    <option value="0" {{ !old('is_active', $schedule->is_active) ? 'selected' : '' }}>Libur / Non-aktif</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="if(confirm('Hapus jadwal ini?')) document.getElementById('delete-sch-form').submit()" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-all">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Jadwal
                </button>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all">
                        UPDATE JADWAL
                    </button>
                </div>
            </div>

        </form>

        <form id="delete-sch-form" action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

</div>
@endsection
