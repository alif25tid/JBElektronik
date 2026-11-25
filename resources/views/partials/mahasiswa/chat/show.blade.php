@extends('layouts.app')

@section('title', 'Chat dengan ' . $user->name . ' - Jualin PCR')

@section('content')
<div class="max-w-3xl mx-auto h-[560px] bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
    <!-- Header Chat (Fixed) -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 shrink-0">
        <div>
            <h2 class="text-base font-semibold text-gray-900">{{ $user->name }}</h2>
            <p class="text-xs text-gray-500">
                {{ (($user->role ?? '') === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin())) ? 'Admin Resmi • Jualin PCR' : 'Mahasiswa • Jualin PCR' }}
            </p>
        </div>
        <a href="{{ route('mahasiswa.chat.index') }}" class="text-xs font-medium text-orange-600 hover:underline">
            Kembali
        </a>
    </div>

    <!-- Riwayat Pesan (Scrollable & Mengisi Sisa Tinggi) -->
    <div id="chat-messages-container" class="flex-1 p-6 space-y-4 overflow-y-auto bg-gray-50/40">
        @forelse($messages as $msg)
            @php $isMe = $msg->sender_id === auth()->id(); @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-md space-y-2">
                    {{-- Jika pesan ini terhubung ke produk tertentu, tampilkan kartu produk --}}
                    @if($msg->product)
                        <div class="p-3 rounded-xl border border-orange-200 bg-orange-50/70 flex items-center gap-3">
                            @if(!empty($msg->product->foto))
                                <img src="{{ asset('storage/' . $msg->product->foto) }}"
                                     alt="{{ $msg->product->nama_barang }}"
                                     class="w-12 h-12 rounded-lg object-cover bg-white border border-gray-200 shrink-0">
                            @endif
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-orange-600">Produk Ditanyakan</p>
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $msg->product->nama_barang }}</p>
                                @if(isset($msg->product->harga))
                                    <p class="text-xs font-medium text-gray-700">Rp {{ number_format($msg->product->harga, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($msg->body)
                        <div class="px-4 py-2.5 rounded-2xl text-sm break-words {{ $isMe ? 'bg-orange-600 text-white rounded-br-sm' : 'bg-white border border-gray-200 text-gray-800 rounded-bl-sm' }}">
                            {{ $msg->body }}
                        </div>
                    @endif

                    <p class="text-[10px] text-gray-400 {{ $isMe ? 'text-right' : 'text-left' }}">
                        {{ $msg->created_at->format('H:i') }}
                    </p>
                </div>
            </div>
        @empty
            <div class="h-full flex items-center justify-center">
                <p class="text-center text-xs text-gray-400">
                    Mulai percakapan dengan {{ $user->name }}.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Preview Produk Otomatis Saat Masuk dari Detail Produk (Fixed di Atas Form) -->
    @if($product)
        <div class="px-6 py-3 bg-orange-50 border-t border-orange-200 flex items-center justify-between gap-3 shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                @if(!empty($product->foto))
                    <img src="{{ asset('storage/' . $product->foto) }}"
                         alt="{{ $product->nama_barang }}"
                         class="w-11 h-11 rounded-lg object-cover border border-orange-200 bg-white shrink-0">
                @endif
                <div class="min-w-0">
                    <span class="text-[11px] font-semibold text-orange-700 block">Otomatis Terlampir</span>
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $product->nama_barang }}</p>
                    @if(isset($product->harga))
                        <p class="text-xs text-gray-700">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>
            <span class="text-xs text-gray-500 shrink-0">Siap dikirim</span>
        </div>
    @endif

    <!-- Form Kirim Pesan (Fixed di Bawah) -->
    <div class="shrink-0">
        @if(($user->role ?? '') === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin()))
            <div class="p-4 border-t border-amber-200 bg-amber-50 text-center text-xs font-medium text-amber-800">
                Pesan resmi dari Admin bersifat satu arah dan tidak dapat dibalas.
            </div>
        @else
            <form action="{{ route('mahasiswa.chat.store', $user->id) }}" method="POST" class="p-4 border-t border-gray-200 bg-white flex gap-2">
                @csrf
                @if($product)
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                @endif

                <input
                    type="text"
                    name="body"
                    value="{{ old('body', $product ? 'Halo kak, apakah ' . $product->nama_barang . ' masih tersedia?' : '') }}"
                    placeholder="Tulis pesan..."
                    class="flex-1 px-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500"
                    required
                >

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-xl transition-colors cursor-pointer"
                >
                    Kirim
                </button>
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatBox = document.getElementById('chat-messages-container');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
</script>
@endpush