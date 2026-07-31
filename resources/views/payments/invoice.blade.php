<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran - {{ $payment->payment_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .invoice-card { shadow: none !important; border: 1px solid #e2e8f0 !important; width: 100% !important; max-width: 100% !important; border-radius: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 px-4 flex flex-col items-center justify-center antialiased">

    <!-- Top Action Toolbar -->
    <div class="no-print mb-6 flex items-center space-x-3">
        <button onclick="window.print()" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-lg shadow-sky-600/20 text-xs flex items-center space-x-2 transition-all">
            <i class="fa-solid fa-print"></i>
            <span>CETAK KWITANSI / STRUK</span>
        </button>

        <button onclick="window.close()" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded-xl border border-slate-200 text-xs transition-all">
            <i class="fa-solid fa-xmark"></i> Tutup
        </button>
    </div>

    <!-- Official Kwitansi Container -->
    <div class="invoice-card w-full max-w-2xl bg-white p-8 md:p-10 rounded-3xl shadow-2xl border border-slate-200/80 text-slate-800 space-y-6 relative overflow-hidden">
        
        <!-- Watermark Badge -->
        <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Clinic Official Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b-2 border-slate-900 pb-6 gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-linear-to-tr from-sky-600 to-blue-700 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-sky-600/30">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-tight text-slate-900 leading-none">SIGMA CLINIC UTAMA</h1>
                    <p class="text-xs font-semibold text-sky-700 mt-1">Layanan Kesehatan & Farmasi Terpadu</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Jl. Kesehatan No. 88, Jakarta Selatan | Telp: (021) 7890123</p>
                    <p class="text-[10px] text-slate-400 font-mono">SIP: SIP.440/123/DISKES/2026</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="inline-flex items-center space-x-1.5 text-xs font-black tracking-wider uppercase text-emerald-800 bg-emerald-100/90 px-3.5 py-1.5 rounded-full border border-emerald-300 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>LUNAS / PAID</span>
                </span>
                <p class="text-xs font-mono font-black text-slate-800 mt-2">{{ $payment->invoice->invoice_number ?? $payment->payment_number }}</p>
                <p class="text-[10px] text-slate-500 font-medium">Ref: {{ $payment->payment_number }}</p>
            </div>
        </div>

        <!-- Meta Patient & Doctor Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-slate-50/80 p-5 rounded-2xl border border-slate-200/80">
            <div class="space-y-1">
                <span class="text-slate-400 block uppercase text-[10px] font-black tracking-wider">Identitas Pasien</span>
                <strong class="text-slate-900 font-extrabold text-sm block">{{ $payment->patient->name ?? '-' }}</strong>
                <p class="text-slate-600">No. Rekam Medis: <strong class="text-sky-700 font-mono font-bold">{{ $payment->patient->mr_number ?? '-' }}</strong></p>
                <p class="text-slate-500">NIK: {{ $payment->patient->nik ?? '-' }}</p>
            </div>

            <div class="space-y-1">
                <span class="text-slate-400 block uppercase text-[10px] font-black tracking-wider">Detail Kunjungan & Kasir</span>
                <p class="text-slate-800 font-bold">Dokter: {{ $payment->registration->doctor->name ?? '-' }}</p>
                <p class="text-slate-600">Poli / Klinik: {{ $payment->registration->clinic->name ?? 'Poli Umum' }}</p>
                <p class="text-slate-600">Tgl & Waktu Bayar: <strong class="text-slate-800">{{ \Carbon\Carbon::parse($payment->paid_at ?? $payment->created_at)->isoFormat('D MMMM YYYY, HH:mm') }} WIB</strong></p>
                <p class="text-slate-600">Metode Bayar: <span class="uppercase font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">{{ $payment->payment_method ?? 'Tunai' }}</span></p>
            </div>
        </div>

        <!-- Itemized Billing Breakdown -->
        <div class="space-y-2">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Rincian Layanan & Pengobatan</h3>
            
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100 font-black uppercase text-[10px] text-slate-600 border-b border-slate-200">
                            <th class="p-3">Keterangan Komponen Biaya</th>
                            <th class="p-3 text-center">Jumlah / Qty</th>
                            <th class="p-3 text-right">Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        <!-- Consultation -->
                        <tr>
                            <td class="p-3 font-semibold">
                                Jasa Konsultasi & Pemeriksaan Dokter
                                <span class="block text-[10px] text-slate-400 font-normal">{{ $payment->registration->doctor->name ?? 'Dokter Spesialis/Umum' }}</span>
                            </td>
                            <td class="p-3 text-center text-slate-500">1 Layanan</td>
                            <td class="p-3 text-right font-bold text-slate-900">Rp {{ number_format($payment->consultation_fee ?? 0, 0, ',', '.') }}</td>
                        </tr>

                        <!-- Treatments -->
                        @if(($payment->treatment_fee ?? 0) > 0)
                        <tr>
                            <td class="p-3 font-semibold">
                                Tindakan Medis & Prosedur Klinik
                                @if(isset($payment->registration->medicalRecord->treatments) && count($payment->registration->medicalRecord->treatments) > 0)
                                    <ul class="text-[10px] text-slate-500 font-normal list-disc list-inside mt-0.5">
                                        @foreach($payment->registration->medicalRecord->treatments as $trt)
                                            <li>{{ $trt->treatment_name ?? $trt->name }} (Rp {{ number_format($trt->pivot->price ?? $trt->price ?? 0, 0, ',', '.') }})</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="p-3 text-center text-slate-500">-</td>
                            <td class="p-3 text-right font-bold text-slate-900">Rp {{ number_format($payment->treatment_fee ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endif

                        <!-- Medicines -->
                        @if(($payment->medicine_fee ?? 0) > 0)
                        <tr>
                            <td class="p-3 font-semibold">
                                Obat-Obatan & Alkes Apotek
                                @if(isset($payment->registration->medicalRecord->prescriptions) && count($payment->registration->medicalRecord->prescriptions) > 0)
                                    <ul class="text-[10px] text-slate-500 font-normal list-disc list-inside mt-0.5">
                                        @foreach($payment->registration->medicalRecord->prescriptions as $pres)
                                            @foreach($pres->items as $item)
                                                <li>{{ $item->medicine->name ?? 'Obat' }} x {{ $item->quantity }} {{ $item->medicine->unit ?? '' }}</li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="p-3 text-center text-slate-500">-</td>
                            <td class="p-3 text-right font-bold text-slate-900">Rp {{ number_format($payment->medicine_fee ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endif

                        <!-- Discount if any -->
                        @if(($payment->discount ?? 0) > 0)
                        <tr class="bg-rose-50/50 text-rose-900">
                            <td class="p-3 font-bold" colspan="2">Potongan Diskon / Potongan Harga</td>
                            <td class="p-3 text-right font-extrabold text-rose-700">- Rp {{ number_format($payment->discount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-900 text-white font-black text-sm">
                            <td colspan="2" class="p-4 uppercase tracking-wider text-right">TOTAL DIBAYAR LUNAS:</td>
                            <td class="p-4 text-right text-emerald-400 font-mono text-base">Rp {{ number_format($payment->total_amount ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Verification & Signature Section -->
        <div class="grid grid-cols-2 gap-4 text-xs pt-6 border-t border-slate-200">
            <div class="text-center space-y-8">
                <p class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Pasien / Wali</p>
                <div class="pt-4">
                    <p class="text-slate-800 font-extrabold underline">{{ $payment->patient->name ?? 'Pasien' }}</p>
                </div>
            </div>

            <div class="text-center space-y-8">
                <p class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Kasir / Petugas SIGMA</p>
                <div class="pt-4">
                    <p class="text-slate-800 font-extrabold underline">{{ Auth::user()->name ?? 'Petugas Kasir' }}</p>
                    <p class="text-[10px] text-slate-400">SIGMA CLINIC Management System</p>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center pt-4 border-t border-slate-100">
            <p class="text-[11px] font-semibold text-slate-500">Terima kasih atas kunjungan Anda di <strong class="text-slate-800">SIGMA CLINIC UTAMA</strong>.</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Semoga Anda beserta keluarga senantiasa sehat selalu.</p>
        </div>

    </div>

</body>
</html>
