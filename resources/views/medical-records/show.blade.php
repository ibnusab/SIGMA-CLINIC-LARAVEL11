@extends('layouts.app')

@section('title', 'Detail Rekam Medis ' . $medicalRecord->record_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Action -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-sky-100 text-sky-800 font-extrabold text-xs rounded-lg font-mono">
                    {{ $medicalRecord->record_number }}
                </span>
                <span class="text-xs font-bold text-slate-500">
                    <i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($medicalRecord->examination_date)->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                </span>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900 mt-2">Rekam Hasil Pemeriksaan Dokter</h2>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('medical-records.pdf', $medicalRecord) }}" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow transition-all flex items-center space-x-2">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Cetak Lembar Rekam Medis PDF</span>
            </a>
            <a href="{{ route('medical-records.index') }}" class="px-3.5 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- Patient & Doctor Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Patient Info Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-3 text-xs">
            <h3 class="font-extrabold text-slate-900 uppercase tracking-wider text-xs border-b border-slate-100 pb-2">Identitas Pasien</h3>
            <div class="flex justify-between">
                <span class="text-slate-500">Nama Pasien:</span>
                <strong class="text-slate-900 font-bold">{{ $medicalRecord->patient->name ?? '-' }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">No. Rekam Medis (RM):</span>
                <strong class="text-sky-600 font-mono font-bold">{{ $medicalRecord->patient->mr_number ?? '-' }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Jenis Kelamin / Umur:</span>
                <span>{{ ($medicalRecord->patient->gender ?? 'L') === 'L' ? 'Laki-laki' : 'Perempuan' }} ({{ $medicalRecord->patient->age ?? '-' }} Tahun)</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Alergi Terdaftar:</span>
                <strong class="text-rose-600">{{ $medicalRecord->patient->allergies ?? 'Tidak ada' }}</strong>
            </div>
        </div>

        <!-- Doctor & Clinic Card -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-3 text-xs">
            <h3 class="font-extrabold text-slate-900 uppercase tracking-wider text-xs border-b border-slate-100 pb-2">Dokter Pemeriksa</h3>
            <div class="flex justify-between">
                <span class="text-slate-500">Dokter Penanggung Jawab:</span>
                <strong class="text-slate-900 font-bold">{{ $medicalRecord->doctor->name ?? '-' }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Poli Klinik:</span>
                <strong class="text-sky-600">{{ $medicalRecord->doctor->clinic->name ?? 'Poli Umum' }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">SIP Dokter:</span>
                <span class="font-mono">{{ $medicalRecord->doctor->nip_sip ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Examination Details -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        <div>
            <h3 class="font-extrabold text-slate-900 uppercase tracking-wider text-xs mb-3 flex items-center space-x-2">
                <i class="fa-solid fa-notes-medical text-sky-600"></i>
                <span>Hasil Anamnesa & Tanda-Tanda Vital</span>
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs text-slate-700 mb-4">
                <div>Tekanan Darah: <strong class="text-slate-900">{{ $medicalRecord->blood_pressure ?? '-' }} mmHg</strong></div>
                <div>Suhu Tubuh: <strong class="text-slate-900">{{ $medicalRecord->temperature ?? '-' }} &deg;C</strong></div>
                <div>Tinggi / Berat: <strong class="text-slate-900">{{ $medicalRecord->height ?? '-' }} cm / {{ $medicalRecord->weight ?? '-' }} kg</strong></div>
                <div>Indeks Massa Tubuh (IMT): <strong class="text-slate-900">{{ $medicalRecord->bmi ?? '-' }}</strong></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Keluhan / Anamnesa</span>
                    <p class="text-slate-800 font-medium leading-relaxed">{{ $medicalRecord->complaints }}</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Pemeriksaan Fisik</span>
                    <p class="text-slate-800 font-medium leading-relaxed">{{ $medicalRecord->physical_exam ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100">
            <span class="text-[10px] font-extrabold uppercase text-slate-400 block mb-1">Diagnosa Utama Dokter</span>
            <div class="text-base font-extrabold text-slate-900 bg-amber-50 border border-amber-200 p-4 rounded-2xl text-amber-900">
                {{ $medicalRecord->diagnosis }}
            </div>
        </div>

        <!-- Treatments & Prescriptions list -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100 text-xs">
            <div>
                <h4 class="font-extrabold text-slate-800 uppercase mb-2">Tindakan Medis Diberikan</h4>
                <ul class="space-y-1.5">
                    @forelse($medicalRecord->treatments as $t)
                    <li class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 flex justify-between items-center">
                        <span class="font-bold text-slate-800">{{ $t->name }}</span>
                        <span class="font-extrabold text-emerald-700">Rp {{ number_format($t->price, 0, ',', '.') }}</span>
                    </li>
                    @empty
                    <li class="text-slate-400 italic">Tidak ada tindakan khusus.</li>
                    @endforelse
                </ul>
            </div>

            <div>
                <h4 class="font-extrabold text-slate-800 uppercase mb-2">Resep Obat Apotek</h4>
                <ul class="space-y-1.5">
                    @forelse($medicalRecord->prescriptions as $p)
                        @foreach($p->items as $item)
                        <li class="p-2.5 rounded-xl bg-purple-50/80 border border-purple-100 flex justify-between items-center">
                            <div>
                                <span class="font-bold text-purple-950 block">{{ $item->medicine->name ?? '-' }}</span>
                                <span class="text-[10px] text-purple-700">{{ $item->instruction }}</span>
                            </div>
                            <span class="font-extrabold text-purple-900 bg-white px-2 py-0.5 rounded border border-purple-200">
                                {{ $item->quantity }} {{ $item->medicine->unit ?? 'Pcs' }}
                            </span>
                        </li>
                        @endforeach
                    @empty
                    <li class="text-slate-400 italic">Tidak ada resep obat.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
