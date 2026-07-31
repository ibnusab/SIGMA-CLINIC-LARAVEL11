<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pasien - {{ $patient->mr_number }}</title>
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
            <span>CETAK KARTU PASIEN</span>
        </button>
    </div>

    <!-- Printable Card ID -->
    <div class="w-[380px] h-[230px] bg-gradient-to-br from-slate-900 via-sky-950 to-slate-900 text-white rounded-2xl p-5 shadow-2xl relative overflow-hidden border border-sky-500/30 flex flex-col justify-between">
        
        <!-- Background Watermark Decor -->
        <div class="absolute -right-10 -bottom-10 text-slate-800/30 text-9xl pointer-events-none">
            <i class="fa-solid fa-heart-pulse"></i>
        </div>

        <!-- Card Header -->
        <div class="flex items-center justify-between border-b border-sky-800/60 pb-3 z-10">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-sky-500 flex items-center justify-center text-white text-base font-black shadow-md">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
                <div>
                    <h1 class="text-xs font-black tracking-wide leading-none text-white">SIGMA CLINIC</h1>
                    <p class="text-[9px] text-sky-300 font-semibold tracking-wider uppercase mt-0.5">KARTU BEROBAT PASIEN</p>
                </div>
            </div>
            <span class="text-[9px] font-bold text-sky-200 bg-sky-900/80 px-2 py-0.5 rounded border border-sky-700">AKSI CEPAT</span>
        </div>

        <!-- Card Body -->
        <div class="z-10 my-auto">
            <div class="text-[10px] text-sky-300 uppercase font-bold tracking-widest mb-0.5">NOMOR REKAM MEDIS (NO. RM)</div>
            <div class="text-xl font-black tracking-wider text-amber-400 font-mono">{{ $patient->mr_number }}</div>

            <div class="mt-2 grid grid-cols-2 gap-2 text-[10px]">
                <div>
                    <span class="text-slate-400 block uppercase">NAMA PASIEN</span>
                    <strong class="text-white font-extrabold text-xs truncate block">{{ $patient->name }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block uppercase">NIK PASIEN</span>
                    <strong class="text-slate-200 font-bold block">{{ $patient->nik }}</strong>
                </div>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="flex items-center justify-between z-10 pt-2 border-t border-sky-800/60 text-[9px] text-slate-400">
            <div>JL. KESEHATAN NO. 88, JAKARTA | TELP: 021-7890123</div>
            <div class="font-bold text-sky-300">GOL: {{ $patient->blood_type ?? '-' }}</div>
        </div>

    </div>

</body>
</html>
