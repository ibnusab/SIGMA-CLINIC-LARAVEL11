<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Antrian #{{ $registration->queue_number }} - SIGMA CLINIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col items-center justify-center p-6 antialiased">

    <div class="no-print mb-6">
        <button onclick="window.print()" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow-lg text-xs flex items-center space-x-2">
            <i class="fa-solid fa-print"></i>
            <span>CETAK STRIP ANTRIAN</span>
        </button>
    </div>

    <!-- Thermal Receipt Style Ticket -->
    <div class="w-[300px] bg-white p-6 rounded-2xl shadow-2xl border border-slate-200 text-slate-800 text-center font-mono">
        
        <div class="border-b-2 border-dashed border-slate-300 pb-3 mb-3">
            <h1 class="text-base font-black tracking-wider text-slate-900">SIGMA CLINIC</h1>
            <p class="text-[10px] text-slate-500 font-sans">Jl. Kesehatan No. 88, Jakarta Selatan</p>
            <p class="text-[10px] text-slate-500 font-sans">Telp: (021) 7890123</p>
        </div>

        <div class="my-2">
            <span class="text-[11px] font-sans uppercase font-extrabold text-slate-500">NOMOR ANTRIAN ANDA</span>
            <div class="text-5xl font-black text-sky-600 my-2 tracking-widest">
                #{{ str_pad($registration->queue_number, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div class="inline-block bg-sky-100 text-sky-800 text-xs font-bold font-sans px-3 py-1 rounded-full uppercase">
                {{ $registration->clinic->name ?? '-' }}
            </div>
        </div>

        <div class="border-y border-dashed border-slate-300 py-3 my-3 text-[11px] text-left space-y-1 font-sans">
            <div class="flex justify-between">
                <span class="text-slate-500">No. Registrasi:</span>
                <strong class="font-mono text-slate-800">{{ $registration->registration_number }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">No. Rekam Medis:</span>
                <strong class="font-mono text-sky-700">{{ $registration->patient->mr_number ?? '-' }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Nama Pasien:</span>
                <strong class="text-slate-900 truncate max-w-[150px]">{{ $registration->patient->name ?? '-' }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Dokter Spesialis:</span>
                <strong class="text-slate-800 truncate max-w-[150px]">{{ $registration->doctor->name ?? '-' }}</strong>
            </div>
            @if($registration->doctor && $registration->doctor->specialization)
            <div class="flex justify-between">
                <span class="text-slate-500">Keahlian:</span>
                <strong class="text-sky-800 font-semibold truncate max-w-[150px]">{{ $registration->doctor->specialization }}</strong>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-slate-500">Jenis Kunjungan:</span>
                <strong class="uppercase text-emerald-700 font-extrabold">{{ $registration->visit_type_label ?? 'Umum (Mandiri)' }}</strong>
            </div>
            @if($registration->complaints || $registration->complaint)
            <div class="flex justify-between">
                <span class="text-slate-500">Keluhan:</span>
                <strong class="text-slate-800 font-semibold truncate max-w-[150px]">{{ $registration->complaints ?? $registration->complaint }}</strong>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-slate-500">Waktu Daftar:</span>
                <span>{{ \Carbon\Carbon::parse($registration->created_at)->format('H:i') }} WIB</span>
            </div>
        </div>

        <div class="text-[10px] text-slate-500 font-sans italic mt-3">
            Mohon menunggu dipanggil oleh petugas di ruang tunggu poli.<br>
            <strong class="text-slate-800 font-bold">Terima kasih atas kepercayaan Anda.</strong>
        </div>

    </div>

</body>
</html>
