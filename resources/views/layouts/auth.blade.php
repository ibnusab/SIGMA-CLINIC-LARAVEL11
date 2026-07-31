<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - SIGMA CLINIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-sky-950 to-slate-900 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 to-blue-600 items-center justify-center text-white text-3xl font-black shadow-xl shadow-sky-500/30 mb-4">
                <i class="fa-solid fa-heart-pulse"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">SIGMA CLINIC</h1>
            <p class="text-xs text-sky-300 font-medium tracking-wide uppercase mt-1">Sistem Informasi Manajemen Klinik Modern</p>
        </div>

        @yield('content')

        <p class="text-center text-xs text-slate-400 mt-8">&copy; {{ date('Y') }} SIGMA CLINIC (Laravel 11 Engine). All Rights Reserved.</p>
    </div>
</body>
</html>
