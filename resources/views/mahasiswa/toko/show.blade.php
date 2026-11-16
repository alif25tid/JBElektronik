@extends('layouts.app')

@section('title', 'Detail Barang - Jualin PCR')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-8 p-6 sm:p-8">
        <!-- Kolom Kiri: Foto & Video Review -->
        <div class="space-y-6">
            <!-- Foto Utama -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Foto Barang</h3>
                <div class="aspect-w-4 aspect-h-3 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_barang }}" class="w-full h-72 object-cover rounded-xl shadow-sm">
                </div>
            </div>

            <!-- Video Review (Jika Ada) -->
            @if($product->video_review && $product->video_review != '-')
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Video Review Produk</h3>
                    <div class="rounded-xl overflow-hidden border border-gray-200 bg-black">
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
                <!-- Status Badge -->
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $product->status == 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        {{ $product->category->nama_kategori ?? 'Tanpa Kategori' }}
                    </span>
                </div>

                <!-- Nama Barang -->
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->nama_barang }}</h1>

                <!-- Harga -->
                <div class="text-2xl font-extrabold text-orange-600 mb-6">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">Deskripsi Barang</h4>
                    <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $product->deskripsi }}</p>
                </div>

                <!-- Info Serah Terima & Lokasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Metode Serah Terima</span>
                        <span class="text-sm font-medium text-gray-800">{{ $product->metode_serah_terima }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Titik Lokasi Pertemuan</span>
                        <span class="text-sm font-medium text-gray-800">{{ $product->titik_lokasi }}</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Bawah -->
            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('mahasiswa.dashboard') }}" class="w-full text-center px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection