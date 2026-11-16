<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Mahasiswa\TokoController;

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
});

// Route Guest (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');