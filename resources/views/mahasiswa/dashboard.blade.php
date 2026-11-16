@extends('layouts.app')

@section('title', 'Katalog Barang - Jualin PCR')

@section('content')
    <!-- Isi Main Content Anda di sini -->
    <h1 class="text-2xl font-bold mb-4">Katalog Barang Elektronik</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Loop Produk -->
    </div>
@endsection

@push('scripts')
    <script>
        // Jika butuh kode JavaScript khusus hanya untuk halaman dashboard ini
        console.log('Halaman dashboard siap!');
    </script>
@endpush