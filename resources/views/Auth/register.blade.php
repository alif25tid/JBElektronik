@extends('layouts.auth')

@section('title', 'Daftar - Jualin PCR')

@section('content')
<!-- Bungkus dengan grid 2 kolom agar bentuknya sama persis dengan halaman login -->
<div class="max-w-5xl w-full bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-2">
    
    <!-- KOLOM KIRI: Gambar / Ilustrasi -->
    <div class="hidden lg:block relative bg-orange-50 h-full min-h-[550px]">
        <img src="{{ asset('images/gambarlogin.jpg') }}" alt="Ilustrasi Jualin PCR" class="absolute inset-0 w-full h-full object-cover">
    </div>

    <!-- KOLOM KANAN: Form Registrasi -->
    <div class="p-8 sm:p-10 flex flex-col justify-center">
        <div class="text-center mb-6">
            <img src="{{ asset('images/logologin.png') }}" alt="Logo Jualin PCR" class="w-40 h-auto object-contain mx-auto">
            <p class="text-xs text-gray-500 mt-1">Gunakan email resmi kampus (<strong>pcr.ac.id</strong>)</p>
        </div>

        <!-- Notifikasi Error -->
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                    placeholder="Nama Asli"
                    class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email Resmi PCR</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    placeholder="xxxxx@mahasiswa.pcr.ac.id"
                    class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label for="whatsapp" class="block text-xs font-medium text-gray-700 mb-1">Nomor WhatsApp Aktif</label>
                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" required
                    placeholder="6281234567890 (Awali dengan 62)"
                    class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    placeholder="Minimal 8 karakter"
                    class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">Ulangi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    placeholder="Ketik ulang password"
                    class="w-full px-3.5 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>

            <button type="submit"
                class="w-full py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors mt-2">
                Daftar Akun
            </button>
        </form>

        <div class="mt-5 pt-4 border-t border-gray-100 text-center text-xs text-gray-600">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                Masuk di sini
            </a>
        </div>
    </div>

</div>
@endsection