@extends('layouts.app')

@section('title', 'Katalog Barang - Jualin PCR')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Katalog Barang Elektronik</h1>
            <p class="text-xs sm:text-sm text-gray-600">Temukan perlengkapan elektronik mahasiswa di lingkungan kampus PCR</p>
        </div>
        @if(request('search') || request('category'))
            <a href="{{ route('mahasiswa.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-600 hover:text-orange-700 bg-orange-50 px-3 py-1.5 rounded-full border border-orange-200 self-start">
                <span>Reset Filter</span>
                <span>&times;</span>
            </a>
        @endif
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @forelse($products as $product)
            <a href="{{ route('mahasiswa.toko.show', $product->id) }}"
               class="group bg-white/95 rounded-2xl border border-gray-200/90 overflow-hidden shadow-xs hover:shadow-lg hover:border-orange-400 hover:-translate-y-1 transition-all duration-200 flex flex-col">

                <!-- Foto Produk (Rasio Persegi / Kompak) -->
                <div class="relative aspect-square bg-gray-100 overflow-hidden">
                    @if($product->foto)
                        <img src="{{ asset('storage/' . $product->foto) }}"
                             alt="{{ $product->nama_barang }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 gap-1">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[11px]">Tidak ada foto</span>
                        </div>
                    @endif

                    <!-- Badge Kategori di Pojok Kiri Atas -->
                    <span class="absolute top-2 left-2 px-2 py-0.5 text-[10px] font-semibold bg-white/90 backdrop-blur-xs text-gray-800 rounded-md shadow-xs border border-gray-200/70 max-w-[85%] truncate">
                        {{ $product->category->nama_kategori ?? $product->category->name ?? 'Elektronik' }}
                    </span>

                    <!-- Badge Video Review jika ada -->
                    @if($product->video_review && $product->video_review !== '-')
                        <span class="absolute bottom-2 right-2 px-1.5 py-0.5 text-[10px] font-semibold bg-slate-900/80 text-white rounded-md inline-flex items-center gap-1">
                            <svg class="w-3 h-3 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                            </svg>
                            Video
                        </span>
                    @endif
                </div>

                <!-- Detail Produk -->
                <div class="p-3 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xs sm:text-sm font-semibold text-gray-800 group-hover:text-orange-600 line-clamp-2 leading-snug transition-colors">
                            {{ $product->nama_barang }}
                        </h2>

                        <p class="text-sm sm:text-base font-extrabold text-orange-600 mt-1.5">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="mt-2.5 pt-2 border-t border-gray-100 space-y-1 text-[11px] text-gray-500">
                        <div class="flex items-center gap-1 truncate">
                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="truncate">{{ $product->user->name ?? 'Mahasiswa' }}</span>
                        </div>

                        @if($product->titik_lokasi)
                            <div class="flex items-center gap-1 truncate text-gray-400">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $product->titik_lokasi }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12 bg-white/95 rounded-2xl border border-gray-200">
                @if(request('search') || request('category'))
                    <p class="text-gray-700 text-sm font-medium">
                        Barang tidak ditemukan untuk pencarian "{{ request('search') }}".
                    </p>
                    <p class="text-gray-400 text-xs mt-1">
                        Coba kata kunci lain atau
                        <a href="{{ route('mahasiswa.dashboard') }}" class="text-orange-600 hover:underline font-medium">tampilkan semua barang</a>.
                    </p>
                @else
                    <p class="text-gray-500 text-sm">Belum ada barang elektronik yang dijual saat ini.</p>
                @endif
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script>
        console.log('Halaman dashboard siap!');
    </script>
@endpush