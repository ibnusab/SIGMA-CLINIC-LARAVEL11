<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - SIGMA CLINIC</title>
    <!-- Tailwind CSS (via CDN for instant preview compatibility) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clinic: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                            800: '#0c4a6e',
                            900: '#0a3651',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts Inter & Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Notice Bar -->
    <div class="bg-clinic-800 text-white text-xs px-4 py-1.5 flex justify-between items-center shadow-inner">
        <div class="flex items-center space-x-3">
            <span class="font-semibold tracking-wide flex items-center"><i class="fa-solid fa-hospital mr-1.5 text-sky-300"></i> SIGMA CLINIC UTAMA</span>
            <span class="hidden md:inline text-slate-300">| Jl. Kesehatan No. 88, Jakarta Selatan</span>
            <span class="hidden lg:inline text-slate-300">| SIP.440/123/DISKES/2026</span>
        </div>
        <div class="flex items-center space-x-4">
            <span class="hidden sm:inline"><i class="fa-regular fa-clock mr-1 text-sky-300"></i> <?php echo e(\Carbon\Carbon::now()->isoFormat('D MMMM YYYY, HH:mm')); ?> WIB</span>
            <span class="bg-emerald-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Sistem Online</span>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
<?php
    $rawRole = Auth::user()->role ?? 'admin';
    $normalizedRole = strtolower(trim($rawRole));

    $isRoleAdmin = in_array($normalizedRole, ['admin']);
    $isRoleDokter = in_array($normalizedRole, ['dokter', 'doctor']);
    $isRoleApoteker = in_array($normalizedRole, ['apoteker', 'pharmacist']);
    $isRoleResepsionis = in_array($normalizedRole, ['resepsionis', 'receptionist']);

    $roleBadgeText = match(true) {
        $isRoleAdmin => 'Administrator',
        $isRoleDokter => 'Dokter',
        $isRoleApoteker => 'Apoteker / Farmasi',
        $isRoleResepsionis => 'Resepsionis',
        default => ucfirst($normalizedRole),
    };

    $roleBadgeClass = match(true) {
        $isRoleAdmin => 'bg-purple-500/20 text-purple-300 border border-purple-500/30',
        $isRoleDokter => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
        $isRoleApoteker => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
        $isRoleResepsionis => 'bg-sky-500/20 text-sky-300 border border-sky-500/30',
        default => 'bg-slate-500/20 text-slate-300 border border-slate-500/30',
    };
?>

        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="w-64 bg-slate-900 text-slate-300 hidden md:flex flex-col shrink-0 shadow-xl border-r border-slate-800 transition-all">
            <!-- Brand Logo Header -->
            <div class="p-5 border-b border-slate-800 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-sky-500 to-blue-600 flex items-center justify-center text-white text-xl font-extrabold shadow-lg shadow-sky-500/30">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-lg tracking-tight leading-none">SIGMA CLINIC</h1>
                    <p class="text-[11px] text-sky-400 font-medium tracking-wide uppercase mt-1">Laravel 11 Medical SIM</p>
                </div>
            </div>

            <!-- Current Logged User Card -->
            <div class="mx-3 my-4 p-3 rounded-xl bg-slate-800/80 border border-slate-700/60 flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-sky-500 text-white font-bold flex items-center justify-center text-sm shadow">
                    <?php echo e(strtoupper(substr(Auth::user()->name ?? 'U', 0, 1))); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-white truncate"><?php echo e(Auth::user()->name ?? 'Pengguna'); ?></p>
                    <span class="inline-block <?php echo e($roleBadgeClass); ?> text-[10px] font-bold px-2 py-0.5 rounded uppercase mt-0.5">
                        <?php echo e($roleBadgeText); ?>

                    </span>
                </div>
            </div>

            <!-- Menu Links - Role Fixed Access -->
            <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto">
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('dashboard') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-chart-pie w-6 text-sm"></i> Dashboard Utama
                </a>

                
                <?php if($isRoleAdmin): ?>
                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Pelayanan Pasien</div>
                <a href="<?php echo e(route('patients.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('patients.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-hospital-user w-6 text-sm"></i> Data Pasien (RM)
                </a>
                <a href="<?php echo e(route('registrations.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('registrations.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-clipboard-list w-6 text-sm"></i> Pendaftaran & Antrian
                </a>
                <a href="<?php echo e(route('medical-records.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('medical-records.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-stethoscope w-6 text-sm"></i> Pemeriksaan Dokter
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Apotek & Farmasi</div>
                <a href="<?php echo e(route('prescriptions.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('prescriptions.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-prescription-bottle-medical w-6 text-sm"></i> Resep & Pengeluaran Obat
                </a>
                <a href="<?php echo e(route('medicines.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('medicines.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-pills w-6 text-sm"></i> Inventaris Obat & Stok
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Kasir & Keuangan</div>
                <a href="<?php echo e(route('payments.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('payments.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-receipt w-6 text-sm"></i> Kasir & Invoice
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Master Data Klinik</div>
                <a href="<?php echo e(route('doctors.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('doctors.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-user-doctor w-6 text-sm"></i> Dokter Klinik
                </a>
                <a href="<?php echo e(route('clinics.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('clinics.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-clinic-medical w-6 text-sm"></i> Poli Spesialis
                </a>
                <a href="<?php echo e(route('schedules.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('schedules.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-regular fa-calendar-check w-6 text-sm"></i> Jadwal Dokter
                </a>
                <a href="<?php echo e(route('treatments.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('treatments.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-kit-medical w-6 text-sm"></i> Tarif Tindakan
                </a>
                <a href="<?php echo e(route('suppliers.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('suppliers.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-truck-field w-6 text-sm"></i> Supplier Farmasi
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Laporan & Pengaturan</div>
                <a href="<?php echo e(route('users.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('users.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-users-gear w-6 text-sm"></i> Kelola Pengguna (User)
                </a>
                <a href="<?php echo e(route('reports.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('reports.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-file-invoice-dollar w-6 text-sm"></i> Laporan & Statistik
                </a>
                <a href="<?php echo e(route('settings.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('settings.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-gears w-6 text-sm"></i> Pengaturan Sistem
                </a>
                <?php endif; ?>

                
                <?php if($isRoleDokter): ?>
                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Pelayanan Medis</div>
                <a href="<?php echo e(route('medical-records.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('medical-records.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-stethoscope w-6 text-sm"></i> Pemeriksaan Pasien
                </a>
                <a href="<?php echo e(route('patients.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('patients.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-hospital-user w-6 text-sm"></i> Riwayat Pasien (RM)
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Resep & Obat</div>
                <a href="<?php echo e(route('prescriptions.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('prescriptions.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-prescription-bottle-medical w-6 text-sm"></i> Resep Obat Pasien
                </a>
                <a href="<?php echo e(route('medicines.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('medicines.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-pills w-6 text-sm"></i> Cek Stok Obat
                </a>
                <?php endif; ?>

                
                <?php if($isRoleApoteker): ?>
                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Apotek & Farmasi</div>
                <a href="<?php echo e(route('prescriptions.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('prescriptions.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-prescription-bottle-medical w-6 text-sm"></i> Resep & Antrian Obat
                </a>
                <a href="<?php echo e(route('medicines.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('medicines.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-pills w-6 text-sm"></i> Inventaris & Stok Obat
                </a>
                <?php endif; ?>

                
                <?php if($isRoleResepsionis): ?>
                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Front Office</div>
                <a href="<?php echo e(route('registrations.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('registrations.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-clipboard-list w-6 text-sm"></i> Pendaftaran & Antrian
                </a>
                <a href="<?php echo e(route('patients.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('patients.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-hospital-user w-6 text-sm"></i> Data Pasien (RM)
                </a>

                <div class="pt-3 pb-1 px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Kasir & Pembayaran</div>
                <a href="<?php echo e(route('payments.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('payments.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-receipt w-6 text-sm"></i> Kasir & Invoice
                </a>
                <a href="<?php echo e(route('treatments.index')); ?>" class="flex items-center px-3 py-2.5 rounded-lg text-xs font-medium transition-colors <?php echo e(request()->routeIs('treatments.*') ? 'bg-sky-600 text-white font-bold shadow-md shadow-sky-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-kit-medical w-6 text-sm"></i> Daftar Tarif Tindakan
                </a>
                <?php endif; ?>
            </nav>

            <!-- Bottom Logout -->
            <div class="p-3 border-t border-slate-800">
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-3 py-2 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-600 hover:text-white text-xs font-bold transition-all">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Header Bar -->
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center space-x-4">
                    <button onclick="document.getElementById('sidebar').classList.toggle('hidden')" class="md:hidden text-slate-600 text-xl hover:text-sky-600 transition-colors"><i class="fa-solid fa-bars"></i></button>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h2>
                        <p class="text-xs text-slate-500">SIGMA CLINIC Management System</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Quick Action Button per Role -->
                    <?php if($isRoleAdmin || $isRoleResepsionis): ?>
                    <a href="<?php echo e(route('registrations.create')); ?>" class="hidden sm:flex items-center space-x-2 bg-sky-600 hover:bg-sky-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold shadow-md shadow-sky-600/20 transition-all">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Daftar Kunjungan</span>
                    </a>
                    <?php elseif($isRoleDokter): ?>
                    <a href="<?php echo e(route('medical-records.create')); ?>" class="hidden sm:flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20 transition-all">
                        <i class="fa-solid fa-stethoscope"></i>
                        <span>Pemeriksaan Baru</span>
                    </a>
                    <?php elseif($isRoleApoteker): ?>
                    <a href="<?php echo e(route('prescriptions.create')); ?>" class="hidden sm:flex items-center space-x-2 bg-amber-600 hover:bg-amber-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold shadow-md shadow-amber-600/20 transition-all">
                        <i class="fa-solid fa-file-prescription"></i>
                        <span>Resep Baru</span>
                    </a>
                    <?php endif; ?>
                </div>
            </header>

            <!-- Flash Alert Toast -->
            <main class="p-6 flex-1">
                <?php if(session('success')): ?>
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span><?php echo e(session('success')); ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-base font-bold">&times;</button>
                </div>
                <?php endif; ?>

                <?php if(session('info')): ?>
                <div class="mb-6 p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-7 h-7 rounded-full bg-sky-500 text-white flex items-center justify-center font-bold">
                            <i class="fa-solid fa-info"></i>
                        </div>
                        <span><?php echo e(session('info')); ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-sky-500 hover:text-sky-700 text-base font-bold">&times;</button>
                </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold shadow-sm">
                    <div class="flex items-center space-x-2 mb-2 font-bold text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Terdapat kesalahan pengisian form:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-slate-600">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 px-6 py-4 text-center text-xs text-slate-500 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                <div>&copy; <?php echo e(date('Y')); ?> <strong>SIGMA CLINIC</strong>. All rights reserved. Powered by Laravel 11 & Tailwind CSS.</div>
                <div class="text-[11px] text-slate-400">Production-Ready Enterprise SIM Klinik</div>
            </footer>
        </div>
    </div>

</body>
</html>
<?php /**PATH D:\chrome download\android studio\laravel\sigma-clinic\resources\views/layouts/app.blade.php ENDPATH**/ ?>