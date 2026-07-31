@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="bg-white rounded-3xl p-8 shadow-2xl border border-slate-100">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">Reset Password Akun</h2>
        <p class="text-xs text-slate-500 mt-1">Masukkan email terdaftar Anda. Kami akan mengirimkan tautan reset password.</p>
    </div>

    @if(session('status'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-semibold border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Terdaftar</label>
            <input type="email" name="email" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-sky-500" placeholder="nama@sigmaclinic.com">
        </div>

        <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-600/30 transition-all">
            KIRIM INSTRUKSI RESET
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-xs text-slate-500 font-bold hover:text-sky-600">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Halaman Login
        </a>
    </div>
</div>
@endsection
