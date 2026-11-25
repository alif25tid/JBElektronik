<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $myId = Auth::id();

        $messages = Message::with(['sender', 'receiver', 'product'])
            ->where('sender_id', $myId)
            ->orWhere('receiver_id', $myId)
            ->orderByDesc('created_at')
            ->get();

        $conversations = $messages->groupBy(function ($msg) use ($myId) {
            return $msg->sender_id === $myId ? $msg->receiver_id : $msg->sender_id;
        })->map(function ($thread) use ($myId) {
            $latestMessage = $thread->first();
            $partner = $latestMessage->sender_id === $myId
                ? $latestMessage->receiver
                : $latestMessage->sender;

            $unreadCount = $thread->where('sender_id', $partner->id)
                ->where('receiver_id', $myId)
                ->whereNull('read_at')
                ->count();

            return [
                'partner' => $partner,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadCount,
            ];
        })->values();

        return view('mahasiswa.chat.index', compact('conversations'));
    }

    public function show(Request $request, User $user)
    {
        $myId = Auth::id();

        if ($user->id === $myId) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda tidak dapat mengirim pesan ke akun sendiri.');
        }

        $product = null;
        if ($request->filled('product_id')) {
            $product = Product::with('category')->find($request->product_id);
        }

        // Tandai pesan masuk dari lawan bicara sebagai sudah dibaca
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $myId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::with('product')
            ->where(function ($q) use ($myId, $user) {
                $q->where('sender_id', $myId)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($myId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $myId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('mahasiswa.chat.show', compact('user', 'messages', 'product'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'body' => 'nullable|string|max:2000',
            'product_id' => 'nullable|exists:products,id',
        ]);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengirim pesan ke akun sendiri.');
        }

        $isTargetAdmin = ($user->role ?? '') === 'admin'
            || (method_exists($user, 'isAdmin') && $user->isAdmin());

        if ($isTargetAdmin) {
            return back()->with('error', 'Pesan dari admin tidak dapat dibalas.');
        }

        if (!$request->filled('body') && !$request->filled('product_id')) {
            return back()->with('error', 'Pesan tidak boleh kosong.');
        }

        $body = $request->body;
        if ($request->filled('product_id') && blank($body)) {
            $product = Product::find($request->product_id);
            $body = 'Halo kak, apakah barang "' . ($product->nama_barang ?? 'ini') . '" masih tersedia?';
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'product_id' => $request->product_id,
            'body' => $body,
        ]);

        return redirect()->route('mahasiswa.chat.show', $user->id);
    }
}