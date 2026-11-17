<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Mahasiswa\TokoController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

// Route khusus Mahasiswa yang sudah login
Route::middleware(['auth'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    
    // Dashboard / Katalog Produk
    Route::get('/dashboard', [MahasiswaController::class, 'index'])->name('dashboard');

    // Kelola Toko Saya (Menggunakan TokoController)
    Route::get('/toko-saya', [TokoController::class, 'index'])->name('toko.index');
    Route::get('/toko-saya/tambah', [TokoController::class, 'create'])->name('toko.create');
    Route::post('/toko-saya', [TokoController::class, 'store'])->name('toko.store');
    Route::delete('/toko-saya/{id}', [TokoController::class, 'destroy'])->name('toko.destroy');
    Route::get('/toko-saya/{id}', [TokoController::class, 'show'])->name('toko.show');
    Route::get('/toko-saya/{id}/edit', [TokoController::class, 'edit'])->name('toko.edit');
    Route::put('/toko-saya/{id}', [TokoController::class, 'update'])->name('toko.update');
});

// Route Guest (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware(['auth'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Rute keranjang
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{id}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/keranjang/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');