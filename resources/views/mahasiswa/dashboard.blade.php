@extends('layouts.app')

@section('title', 'Katalog Barang - Jualin PCR')

@section('content')
    <!-- Isi Main Content Anda di sini -->
    <h1 class="text-2xl font-bold mb-4">Katalog Barang Elektronik</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    @forelse($products as $product)
        <div class="bg-white border border-orange-500 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
            <!-- Foto Produk -->
            <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
                @if($product->foto)
                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_barang }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xs text-gray-400">Tidak ada foto</span>
                @endif
            </div>

            <!-- Detail Produk -->
            <div class="p-4">
                <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-orange-100 text-orange-800 rounded-full mb-2">
                    {{ $product->category->name ?? 'Kategori' }}
                </span>
                <h2 class="font-bold text-gray-900 text-lg truncate">{{ $product->nama_barang }}</h2>
                <p class="text-orange-600 font-bold mt-1">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-2">Penjual: {{ $product->user->name ?? 'Mahasiswa' }}</p>

                <a href="{{ route('mahasiswa.toko.show', $product->id) }}" class="mt-4 block w-full text-center py-2 px-4 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition-colors">
                    Lihat Detail
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 bg-white rounded-xl border border-gray-200">
            <p class="text-gray-500 text-sm">Belum ada barang elektronik yang dijual saat ini.</p>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
    <script>
        // Jika butuh kode JavaScript khusus hanya untuk halaman dashboard ini
        console.log('Halaman dashboard siap!');
    </script>
@endpush