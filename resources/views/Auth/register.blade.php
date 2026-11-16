@extends('layouts.auth')

@section('title', 'Daftar Akun - Jualin PCR')

@section('content')
<div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Gunakan email resmi kampus (<span class="font-medium text-gray-700">pcr.ac.id</span>)</p>
    </div>

    <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                placeholder="Nama Asli"
                class="w-full px-4 py-2.5 text-sm border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            @error('name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Kampus -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Resmi PCR</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                placeholder="xxxxx@mahasiswa.pcr.ac.id"
                class="w-full px-4 py-2.5 text-sm border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            @error('email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nomor WhatsApp -->
        <div>
            <label for="no_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Aktif</label>
            <input type="text" name="no_whatsapp" id="no_whatsapp" value="{{ old('no_whatsapp') }}" required
                placeholder="6281234567890 (Awali dengan 62)"
                class="w-full px-4 py-2.5 text-sm border @error('no_whatsapp') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            <p class="text-xs text-gray-400 mt-1">Digunakan untuk koordinasi transaksi COD.</p>
            @error('no_whatsapp')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" id="password" required
                placeholder="Minimal 8 karakter"
                class="w-full px-4 py-2.5 text-sm border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            @error('password')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Ulangi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                placeholder="Ketik ulang password"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 mt-2 bg-orange-600 hover:bg-orange-700 text-white font-medium text-sm rounded-lg shadow-sm transition-colors">
            Daftar Akun
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-100 text-center text-sm text-gray-600">
        Sudah punya akun? 
        <a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:text-orange-700 transition-colors">
            Masuk di sini
        </a>
    </div>
</div>
@endsection