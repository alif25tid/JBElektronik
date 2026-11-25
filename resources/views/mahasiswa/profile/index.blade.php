@extends('layouts.app')

@section('title', 'Akun Saya - Jualin PCR')

@section('content')
@php
    $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $formatTanggalWaktu = function ($timestamp) use ($bulanIndo) {
        if (!$timestamp) {
            return '-';
        }

        $dt = \Carbon\Carbon::parse($timestamp)->timezone(config('app.timezone', 'Asia/Jakarta'));
        $namaBulan = $bulanIndo[$dt->month] ?? $dt->format('F');

        return $dt->day . ' ' . $namaBulan . ' ' . $dt->year . ' • ' . $dt->format('H:i');
    };
@endphp

<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Akun &amp; Data Diri</h1>
            <p class="text-xs sm:text-sm text-gray-600">Lihat dan perbarui informasi profil akun Jualin PCR Anda</p>
        </div>
        <a href="{{ route('mahasiswa.dashboard') }}" class="text-xs font-medium text-orange-600 hover:underline self-start sm:self-auto">
            Kembali ke Katalog
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Ringkasan Profil (Mode Lihat Data Diri) -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-6">
        <div class="flex items-center gap-4 pb-5 border-b border-gray-100">
            <div class="w-14 h-14 rounded-full bg-orange-500 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">
                {{ strtoupper(mb_substr($user->name ?? 'M', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <h2 class="text-base font-bold text-gray-900 truncate">{{ $user->name }}</h2>
                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                <span class="inline-block mt-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-orange-100 text-orange-700">
                    {{ strtoupper($user->role ?? 'MAHASISWA') }} • Jualin PCR
                </span>
            </div>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-5 text-sm">
            <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100">
                <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Nama Lengkap</dt>
                <dd class="font-semibold text-gray-800 mt-1">{{ $user->name }}</dd>
            </div>

            <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100">
                <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Email Kampus</dt>
                <dd class="font-semibold text-gray-800 mt-1 break-all">{{ $user->email }}</dd>
            </div>

            <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100 sm:col-span-2">
                <dt class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">No. WhatsApp</dt>
                <dd class="font-semibold text-gray-800 mt-1">{{ $user->no_whatsapp ?: 'Belum diisi' }}</dd>
            </div>

            <div class="p-3.5 rounded-xl bg-orange-50/50 border border-orange-100">
                <dt class="text-[11px] font-semibold uppercase tracking-wider text-orange-600">Tanggal Bergabung</dt>
                <dd class="font-semibold text-gray-800 mt-1">{{ $formatTanggalWaktu($user->created_at) }}</dd>
            </div>

            <div class="p-3.5 rounded-xl bg-orange-50/50 border border-orange-100">
                <dt class="text-[11px] font-semibold uppercase tracking-wider text-orange-600">Terakhir Update</dt>
                <dd class="font-semibold text-gray-800 mt-1">{{ $formatTanggalWaktu($user->updated_at) }}</dd>
            </div>
        </dl>
    </div>

    <!-- Form Edit Data Diri -->
    <form action="{{ route('mahasiswa.profile.update') }}" method="POST" class="bg-white rounded-2xl border border-gray-200 shadow-xs p-6 space-y-4">
        @csrf
        @method('PUT')

        <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Edit Data Diri</h3>

        <div>
            <label for="name" class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500"
            >
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1.5">Email Kampus</label>
            <input
                type="email"
                id="email"
                value="{{ $user->email }}"
                disabled
                class="w-full px-4 py-2.5 text-sm border border-gray-200 bg-gray-100 text-gray-500 rounded-xl cursor-not-allowed"
            >
            <p class="text-[11px] text-gray-400 mt-1">Email institusi dikunci untuk keamanan identitas akun.</p>
        </div>

        <div>
            <label for="no_whatsapp" class="block text-xs font-semibold text-gray-700 mb-1.5">No. WhatsApp</label>
            <input
                type="text"
                id="no_whatsapp"
                name="no_whatsapp"
                value="{{ old('no_whatsapp', $user->no_whatsapp) }}"
                placeholder="Contoh: 081234567890"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500"
            >
        </div>

        <div class="pt-2 flex justify-end">
            <button
                type="submit"
                class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-xl transition-colors cursor-pointer"
            >
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection