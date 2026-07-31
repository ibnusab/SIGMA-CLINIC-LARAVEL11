@extends('layouts.app')

@section('title', 'Buat Resep Obat Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Form Pembuatan Resep Obat Manual</h3>
                <p class="text-xs text-slate-500">Pilih pasien dan item racikan/resep obat yang diberikan</p>
            </div>
            <a href="{{ route('prescriptions.index') }}" class="px-3.5 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-all">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('prescriptions.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Pasien <span class="text-rose-500">*</span></label>
                    <select name="patient_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($patients as $p)
                        <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>[{{ $p->mr_number }}] {{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Dokter Peresep <span class="text-rose-500">*</span></label>
                    <select name="doctor_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500">
                        <option value="">-- Pilih Dokter --</option>
                        @foreach($doctors as $d)
                        <option value="{{ $d->id }}" {{ old('doctor_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Recipe Items -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700">Daftar Item Obat</h4>
                    <button type="button" onclick="addMedItem()" class="px-3 py-1 bg-purple-100 text-purple-800 hover:bg-purple-200 rounded-lg text-xs font-bold">
                        + Tambah Item
                    </button>
                </div>

                <div id="med-item-rows" class="space-y-3">
                    <div class="grid grid-cols-12 gap-2 bg-slate-50 p-3 rounded-2xl border border-slate-200 text-xs">
                        <div class="col-span-5">
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Pilih Obat</label>
                            <select name="medicines[0][id]" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                                <option value="">-- Pilih Obat --</option>
                                @foreach($medicines as $m)
                                <option value="{{ $m->id }}">{{ $m->name }} (Rp {{ number_format($m->selling_price, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Jumlah</label>
                            <input type="number" min="1" name="medicines[0][quantity]" value="1" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                        </div>
                        <div class="col-span-4">
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Aturan Pakai</label>
                            <input type="text" name="medicines[0][instruction]" value="3 x 1 Tablet sesudah makan" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                        </div>
                        <div class="col-span-1 flex items-end justify-center pb-1">
                            <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('prescriptions.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-600/20 transition-all">
                    SIMPAN RESEP OBAT
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    let pCount = 1;
    function addMedItem() {
        const container = document.getElementById('med-item-rows');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-12 gap-2 bg-slate-50 p-3 rounded-2xl border border-slate-200 text-xs';
        div.innerHTML = `
            <div class="col-span-5">
                <select name="medicines[${pCount}][id]" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                    <option value="">-- Pilih Obat --</option>
                    @foreach($medicines as $m)
                    <option value="{{ $m->id }}">{{ $m->name }} (Rp {{ number_format($m->selling_price, 0, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <input type="number" min="1" name="medicines[${pCount}][quantity]" value="1" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
            </div>
            <div class="col-span-4">
                <input type="text" name="medicines[${pCount}][instruction]" value="3 x 1 Tablet sesudah makan" class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
            </div>
            <div class="col-span-1 flex items-end justify-center pb-1">
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
            </div>
        `;
        container.appendChild(div);
        pCount++;
    }
</script>
@endsection
