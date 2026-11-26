@extends('layouts.app')

@section('title', 'Tambah Barang - Jualin PCR')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white border border-orange-100 rounded-2xl shadow-sm p-6 sm:p-8">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 bg-clip-text text-transparent">
                Tambah Barang Dagangan
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Isi detail barang elektronik yang ingin Anda tawarkan ke sesama mahasiswa.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mahasiswa.toko.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                <input
                    type="text"
                    name="nama_barang"
                    value="{{ old('nama_barang') }}"
                    required
                    class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                    placeholder="Contoh: Mouse Logitech G102 Bekas"
                >
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select
                        name="category_id"
                        required
                        class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                    >
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                    <input
                        type="number"
                        name="harga"
                        value="{{ old('harga') }}"
                        required
                        class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                        placeholder="Contoh: 150000"
                    >
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Barang</label>
                <textarea
                    name="deskripsi"
                    rows="4"
                    required
                    class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                    placeholder="Jelaskan kondisi fisik, kelengkapan, dan minus barang..."
                >{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Serah Terima</label>
                    <select
                        name="metode_serah_terima"
                        required
                        class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                    >
                        <option value="">Pilih Metode Serah Terima</option>
                        <option value="COD di Kampus" {{ old('metode_serah_terima') == 'COD di Kampus' ? 'selected' : '' }}>Atur Lokasi</option>
                        <option value="Antar ke Kost" {{ old('metode_serah_terima') == 'Antar ke Kost' ? 'selected' : '' }}>Antar ke Kost</option>
                        <option value="Jemput di Kost" {{ old('metode_serah_terima') == 'Jemput di Kost' ? 'selected' : '' }}>Jemput di Kost</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titik Lokasi Pertemuan</label>
                    <input
                        type="text"
                        name="titik_lokasi"
                        value="{{ old('titik_lokasi') }}"
                        required
                        class="w-full rounded-xl border-orange-200 border px-3.5 py-2 text-sm text-gray-800 bg-orange-50/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                        placeholder="Contoh: Gedung Utama / Kantin PCR"
                    >
                </div>
            </div>

            <div>
                <label for="createFotosInput" class="block text-sm font-medium text-gray-700 mb-1">
                    Foto Barang <span class="text-xs font-normal text-gray-500">(Minimal 1, maksimal 3 foto &middot; Maks 2MB per foto)</span>
                </label>
                <input
                    type="file"
                    id="createFotosInput"
                    name="fotos[]"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                    multiple
                    required
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all cursor-pointer"
                >
                <p id="createFotosError" class="hidden mt-1.5 text-xs font-semibold text-red-600">
                    Maksimal hanya boleh melampirkan 3 foto. Silakan pilih ulang maksimal 3 foto.
                </p>
                <div id="createFotosPreview" class="mt-3 flex flex-wrap gap-2.5"></div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Video Review Barang (Opsional, Maks 10MB)</label>
                <input
                    type="file"
                    name="video_review"
                    accept="video/mp4,video/quicktime,video/mkv"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all cursor-pointer"
                >
            </div>

            <div class="flex justify-end items-center gap-3 pt-5 border-t border-orange-100">
                <a
                    href="{{ route('mahasiswa.toko.index') }}"
                    class="px-4 py-2 border border-orange-200 text-orange-600 text-sm font-medium rounded-xl hover:bg-orange-50 transition-colors"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="px-5 py-2 bg-gradient-to-r from-orange-400 via-orange-500 to-amber-600 hover:from-amber-400 hover:via-orange-500 hover:to-orange-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all cursor-pointer"
                >
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('createFotosInput');
        const errorEl = document.getElementById('createFotosError');
        const previewEl = document.getElementById('createFotosPreview');
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