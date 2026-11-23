<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->withCount('products')
            ->latest()
            ->get();

        $pendingProducts = Product::with(['user', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $allProducts = Product::with(['user', 'category'])
            ->latest()
            ->get();

        return view('admin.dashboard', compact('users', 'pendingProducts', 'allProducts'));
    }

    public function approve(Product $product)
    {
        $product->update([
            'status' => 'tersedia',
            'rejection_reason' => null,
            'moderated_at' => now(),
        ]);

        return back()->with('success', 'Barang "' . $product->nama_barang . '" telah dikonfirmasi dan tampil di katalog.');
    }

    public function reject(Request $request, Product $product)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'moderated_at' => now(),
        ]);

        return back()->with('success', 'Barang "' . $product->nama_barang . '" ditolak dengan alasan yang dicatat.');
    }
}