<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->group(function () {
    // Dashboard / Katalog Produk
    Route::get('/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
    
    // Kelola Toko Saya (Penjual)
    Route::get('/toko-saya', [MahasiswaController::class, 'tokoSaya'])->name('mahasiswa.toko');
    
    // Simpan Produk Baru
    Route::post('/produk/simpan', [ProductController::class, 'store'])->name('produk.simpan');
});