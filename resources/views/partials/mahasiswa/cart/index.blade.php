@extends('layouts.app')

@section('title', 'Keranjang Belanja - Jualin PCR')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Keranjang Belanja Anda</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($carts->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center shadow-sm">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-600 text-base font-medium mb-4">Keranjang belanja Anda masih kosong.</p>
            <a href="{{ route('mahasiswa.dashboard') }}" class="inline-block px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-xl transition-colors">
                Cari Barang di Katalog
            </a>
        </div>
    @else
        <div class="space-y-4 mb-6">
            @foreach($carts as $cart)
                <!-- Kotak Pembungkus Produk -->
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:border-orange-300 transition-colors">
                    
                    <!-- Informasi Barang (Foto & Detail) -->
                    <div class="flex items-center space-x-4">
                        @if($cart->product->foto)
                            <img src="{{ asset('storage/' . $cart->product->foto) }}" alt="{{ $cart->product->nama_barang }}" class="w-20 h-20 object-cover rounded-xl border border-gray-200 flex-shrink-0">
                        @else
                            <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 text-xs font-semibold flex-shrink-0">No Image</div>
                        @endif

                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-orange-50 text-orange-700 text-xs font-medium rounded-full mb-1">
                                {{ $cart->product->category->nama_kategori ?? 'Kategori' }}
                            </span>
                            <h3 class="text-base font-semibold text-gray-900">{{ $cart->product->nama_barang }}</h3>
                            <p class="text-sm font-bold text-orange-600">Rp {{ number_format($cart->product->harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-1">Lokasi COD: <span class="font-medium text-gray-700">{{ $cart->product->titik_lokasi }}</span></p>
                        </div>
                    </div>

                    <!-- Tombol Aksi (Didalam Kotak Produk) -->
                    <div class="flex items-center space-x-3 w-full sm:w-auto justify-end border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-100">
                        <!-- Tombol Lihat Detail -->
                        <a href="{{ route('mahasiswa.toko.show', $cart->product->id) }}" class="px-4 py-2 bg-gray-900 hover:bg-orange-600 text-white text-xs font-semibold rounded-xl shadow-sm transition-colors text-center">
                            Lihat Detail
                        </a>
                        
                        <!-- Tombol Hapus -->
                        <form action="{{ route('mahasiswa.cart.destroy', $cart->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus barang ini dari keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-xl transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('mahasiswa.dashboard') }}" class="px-5 py-2.5 border border-orange-500 bg-orange-500 text-white text-sm font-medium rounded-xl hover:bg-orange-700 hover:border-orange-700 transition-colors">
                &larr; Lanjut Belanja
            </a>
        </div>
    @endif
</div>
@endsection