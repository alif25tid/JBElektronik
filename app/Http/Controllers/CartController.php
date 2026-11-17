<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan daftar isi keranjang
    public function index()
    {
        // Ambil keranjang milik user, dan pastikan produknya masih tersedia
        $carts = Cart::with('product.category')
                    ->where('user_id', Auth::id())
                    ->whereHas('product', function($q) {
                        $q->where('status', 'tersedia');
                    })
                    ->get();

        return view('mahasiswa.cart.index', compact('carts'));
    }

    // Memasukkan barang ke keranjang
    public function store(Request $request, $productId)
    {
        // Cek apakah barang sudah ada di keranjang user
        $exists = Cart::where('user_id', Auth::id())->where('product_id', $productId)->exists();

        if ($exists) {
            // Jika sudah ada, kembalikan dengan pesan peringatan/notif
            return back()->with('warning', 'Barang tersebut sudah ada di dalam keranjang Anda!');
        }

        // Jika belum ada, simpan ke keranjang
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

    // Menghapus barang dari keranjang
    public function destroy($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'Barang dihapus dari keranjang.');
    }
}