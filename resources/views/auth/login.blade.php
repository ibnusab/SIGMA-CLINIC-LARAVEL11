@extends('layouts.auth')

@section('title', 'Login Sistem')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-2xl border border-slate-100">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">Masuk Akses Akun</h2>
        <p class="text-xs text-slate-500 mt-1">Silakan masukkan kredensial akun Anda untuk melanjutkan.</p>
    </div>

    @if(session('info'))
        <div class="mb-4 p-3 rounded-xl bg-sky-50 text-sky-800 text-xs font-semibold border border-sky-200">
            {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-rose-50 text-rose-800 text-xs font-semibold border border-rose-200">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Kredensial</label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input type="email" id="email" name="email" value="{{ old('email', 'admin@sigmaclinic.com') }}" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all" placeholder="email@sigmaclinic.com">
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">Password</label>
                <a href="{{ route('password.request') }}" class="text-xs text-sky-600 font-semibold hover:underline">Lupa Password?</a>
            </div>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input type="password" id="password" name="password" value="password" required class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all" placeholder="••••••••">
                <button type="button" onclick="togglePassword('password', 'toggle-icon-pass')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1.5 flex items-center justify-center transition-colors" title="Lihat Password">
                    <i id="toggle-icon-pass" class="fa-solid fa-eye text-sm"></i>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center space-x-2 text-xs text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                <span>Ingat Saya</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-600/30 transition-all flex items-center justify-center space-x-2 cursor-pointer" style="background: linear-gradient(to right, #0284c7, #1d4ed8);">
            <span>MASUK SISTEM</span>
            <i class="fa-solid fa-arrow-right"></i>
        </button>
    </form>

    <!-- Quick Role Switcher for Demo / Testing -->
    <div class="mt-8 pt-6 border-t border-slate-100">
        <p class="text-[11px] font-bold text-slate-400 uppercase text-center mb-3">Login Demo Akun Cepat:</p>
        <div class="grid grid-cols-2 gap-2 text-[11px]">
            <button onclick="fillLogin('admin@sigmaclinic.com')" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 font-bold text-slate-700 flex items-center justify-center space-x-1">
                <i class="fa-solid fa-user-gear text-sky-600"></i>
                <span>Admin</span>
            </button>
            <button onclick="fillLogin('dokter.andri@sigmaclinic.com')" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 font-bold text-slate-700 flex items-center justify-center space-x-1">
                <i class="fa-solid fa-user-doctor text-emerald-600"></i>
                <span>Dokter</span>
            </button>
            <button onclick="fillLogin('resepsionis@sigmaclinic.com')" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 font-bold text-slate-700 flex items-center justify-center space-x-1">
                <i class="fa-solid fa-user-nurse text-amber-600"></i>
                <span>Resepsionis</span>
            </button>
            <button onclick="fillLogin('apoteker@sigmaclinic.com')" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 font-bold text-slate-700 flex items-center justify-center space-x-1">
                <i class="fa-solid fa-pills text-purple-600"></i>
                <span>Apoteker</span>
            </button>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function fillLogin(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
    }
</script>
@endsection
