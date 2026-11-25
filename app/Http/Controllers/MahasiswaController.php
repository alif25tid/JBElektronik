<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\UserWarning;

class MahasiswaController extends Controller
{
    // 1. Dashboard Mode Pembeli (Menampilkan Katalog Produk & Kategori)
    public function index(Request $request)
    {
        $categoryId = $request->get('category');
        $search = $request->get('search');

        // Ambil produk yang tersedia dengan filter pencarian / kategori / penjual
        $products = Product::with(['category', 'user'])
            ->where('status', 'tersedia')
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($catQuery) use ($search) {
                            $catQuery->where('nama_kategori', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->inRandomOrder()
            ->get();

        $categories = Category::all();

        return view('mahasiswa.dashboard', compact('products', 'categories'));
    }

    // 2. Dashboard Mode Penjual (Kelola Barang Saya)
    public function tokoSaya()
    {
        // Kecualikan barang berstatus 'dihapus' agar tidak tampil di daftar aktif penjual
        $myProducts = Product::where('user_id', Auth::id())
            ->where('status', '!=', 'dihapus')
            ->latest()
            ->get();

        $categories = Category::all();

        return view('mahasiswa.toko', compact('myProducts', 'categories'));
    }

    public function acknowledgeWarning(UserWarning $warning)
    {
        abort_if($warning->user_id !== Auth::id(), 403);

        $warning->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back();
    }
}