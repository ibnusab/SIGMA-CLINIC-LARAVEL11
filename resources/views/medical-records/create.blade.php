@extends('layouts.app')

@section('title', 'Input Pemeriksaan Medis Pasien')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Hasil Pemeriksaan & Diagnosa Dokter</h3>
                <p class="text-xs text-slate-500">Rekam fisik, diagnosa, tindakan, serta resep obat pasien</p>
            </div>
            <a href="{{ route('medical-records.index') }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Batal
            </a>
        </div>

        <form action="{{ route('medical-records.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Select Antrian Registration -->
            <div class="bg-sky-50/60 p-4 rounded-2xl border border-sky-100">
                <label class="block text-xs font-bold text-sky-900 uppercase mb-1">Pilih Pasien Dalam Antrian <span class="text-rose-500">*</span></label>
                <select name="registration_id" required class="w-full px-4 py-2.5 bg-white border border-sky-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    <option value="">-- Pilih Antrian Kunjungan Pasien --</option>
                    @foreach($registrations as $reg)
                    <option value="{{ $reg->id }}" {{ (old('registration_id') == $reg->id || $selectedRegistrationId == $reg->id) ? 'selected' : '' }}>
                        Antrian #{{ str_pad($reg->queue_number, 2, '0', STR_PAD_LEFT) }} - [{{ $reg->patient->mr_number ?? '-' }}] {{ $reg->patient->name ?? '-' }} (Poli: {{ $reg->clinic->name ?? '-' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Vital Signs Section -->
            <div>
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-3 flex items-center space-x-2">
                    <i class="fa-solid fa-heart-pulse text-rose-500"></i>
                    <span>Tanda-Tanda Vital & Pemeriksaan Fisik</span>
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Tekanan Darah (mmHg)</label>
                        <input type="text" name="blood_pressure" value="{{ old('blood_pressure', '120/80') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="120/80">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Suhu Tubuh (&deg;C)</label>
                        <input type="number" step="0.1" name="temperature" value="{{ old('temperature', 36.5) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Tinggi Badan (cm)</label>
                        <input type="number" name="height" value="{{ old('height', 165) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Berat Badan (kg)</label>
                        <input type="number" step="0.5" name="weight" value="{{ old('weight', 60) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                    </div>
                </div>
            </div>

            <!-- Complaints & Physical Exam -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Anamnesa / Keluhan Pasien <span class="text-rose-500">*</span></label>
                    <textarea name="complaints" rows="3" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Keluhan utama dan riwayat penyakit saat ini...">{{ old('complaints') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pemeriksaan Fisik / Objektif</label>
                    <textarea name="physical_exam" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Kondisi umum, mata, tenggorokan, thorax, abdomen...">{{ old('physical_exam') }}</textarea>
                </div>
            </div>

            <!-- Diagnosis Section -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Diagnosa Medis Utama <span class="text-rose-500">*</span></label>
                <input type="text" name="diagnosis" value="{{ old('diagnosis') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Contoh: Pharyngitis Akut (ICD-10 J02) / Hipertensi Primer">
            </div>

            <!-- Treatments / Tindakan Selection -->
            <div>
                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">Tindakan Medis Ditambahkan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-40 overflow-y-auto p-3 bg-slate-50 rounded-2xl border border-slate-200 text-xs">
                    @foreach($treatments as $t)
                    <label class="flex items-center space-x-2 p-2 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-sky-400">
                        <input type="checkbox" name="treatment_ids[]" value="{{ $t->id }}" class="rounded text-sky-600 focus:ring-sky-500">
                        <span class="font-bold text-slate-800">{{ $t->name }}</span>
                        <span class="ml-auto text-emerald-700 font-extrabold">+Rp {{ number_format($t->price, 0, ',', '.') }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Recipe / Medicine Items Section -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center space-x-2">
                        <i class="fa-solid fa-pills text-purple-600"></i>
                        <span>Resep Obat Pasien</span>
                    </h4>
                    <button type="button" onclick="addMedicineRow()" class="px-3 py-1 bg-purple-100 text-purple-800 hover:bg-purple-200 rounded-lg text-xs font-bold">
                        + Tambah Item Obat
                    </button>
                </div>

                <div id="medicine-rows" class="space-y-3">
                    <div class="grid grid-cols-12 gap-2 bg-slate-50 p-3 rounded-2xl border border-slate-200 text-xs">
                        <div class="col-span-5">
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Obat</label>
                            <select name="medicines[0][id]" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                                <option value="">-- Pilih Obat --</option>
                                @foreach($medicines as $m)
                                <option value="{{ $m->id }}">{{ $m->name }} (Stok: {{ $m->stock }} {{ $m->unit }}) - Rp {{ number_format($m->selling_price, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Jumlah</label>
                            <input type="number" min="1" name="medicines[0][quantity]" value="1" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                        </div>
                        <div class="col-span-4">
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Dosis / Aturan Pakai</label>
                            <input type="text" name="medicines[0][instruction]" value="3 x 1 Tablet sesudah makan" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                        </div>
                        <div class="col-span-1 flex items-end justify-center pb-1">
                            <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes & Follow up -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Tambahan Dokter / Anjuran</label>
                    <input type="text" name="doctor_notes" value="{{ old('doctor_notes') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500" placeholder="Istirahat cukup, hindari makanan pedas">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Kontrol Ulang (Opsional)</label>
                    <input type="date" name="next_follow_up" value="{{ old('next_follow_up') }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('medical-records.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>SIMPAN HASIL PEMERIKSAAN</span>
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    let medCount = 1;
    function addMedicineRow() {
        const container = document.getElementById('medicine-rows');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-2 bg-slate-50 p-3 rounded-2xl border border-slate-200 text-xs';
        div.innerHTML = `
            <div class="col-span-5">
                <select name="medicines[${medCount}][id]" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                    <option value="">-- Pilih Obat --</option>
                    @foreach($medicines as $m)
                    <option value="{{ $m->id }}">{{ $m->name }} (Stok: {{ $m->stock }} {{ $m->unit }}) - Rp {{ number_format($m->selling_price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <input type="number" min="1" name="medicines[${medCount}][quantity]" value="1" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
            </div>
            <div class="col-span-4">
                <input type="text" name="medicines[${medCount}][instruction]" value="3 x 1 Tablet sesudah makan" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
            </div>
            <div class="col-span-1 flex items-end justify-center pb-1">
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
            </div>
        `;
        container.appendChild(div);
        medCount++;
    }
</script>
@endsection
