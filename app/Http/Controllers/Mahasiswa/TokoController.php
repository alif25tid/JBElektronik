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
    public function index()
    {
        $products = Product::where('user_id', Auth::id())
            ->where('status', '!=', 'dihapus')
            ->with(['category', 'photos'])
            ->latest()
            ->get();

        return view('mahasiswa.toko.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('mahasiswa.toko.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'required',
            'fotos' => 'required|array|min:1|max:3',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_review' => 'nullable|mimes:mp4,mov,avi,mkv|max:10240',
            'metode_serah_terima' => 'required',
            'titik_lokasi' => 'required',
        ]);

        $videoPath = '-';
        if ($request->hasFile('video_review')) {
            $videoPath = $request->file('video_review')->store('product-videos', 'public');
        }

        $product = Product::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'foto' => null,
            'video_review' => $videoPath,
            'metode_serah_terima' => $request->metode_serah_terima,
            'titik_lokasi' => $request->titik_lokasi,
            'status' => 'pending',
        ]);

        $firstPhotoPath = null;
        foreach ($request->file('fotos', []) as $index => $file) {
            $path = $file->store('product-photos', 'public');
            if ($index === 0) {
                $firstPhotoPath = $path;
            }

            $product->photos()->create([
                'path' => $path,
                'sort_order' => $index,
            ]);
        }

        if ($firstPhotoPath) {
            $product->update(['foto' => $firstPhotoPath]);
        }

        return redirect()->route('mahasiswa.toko.index')->with('success', 'Barang dagangan berhasil diunggah dan sedang menunggu konfirmasi Super Admin!');
    }

    public function destroy($id)
    {
        $product = Product::with('photos')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        foreach ($product->photos as $photo) {
            if ($photo->path && Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }
        }

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
        $product = Product::with(['category', 'user', 'photos'])->findOrFail($id);

        return view('mahasiswa.toko.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('photos')
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'dihapus')
            ->findOrFail($id);
        $categories = Category::all();

        return view('mahasiswa.toko.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('photos')
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'dihapus')
            ->findOrFail($id);

        $request->validate([
            'category_id' => 'required',
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'required',
            'fotos' => 'nullable|array|min:1|max:3',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_review' => 'nullable|mimes:mp4,mov,avi,mkv|max:10240',
            'metode_serah_terima' => 'required',
            'titik_lokasi' => 'required',
            'status' => 'required|in:tersedia,terjual',
        ]);

        if ($request->hasFile('fotos')) {
            foreach ($product->photos as $oldPhoto) {
                if ($oldPhoto->path && Storage::disk('public')->exists($oldPhoto->path)) {
                    Storage::disk('public')->delete($oldPhoto->path);
                }
            }
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $product->photos()->delete();

            $firstPhotoPath = null;
            foreach ($request->file('fotos', []) as $index => $file) {
                $path = $file->store('product-photos', 'public');
                if ($index === 0) {
                    $firstPhotoPath = $path;
                }

                $product->photos()->create([
                    'path' => $path,
                    'sort_order' => $index,
                ]);
            }

            $product->foto = $firstPhotoPath;
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