@php
    $isAdminPartner = ($user->role ?? '') === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin());
    $displayPartnerName = $isAdminPartner ? 'ADMIN JUALIN' : $user->name;

    $groupedMessages = $messages->groupBy(function ($msg) {
        return optional($msg->created_at)->format('Y-m-d') ?? 'tanpa-tanggal';
    });

    $todayKey = now()->format('Y-m-d');
    $yesterdayKey = now()->subDay()->format('Y-m-d');
    $bulanIndo = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
    ];
@endphp

@extends('layouts.app')

@section('title', 'Chat dengan ' . $displayPartnerName . ' - Jualin PCR')

@section('content')
<div class="max-w-3xl mx-auto h-[560px] bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
    <!-- Header Chat (Fixed) -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 shrink-0">
        <div>
            <div class="flex items-center gap-1.5">
                <h2 class="text-base font-bold text-gray-900 {{ $isAdminPartner ? 'tracking-wide' : '' }}">
                    {{ $displayPartnerName }}
                </h2>
                @if($isAdminPartner)
                    <span title="Akun Resmi Admin Jualin" aria-label="Akun Resmi Admin Jualin" class="inline-flex items-center justify-center shrink-0 text-orange-500">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 1.5l2.35 1.97 3.05-.37.75 2.98 2.86 1.15-.78 2.97 1.77 2.5-2.18 2.15.27 3.06-3.03.52-1.52 2.68L12 22.5l-2.35-1.97-3.05.37-.75-2.98-2.86-1.15.78-2.97-1.77-2.5 2.18-2.15-.27-3.06 3.03-.52L8.75 2l3.25-.5zm4.03 8.22a.75.75 0 00-1.06-1.06l-4.22 4.22-1.72-1.72a.75.75 0 00-1.06 1.06l2.25 2.25c.3.29.77.29 1.06 0l4.75-4.75z" />
                        </svg>
                    </span>
                @endif
            </div>
            <p class="text-xs text-gray-500">
                {{ $isAdminPartner ? 'Admin Resmi • Jualin PCR' : 'Mahasiswa • Jualin PCR' }}
            </p>
        </div>
        <a href="{{ route('mahasiswa.chat.index') }}" class="text-xs font-medium text-orange-600 hover:underline">
            Kembali
        </a>
    </div>

    <!-- Riwayat Pesan (Scrollable & Mengisi Sisa Tinggi) -->
    <div id="chat-messages-container" class="flex-1 p-6 space-y-5 overflow-y-auto bg-gray-50/40">
        @forelse($groupedMessages as $dateKey => $dayMessages)
            @php
                if ($dateKey === $todayKey) {
                    $dateLabel = 'Hari Ini';
                } elseif ($dateKey === $yesterdayKey) {
                    $dateLabel = 'Kemarin';
                } elseif ($dateKey !== 'tanpa-tanggal') {
                    $dt = \Carbon\Carbon::createFromFormat('Y-m-d', $dateKey);
                    $dateLabel = $dt->day . ' ' . ($bulanIndo[$dt->month] ?? $dt->format('M')) . ' ' . $dt->year;
                } else {
                    $dateLabel = '';
                }
            @endphp

            <div class="space-y-4">
                @if($dateLabel !== '')
                    <div class="sticky top-0 z-10 flex justify-center my-1">
                        <span class="px-3 py-1 rounded-full bg-white border border-gray-200/90 shadow-2xs text-[11px] font-medium text-gray-500">
                            {{ $dateLabel }}
                        </span>
                    </div>
                @endif

                @foreach($dayMessages as $msg)
                    @php
                        $isMe = $msg->sender_id === auth()->id();
                        $statusLabel = $isMe ? (!empty($msg->read_at) ? 'Dibaca' : 'Terkirim') : null;
                    @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-md space-y-1.5">
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

                            <div class="flex items-center gap-1.5 text-[10px] text-gray-400 {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                <span>{{ optional($msg->created_at)->format('H:i') }}</span>
                                @if($isMe && $statusLabel)
                                    <span>&bull;</span>
                                    <span class="{{ !empty($msg->read_at) ? 'text-orange-600 font-medium' : 'text-gray-400' }}">
                                        {{ $statusLabel }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="h-full flex items-center justify-center">
                <p class="text-center text-xs text-gray-400">
                    Mulai percakapan dengan {{ $displayPartnerName }}.
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
        @if($isAdminPartner)
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