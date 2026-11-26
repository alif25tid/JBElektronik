@extends('layouts.app')

@section('title', 'Detail Barang - Jualin PCR')

@section('content')
@php
    $galleryPhotos = collect();

    if ($product->relationLoaded('photos') && $product->photos->isNotEmpty()) {
        $galleryPhotos = $product->photos->map(function ($photo) {
            return asset('storage/' . $photo->path);
        })->values();
    }

    if ($galleryPhotos->isEmpty() && !empty($product->foto)) {
        $galleryPhotos = collect([asset('storage/' . $product->foto)]);
    }
@endphp

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
        <!-- Kolom Kiri: Slider Foto & Video Review -->
        <div class="space-y-6">
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <h3 class="text-xs font-bold text-orange-500 uppercase tracking-wider">Foto Barang</h3>
                    @if($galleryPhotos->count() > 1)
                        <span id="detailPhotoCounter" class="text-xs font-semibold text-orange-600 bg-orange-50 px-2.5 py-0.5 rounded-full border border-orange-200">
                            1 / {{ $galleryPhotos->count() }}
                        </span>
                    @endif
                </div>

                @if($galleryPhotos->isNotEmpty())
                    <div class="relative bg-orange-50/40 rounded-2xl overflow-hidden border border-orange-100">
                        <img
                            id="detailMainPhoto"
                            src="{{ $galleryPhotos->first() }}"
                            alt="{{ $product->nama_barang }}"
                            class="w-full h-72 object-cover rounded-2xl shadow-xs transition-opacity duration-200"
                        >

                        @if($galleryPhotos->count() > 1)
                            <button
                                type="button"
                                id="detailPrevPhotoBtn"
                                aria-label="Foto sebelumnya"
                                class="w-9 h-9 rounded-full bg-white/90 hover:bg-white text-gray-800 border border-orange-200 shadow-sm flex items-center justify-center absolute left-3 top-1/2 -translate-y-1/2 transition-all cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <button
                                type="button"
                                id="detailNextPhotoBtn"
                                aria-label="Foto berikutnya"
                                class="w-9 h-9 rounded-full bg-white/90 hover:bg-white text-gray-800 border border-orange-200 shadow-sm flex items-center justify-center absolute right-3 top-1/2 -translate-y-1/2 transition-all cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            <div id="detailPhotoDots" class="flex items-center justify-center gap-1.5 py-2.5 bg-white/85 border-t border-orange-100">
                                @foreach($galleryPhotos as $idx => $url)
                                    <button
                                        type="button"
                                        data-photo-index="{{ $idx }}"
                                        aria-label="Lihat foto {{ $idx + 1 }}"
                                        class="detail-photo-dot h-2 rounded-full transition-all cursor-pointer {{ $idx === 0 ? 'w-5 bg-orange-500' : 'w-2 bg-orange-200 hover:bg-orange-300' }}"
                                    ></button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if($galleryPhotos->count() > 1)
                        <div id="detailPhotoThumbs" class="mt-3 flex items-center gap-2.5 overflow-x-auto pb-1">
                            @foreach($galleryPhotos as $idx => $url)
                                <button
                                    type="button"
                                    data-photo-index="{{ $idx }}"
                                    aria-label="Thumbnail foto {{ $idx + 1 }}"
                                    class="detail-photo-thumb w-16 h-16 rounded-xl overflow-hidden border-2 shrink-0 transition-all cursor-pointer {{ $idx === 0 ? 'border-orange-500 ring-2 ring-orange-200' : 'border-orange-100 opacity-75 hover:opacity-100' }}"
                                >
                                    <img src="{{ $url }}" alt="{{ $product->nama_barang }} {{ $idx + 1 }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="bg-orange-50/40 rounded-2xl border border-orange-100 h-72 flex items-center justify-center text-sm text-gray-400">
                        Tidak ada foto produk
                    </div>
                @endif
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

@if($galleryPhotos->count() > 1)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const photos = @json($galleryPhotos->values());
        const mainPhoto = document.getElementById('detailMainPhoto');
        const counterEl = document.getElementById('detailPhotoCounter');
        const prevBtn = document.getElementById('detailPrevPhotoBtn');
        const nextBtn = document.getElementById('detailNextPhotoBtn');
        const dots = Array.from(document.querySelectorAll('.detail-photo-dot'));
        const thumbs = Array.from(document.querySelectorAll('.detail-photo-thumb'));

        if (!mainPhoto || !photos.length) return;

        let activeIndex = 0;

        function renderSlide(nextIdx) {
            activeIndex = (nextIdx + photos.length) % photos.length;
            mainPhoto.src = photos[activeIndex];

            if (counterEl) {
                counterEl.textContent = `${activeIndex + 1} / ${photos.length}`;
            }

            dots.forEach((dot, idx) => {
                dot.classList.remove('w-5', 'bg-orange-500', 'w-2', 'bg-orange-200', 'hover:bg-orange-300');
                if (idx === activeIndex) {
                    dot.classList.add('w-5', 'bg-orange-500');
                } else {
                    dot.classList.add('w-2', 'bg-orange-200', 'hover:bg-orange-300');
                }
            });

            thumbs.forEach((thumb, idx) => {
                thumb.classList.remove('border-orange-500', 'ring-2', 'ring-orange-200', 'border-orange-100', 'opacity-75', 'hover:opacity-100');
                if (idx === activeIndex) {
                    thumb.classList.add('border-orange-500', 'ring-2', 'ring-orange-200');
                } else {
                    thumb.classList.add('border-orange-100', 'opacity-75', 'hover:opacity-100');
                }
            });
        }

        prevBtn?.addEventListener('click', () => renderSlide(activeIndex - 1));
        nextBtn?.addEventListener('click', () => renderSlide(activeIndex + 1));

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                renderSlide(Number(dot.dataset.photoIndex));
            });
        });

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', () => {
                renderSlide(Number(thumb.dataset.photoIndex));
            });
        });
    });
</script>
@endif
@endsection