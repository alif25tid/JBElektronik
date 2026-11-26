@extends('layouts.app')

@section('title', 'Detail Barang - Jualin PCR')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Notifikasi Peringatan (Barang sudah ada di keranjang) -->
    @if(session('warning'))
        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl text-sm font-medium">
            {{ session('warning') }}
        </div>
    @endif

    <div class="bg-white border border-orange-100/90 rounded-2xl shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-8 p-6 sm:p-8">
        <!-- Kolom Kiri: Foto & Video Review -->
        <div class="space-y-6">
            <!-- Foto Utama -->
            <div>
                <h3 class="text-xs font-bold text-orange-500 uppercase tracking-wider mb-2">Foto Barang</h3>
                <div class="aspect-w-4 aspect-h-3 bg-orange-50/40 rounded-2xl overflow-hidden border border-orange-100">
                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_barang }}" class="w-full h-72 object-cover rounded-2xl shadow-xs">
                </div>
            </div>

            <!-- Video Review (Jika Ada) -->
            @if($product->video_review && $product->video_review != '-')
                <div>
                    <h3 class="text-xs font-bold text-orange-500 uppercase tracking-wider mb-2">Video Review Produk</h3>
                    <div class="rounded-2xl overflow-hidden border border-orange-100 bg-black">
                        <video controls class="w-full h-64 object-contain">
                            <source src="{{ asset('storage/' . $product->video_review) }}" type="video/mp4">
                            Browser Anda tidak mendukung pemutaran video.
                        </video>
                    </div>
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Detail Informasi Barang -->
        <div class="flex flex-col justify-between space-y-6">
            <div>
                <!-- Status & Kategori Badge -->
                <div class="flex items-center justify-between gap-2 mb-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $product->status == 'tersedia' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                    <span class="text-xs font-semibold text-white bg-gradient-to-r from-orange-400 via-orange-500 to-amber-600 px-3 py-1 rounded-full shadow-xs">
                        {{ $product->category->nama_kategori ?? 'Tanpa Kategori' }}
                    </span>
                </div>

                <!-- Nama Barang -->
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->nama_barang }}</h1>

                <!-- Harga -->
                <div class="text-2xl sm:text-3xl font-extrabold bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 bg-clip-text text-transparent mb-6">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </div>

                <!-- Deskripsi (Ukuran Fixed & Scrollable) -->
                <div class="mb-6">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Deskripsi Barang</h4>
                    <div class="h-40 overflow-y-auto pr-2 bg-orange-50/35 p-4 rounded-2xl border border-orange-100/80 text-sm text-gray-600 whitespace-pre-line leading-relaxed">{{ $product->deskripsi }}</div>
                </div>

                <!-- Info Serah Terima & Lokasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-orange-100 pt-4">
                    <div>
                        <span class="block text-[11px] font-bold text-orange-500 uppercase tracking-wider">Metode Serah Terima</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $product->metode_serah_terima }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-orange-500 uppercase tracking-wider">Titik Lokasi Pertemuan</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $product->titik_lokasi }}</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Bawah -->
            <div class="space-y-3 pt-6 border-t border-orange-100">
                <!-- Tombol Masukkan ke Keranjang -->
                <form action="{{ route('mahasiswa.cart.store', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center px-5 py-3 bg-gradient-to-r from-orange-400 via-orange-500 to-amber-600 hover:from-amber-400 hover:via-orange-500 hover:to-orange-600 text-white text-sm font-bold rounded-full shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>+ Masukkan ke Keranjang</span>
                    </button>
                </form>

                <!-- Tombol Chat -->
                @if(auth()->id() != $product->user_id)
                    <a href="{{ route('mahasiswa.chat.show', ['user' => $product->user_id, 'product_id' => $product->id]) }}"
                        class="block w-full text-center py-2.5 px-5 border border-orange-300 text-orange-600 hover:bg-orange-50 font-semibold text-sm rounded-full transition-colors">
                        Chat Penjual
                    </a>
                @endif

                <!-- Tombol Kembali -->
                <a href="{{ route('mahasiswa.dashboard') }}" class="w-full block text-center px-5 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-full hover:bg-gray-50 transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection