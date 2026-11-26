@extends('layouts.app')

@section('title', 'Edit Barang - Jualin PCR')

@section('content')
@php
    $existingPhotos = collect();
    if ($product->relationLoaded('photos') && $product->photos->isNotEmpty()) {
        $existingPhotos = $product->photos->map(fn ($p) => asset('storage/' . $p->path));
    } elseif ($product->foto) {
        $existingPhotos = collect([asset('storage/' . $product->foto)]);
    }
@endphp

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white border border-orange-100 rounded-2xl shadow-sm p-6 sm:p-8">
        <h1 class="text-2xl font-extrabold bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 bg-clip-text text-transparent mb-6">
            Edit Barang Dagangan
        </h1>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mahasiswa.toko.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select
                    name="category_id"
                    required
                    class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                >
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nama Barang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                <input
                    type="text"
                    name="nama_barang"
                    value="{{ old('nama_barang', $product->nama_barang) }}"
                    required
                    class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                >
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input
                    type="number"
                    name="harga"
                    value="{{ old('harga', $product->harga) }}"
                    required
                    class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                >
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi &amp; Kondisi Barang</label>
                <textarea
                    name="deskripsi"
                    rows="4"
                    required
                    class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                >{{ old('deskripsi', $product->deskripsi) }}</textarea>
            </div>

            <!-- Foto Saat Ini & Ganti Foto (Maksimal 3) -->
            <div>
                <label for="editFotosInput" class="block text-sm font-medium text-gray-700 mb-1">
                    Foto Barang <span class="text-xs font-normal text-gray-500">(Opsional &middot; Maksimal 3 foto, maks 2MB per foto)</span>
                </label>
                @if($existingPhotos->isNotEmpty())
                    <div class="mb-2.5 flex flex-wrap gap-2.5">
                        @foreach($existingPhotos as $photoUrl)
                            <img src="{{ $photoUrl }}" alt="Foto Barang" class="w-20 h-20 object-cover rounded-xl border border-orange-100 shadow-xs">
                        @endforeach
                    </div>
                @endif
                <input
                    type="file"
                    id="editFotosInput"
                    name="fotos[]"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                    multiple
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all cursor-pointer"
                >
                <p id="editFotosError" class="hidden mt-1.5 text-xs font-semibold text-red-600">
                    Maksimal hanya boleh melampirkan 3 foto. Silakan pilih ulang maksimal 3 foto.
                </p>
                <div id="editFotosPreview" class="mt-3 flex flex-wrap gap-2.5"></div>
            </div>

            <!-- Video Review -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Video Review Baru (Opsional)</label>
                <input
                    type="file"
                    name="video_review"
                    accept="video/mp4,video/quicktime,video/mkv"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all cursor-pointer"
                >
            </div>

            <!-- Metode Serah Terima -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Serah Terima</label>
                <select
                    name="metode_serah_terima"
                    required
                    class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                >
                    <option value="COD di Kampus" {{ $product->metode_serah_terima == 'COD di Kampus' ? 'selected' : '' }}>COD di Kampus</option>
                    <option value="Antar ke Kost" {{ $product->metode_serah_terima == 'Antar ke Kost' ? 'selected' : '' }}>Antar ke Kost</option>
                    <option value="Jemput di Kost" {{ $product->metode_serah_terima == 'Jemput di Kost' ? 'selected' : '' }}>Jemput di Kost</option>
                </select>
            </div>

            <!-- Titik Lokasi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titik Lokasi Pertemuan</label>
                <input
                    type="text"
                    name="titik_lokasi"
                    value="{{ old('titik_lokasi', $product->titik_lokasi) }}"
                    required
                    class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                >
            </div>

            <!-- Status Barang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Barang</label>
                <select
                    name="status"
                    required
                    class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                >
                    <option value="tersedia" {{ $product->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="terjual" {{ $product->status == 'terjual' ? 'selected' : '' }}>Terjual</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end items-center gap-3 pt-5 border-t border-orange-100">
                <a
                    href="{{ route('mahasiswa.toko.index') }}"
                    class="px-5 py-2.5 border border-orange-200 text-orange-600 text-sm font-medium rounded-xl hover:bg-orange-50 transition-colors"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-orange-400 via-orange-500 to-amber-600 hover:from-amber-400 hover:via-orange-500 hover:to-orange-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all cursor-pointer"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('editFotosInput');
        const errorEl = document.getElementById('editFotosError');
        const previewEl = document.getElementById('editFotosPreview');
        const maxFiles = 3;

        if (!input || !previewEl) return;

        input.addEventListener('change', function () {
            previewEl.innerHTML = '';

            if (this.files.length > maxFiles) {
                if (errorEl) errorEl.classList.remove('hidden');
                this.value = '';
                return;
            }

            if (errorEl) errorEl.classList.add('hidden');

            Array.from(this.files).forEach((file) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;
                    img.className = 'w-20 h-20 object-cover rounded-xl border border-orange-200 shadow-2xs';
                    previewEl.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    });
</script>
@endsection