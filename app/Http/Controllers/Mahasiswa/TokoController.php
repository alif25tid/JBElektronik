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
    // Menampilkan daftar produk yang dijual oleh mahasiswa yang sedang login
    public function index()
    {
        $products = Product::where('user_id', Auth::id())
                    ->with('category') // Tambahkan ini agar relasi kategori terbaca
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
                'video_review' => 'nullable|mimes:mp4,mov,avi,mkv|max:10240', // Maksimal sekitar 10MB
                'metode_serah_terima' => 'required',
                'titik_lokasi' => 'required',
            ]);

            // Simpan foto
            $fotoPath = $request->file('foto')->store('product-photos', 'public');

            // Cek apakah ada file video yang diunggah
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
                'video_review' => $videoPath, // Menyimpan path file video atau '-' jika kosong
                'metode_serah_terima' => $request->metode_serah_terima,
                'titik_lokasi' => $request->titik_lokasi,
                'status' => 'tersedia',
            ]);

            return redirect()->route('mahasiswa.toko.index')->with('success', 'Barang dagangan beserta videonya berhasil ditambahkan!');
        }

    // Menghapus produk
    public function destroy($id)
    {
        $product = Product::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }
        
        $product->delete();

        return redirect()->route('mahasiswa.toko.index')->with('success', 'Barang dagangan berhasil dihapus.');
    }
    
   public function show($id)
    {
        // Hapus filter user_id agar akun lain tidak error 404 Not Found
        $product = Product::with(['category', 'user'])->findOrFail($id);
        
        return view('mahasiswa.toko.show', compact('product'));
    }
}
