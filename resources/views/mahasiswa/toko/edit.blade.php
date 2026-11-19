@extends('layouts.app')

@section('title', 'Edit Barang - Jualin PCR')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Barang Dagangan</h1>

        <form action="{{ route('mahasiswa.toko.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category_id" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
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
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $product->nama_barang) }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $product->harga) }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi & Kondisi Barang</label>
                <textarea name="deskripsi" rows="4" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">{{ old('deskripsi', $product->deskripsi) }}</textarea>
            </div>

            <!-- Foto Saat Ini & Ganti Foto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang (Opsional)</label>
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $product->foto) }}" alt="" class="w-20 h-20 object-cover rounded-lg border">
                </div>
                <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <!-- Video Review -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Video Review Baru (Opsional)</label>
                <input type="file" name="video_review" accept="video/mp4,video/quicktime,video/mkv" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <!-- Metode Serah Terima -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Serah Terima</label>
                <select name="metode_serah_terima" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
                    <option value="COD di Kampus" {{ $product->metode_serah_terima == 'COD di Kampus' ? 'selected' : '' }}>COD di Kampus</option>
                    <option value="Antar ke Kost" {{ $product->metode_serah_terima == 'Antar ke Kost' ? 'selected' : '' }}>Antar ke Kost</option>
                    <option value="Jemput di Kost" {{ $product->metode_serah_terima == 'Jemput di Kost' ? 'selected' : '' }}>Jemput di Kost</option>
                </select>
            </div>

            <!-- Titik Lokasi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titik Lokasi Pertemuan</label>
                <input type="text" name="titik_lokasi" value="{{ old('titik_lokasi', $product->titik_lokasi) }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
            </div>

            <!-- Status Barang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Barang</label>
                <select name="status" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
                    <option value="tersedia" {{ $product->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="terjual" {{ $product->status == 'terjual' ? 'selected' : '' }}>Terjual</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('mahasiswa.toko.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-xl shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection