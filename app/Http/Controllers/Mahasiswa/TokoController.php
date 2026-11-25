<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TokoController extends Controller
{
    // Menampilkan daftar produk yang dijual oleh mahasiswa yang sedang login (kecuali yang berstatus 'dihapus')
    public function index()
    {
        $products = Product::where('user_id', Auth::id())
            ->where('status', '!=', 'dihapus')
            ->with('category')
            ->latest()
            ->get();

        return view('mahasiswa.toko.index', compact('products'));
    }

    // Menampilkan form tambah produk
    public function create()
    {
        $categories = Category::all();
        return view('mahasiswa.toko.create', compact('categories'));
    }

    // Menyimpan produk baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'video_review' => 'nullable|mimes:mp4,mov,avi,mkv|max:10240',
            'metode_serah_terima' => 'required',
            'titik_lokasi' => 'required',
        ]);

        $fotoPath = $request->file('foto')->store('product-photos', 'public');

        $videoPath = '-';
        if ($request->hasFile('video_review')) {
            $videoPath = $request->file('video_review')->store('product-videos', 'public');
        }

        Product::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'video_review' => $videoPath,
            'metode_serah_terima' => $request->metode_serah_terima,
            'titik_lokasi' => $request->titik_lokasi,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.toko.index')->with('success', 'Barang dagangan berhasil diunggah dan sedang menunggu konfirmasi Super Admin!');
    }

    // Menandai produk sebagai 'dihapus' agar tetap tersimpan di database untuk akumulasi
    public function destroy($id)
    {
        $product = Product::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }

        if ($product->video_review && $product->video_review !== '-' && Storage::disk('public')->exists($product->video_review)) {
            Storage::disk('public')->delete($product->video_review);
        }

        $product->update([
            'status' => 'dihapus',
        ]);

        return redirect()->route('mahasiswa.toko.index')->with('success', 'Barang dagangan berhasil dihapus.');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'user'])->findOrFail($id);

        return view('mahasiswa.toko.show', compact('product'));
    }

    // Menampilkan form edit produk
    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())
            ->where('status', '!=', 'dihapus')
            ->findOrFail($id);
        $categories = Category::all();

        return view('mahasiswa.toko.edit', compact('product', 'categories'));
    }

    // Memperbarui data produk di database
    public function update(Request $request, $id)
    {
        $product = Product::where('user_id', Auth::id())
            ->where('status', '!=', 'dihapus')
            ->findOrFail($id);

        $request->validate([
            'category_id' => 'required',
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video_review' => 'nullable|mimes:mp4,mov,avi,mkv|max:10240',
            'metode_serah_terima' => 'required',
            'titik_lokasi' => 'required',
            'status' => 'required|in:tersedia,terjual',
        ]);

        if ($request->hasFile('foto')) {
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $product->foto = $request->file('foto')->store('product-photos', 'public');
        }

        if ($request->hasFile('video_review')) {
            if ($product->video_review && $product->video_review != '-' && Storage::disk('public')->exists($product->video_review)) {
                Storage::disk('public')->delete($product->video_review);
            }
            $product->video_review = $request->file('video_review')->store('product-videos', 'public');
        }

        $nextStatus = $product->status === 'rejected' ? 'pending' : $request->status;
        $nextRejectionReason = $product->status === 'rejected' ? null : $product->rejection_reason;

        $product->update([
            'category_id' => $request->category_id,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'foto' => $product->foto,
            'video_review' => $product->video_review,
            'metode_serah_terima' => $request->metode_serah_terima,
            'titik_lokasi' => $request->titik_lokasi,
            'status' => $nextStatus,
            'rejection_reason' => $nextRejectionReason,
        ]);

        return redirect()->route('mahasiswa.toko.index')->with('success', 'Barang dagangan berhasil diperbarui!');
    }
}