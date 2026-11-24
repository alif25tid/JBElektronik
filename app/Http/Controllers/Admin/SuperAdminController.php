<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserWarning;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

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

    public function warnUser(Request $request, User $user)
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'reason.required' => 'Alasan pelanggaran wajib diisi.',
        ]);

        UserWarning::create([
            'user_id'  => $user->id,
            'admin_id' => Auth::id(),
            'reason'   => $request->reason,
            'is_read'  => false,
        ]);

        return back()->with('success', "Peringatan berhasil dikirim ke {$user->name}.");
    }

    public function sendAdminMessage(Request $request, User $user)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ], [
            'body.required' => 'Isi pesan wajib diisi.',
        ]);

        Message::create([
            'sender_id'     => Auth::id(),
            'receiver_id'   => $user->id,
            'body'          => $request->body,
            'is_from_admin' => true,
        ]);

        return back()->with('success', "Pesan dari admin berhasil dikirim ke {$user->name}.");
    }
}