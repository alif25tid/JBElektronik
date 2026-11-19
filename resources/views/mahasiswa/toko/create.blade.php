@extends('layouts.app')

@section('title', 'Tambah Barang - Jualin PCR')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Dagangan</h1>
            <p class="text-sm text-gray-500">Isi detail barang elektronik yang ingin Anda tawarkan ke sesama mahasiswa.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc list-inside">
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
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: Mouse Logitech G102 Bekas">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                            @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga') }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: 150000">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Barang</label>
                <textarea name="deskripsi" rows="4" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Jelaskan kondisi fisik, kelengkapan, dan minus barang...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Serah Terima</label>
                    <select name="metode_serah_terima" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Pilih Metode Serah Terima</option>
                        <option value="COD di Kampus">Atur Lokasi</option>
                        <option value="Antar ke Kost">Antar ke Kost</option>
                        <option value="Jemput di Kost">Jemput di Kost</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titik Lokasi Pertemuan</label>
                    <input type="text" name="titik_lokasi" value="{{ old('titik_lokasi') }}" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: Gedung Utama / Kantin PCR">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang (Maks 2MB)</label>
                <input type="file" name="foto" accept="image/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Video Review Barang (Opsional, Maks 10MB)</label>
                <input type="file" name="video_review" accept="video/mp4,video/quicktime,video/mkv" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('mahasiswa.toko.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">Simpan Barang</button>
            </div>
        </form>
    </div>
</div>
@endsection