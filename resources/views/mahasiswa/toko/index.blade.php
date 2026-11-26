@extends('layouts.app')

@section('title', 'Toko Saya - Jualin PCR')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 bg-clip-text text-transparent">
                Kelola Toko Saya
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar barang elektronik yang sedang Anda iklankan di kampus.</p>
        </div>
        <a href="{{ route('mahasiswa.toko.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-orange-400 via-orange-500 to-amber-600 hover:from-amber-400 hover:via-orange-500 hover:to-orange-600 text-white font-bold text-sm rounded-full shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300 self-start sm:self-auto">
            + Tambah Barang Baru
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-orange-100/90 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-orange-100/80">
                <thead class="bg-gradient-to-r from-orange-400 via-orange-500 to-amber-600 text-white">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-orange-50">
                    @forelse($products as $product)
                        <tr class="hover:bg-orange-50/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-start">
                                    <div class="h-11 w-11 flex-shrink-0 bg-orange-50 border border-orange-100 rounded-xl overflow-hidden">
                                        @if($product->foto)
                                            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_barang }}" class="w-11 h-11 object-cover">
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $product->nama_barang }}</div>
                                        <div class="text-xs text-gray-500">{{ $product->titik_lokasi }}</div>

                                        @if($product->status === 'pending')
                                            <div class="mt-2 p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 max-w-xs">
                                                Menunggu konfirmasi Super Admin. Barang belum tampil di katalog.
                                            </div>
                                        @elseif($product->status === 'rejected')
                                            <div class="mt-2 p-2.5 rounded-xl bg-red-50 border border-red-200 text-xs text-red-800 max-w-xs space-y-1">
                                                <p class="font-bold">Peringatan: Barang ini ditolak oleh Super Admin dan tidak tampil di katalog.</p>
                                                @if($product->rejection_reason)
                                                    <p>Alasan: {{ $product->rejection_reason }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <span class="px-2.5 py-1 text-xs font-medium bg-orange-50 text-orange-700 rounded-full border border-orange-100">
                                    {{ $product->category->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 bg-clip-text text-transparent">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(in_array($product->status, ['approved', 'tersedia']))
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                        Disetujui
                                    </span>
                                @elseif($product->status === 'pending')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                        Menunggu Konfirmasi
                                    </span>
                                @elseif($product->status === 'rejected')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('mahasiswa.toko.edit', $product->id) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-600 hover:bg-orange-100 border border-orange-200/80 transition-colors">
                                        Edit
                                    </a>
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('mahasiswa.toko.destroy', $product->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 border border-red-200/80 transition-colors cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                Belum ada barang yang Anda jual. Silakan klik tombol <strong class="text-orange-600">+ Tambah Barang Baru</strong> di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection