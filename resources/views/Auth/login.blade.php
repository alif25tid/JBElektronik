@extends('layouts.auth')

@section('title', 'Masuk - Jualin PCR')

@section('content')
<div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h1>
        <p class="text-sm text-gray-500 mt-1">Masuk ke akun Jualin-PCR Anda</p>
    </div>

    <!-- Notifikasi Sukses Registrasi -->
    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Notifikasi Error Login -->
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Kampus</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                placeholder="nama@mahasiswa.pcr.ac.id"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" id="password" required
                placeholder="••••••••"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                <span>Ingat saya</span>
            </label>
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">
            Masuk
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-100 text-center text-sm text-gray-600">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="font-semibold text-orange-600 hover:text-orange-700 transition-colors">
            Daftar sekarang
        </a>
    </div>
</div>
@endsection