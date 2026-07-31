@extends('layouts.app')

@section('title', 'Rekam Medis Pasien - ' . $patient->name)

@section('content')
<div class="space-y-6">

    <!-- Patient Header Summary Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 to-blue-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-sky-500/20">
                {{ strtoupper(substr($patient->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center space-x-3">
                    <h2 class="text-xl font-extrabold text-slate-900">{{ $patient->name }}</h2>
                    <span class="px-3 py-0.5 rounded-full bg-sky-100 text-sky-800 font-extrabold text-xs">
                        {{ $patient->mr_number }}
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 mt-1">
                    <span><i class="fa-solid fa-id-card text-slate-400 mr-1"></i> NIK: {{ $patient->nik }}</span>
                    <span><i class="fa-solid fa-venus-mars text-slate-400 mr-1"></i> {{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }} ({{ $patient->age }} Th)</span>
                    <span><i class="fa-solid fa-phone text-slate-400 mr-1"></i> {{ $patient->phone }}</span>
                    <span><i class="fa-solid fa-droplet text-slate-400 mr-1"></i> Gol: {{ $patient->blood_type ?? '-' }}</span>
                </div>
                <div class="text-xs text-slate-500 mt-1">
                    <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i> {{ $patient->address }}
                </div>
                @if($patient->allergies && $patient->allergies !== 'Tidak ada')
                <div class="mt-2 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg inline-block border border-rose-200">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Alergi: {{ $patient->allergies }}
                </div>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('registrations.create', ['patient_id' => $patient->id]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition-all flex items-center space-x-1">
                <i class="fa-solid fa-calendar-plus mr-1"></i>
                <span>Daftar Kunjungan</span>
            </a>
            <a href="{{ route('patients.card', $patient) }}" target="_blank" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow transition-all flex items-center space-x-1">
                <i class="fa-solid fa-id-card mr-1"></i>
                <span>Cetak Kartu</span>
            </a>
            <a href="{{ route('patients.edit', $patient) }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-all">
                <i class="fa-solid fa-pen mr-1"></i>
                <span>Edit Pasien</span>
            </a>
        </div>
    </div>

    <!-- Medical History Timeline Section -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Riwayat Rekam Medis Pasien</h3>
                <p class="text-xs text-slate-500">Daftar riwayat pemeriksaan, diagnosa, dan resep obat yang diterima pasien</p>
            </div>
            <span class="text-xs font-bold bg-slate-100 text-slate-700 px-3 py-1 rounded-lg">
                Total : {{ count($patient->medicalRecords) }} Riwayat
            </span>
        </div>

        @if(count($patient->medicalRecords) > 0)
        <div class="space-y-6">
            @foreach($patient->medicalRecords as $mr)
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-sky-300 transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 mb-3 border-b border-slate-200/60">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-black text-sky-700 bg-sky-100 px-2.5 py-1 rounded-lg">
                            {{ $mr->record_number }}
                        </span>
                        <span class="text-xs font-bold text-slate-800">
                            <i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($mr->examination_date)->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                        </span>
                    </div>
                    <div class="text-xs font-bold text-slate-600">
                        Dokter Pemeriksa: <span class="text-slate-900 font-black">{{ $mr->doctor->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 text-xs">
                    <!-- Keluhan -->
                    <div class="bg-white p-3 rounded-xl border border-slate-200/60">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Keluhan Pasien</span>
                        <p class="text-slate-800 font-medium">{{ $mr->complaints }}</p>
                    </div>

                    <!-- Vital Signs -->
                    <div class="bg-white p-3 rounded-xl border border-slate-200/60">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Tanda-Tanda Vital</span>
                        <div class="space-y-0.5 text-slate-700">
                            <div>Tekanan Darah: <strong>{{ $mr->blood_pressure ?? '-' }} mmHg</strong></div>
                            <div>Suhu: <strong>{{ $mr->temperature ?? '-' }} &deg;C</strong> | Tinggi: <strong>{{ $mr->height ?? '-' }} cm</strong></div>
                            <div>Berat: <strong>{{ $mr->weight ?? '-' }} kg</strong> | IMT: <strong>{{ $mr->bmi ?? '-' }}</strong></div>
                        </div>
                    </div>

                    <!-- Diagnosa -->
                    <div class="bg-white p-3 rounded-xl border border-slate-200/60">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Diagnosa Dokter</span>
                        <p class="text-slate-900 font-bold">{{ $mr->diagnosis }}</p>
                        @if($mr->doctor_notes)
                        <p class="text-[11px] text-slate-500 italic mt-1">Catatan: {{ $mr->doctor_notes }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="text-xs text-slate-500">
                        @if(count($mr->prescriptions) > 0)
                        <span class="text-emerald-700 font-bold"><i class="fa-solid fa-check-circle mr-1"></i> Resep obat tersedia</span>
                        @else
                        <span class="text-slate-400">Tidak ada resep obat</span>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('medical-records.pdf', $mr) }}" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition-all shadow">
                            <i class="fa-solid fa-file-pdf mr-1"></i> Cetak PDF
                        </a>
                        <a href="{{ route('medical-records.show', $mr) }}" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-bold transition-all shadow">
                            Detail Medis
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-12 text-center text-slate-400">
            <i class="fa-solid fa-folder-open text-3xl mb-2"></i>
            <p class="text-xs font-semibold">Belum ada riwayat rekam medis untuk pasien ini.</p>
        </div>
        @endif
    </div>

</div>
@endsection
