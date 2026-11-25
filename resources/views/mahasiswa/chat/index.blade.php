@extends('layouts.app')

@section('title', 'Daftar Pesan - Jualin PCR')

@section('content')
<div class="max-w-3xl mx-auto h-[560px] bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
    <!-- Header Daftar Pesan (Fixed) -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50 shrink-0">
        <h1 class="text-lg font-semibold text-gray-900">Pesan</h1>
        <a href="{{ route('mahasiswa.dashboard') }}" class="text-xs font-medium text-orange-600 hover:underline">
            Kembali ke Katalog
        </a>
    </div>

    <!-- List Percakapan (Fixed Height & Scrollable) -->
    <div class="flex-1 overflow-y-auto divide-y divide-gray-100">
        @forelse($conversations as $item)
            @php
                $partner = $item['partner'];
                $lastMsg = $item['latest_message'];
                $unread = $item['unread_count'];
                $isAdminPartner = ($partner->role ?? '') === 'admin'
                    || (method_exists($partner, 'isAdmin') && $partner->isAdmin())
                    || ($lastMsg->is_from_admin ?? false);
            @endphp

            <a href="{{ route('mahasiswa.chat.show', $partner->id) }}"
               class="flex items-center justify-between gap-4 px-6 py-4 hover:bg-orange-50/50 transition-colors">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5">
                        @if($isAdminPartner)
                            <p class="text-sm font-bold text-gray-900 tracking-wide truncate">ADMIN JUALIN</p>
                            <!-- Lencana Bergerigi Centang Oranye -->
                            <span title="Akun Resmi Admin Jualin" aria-label="Akun Resmi Admin Jualin" class="inline-flex items-center justify-center shrink-0 text-orange-500">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 1.5l2.35 1.97 3.05-.37.75 2.98 2.86 1.15-.78 2.97 1.77 2.5-2.18 2.15.27 3.06-3.03.52-1.52 2.68L12 22.5l-2.35-1.97-3.05.37-.75-2.98-2.86-1.15.78-2.97-1.77-2.5 2.18-2.15-.27-3.06 3.03-.52L8.75 2l3.25-.5zm4.03 8.22a.75.75 0 00-1.06-1.06l-4.22 4.22-1.72-1.72a.75.75 0 00-1.06 1.06l2.25 2.25c.3.29.77.29 1.06 0l4.75-4.75z" />
                                </svg>
                            </span>
                        @else
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $partner->name }}</p>
                        @endif

                        @if($lastMsg->product)
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 font-medium truncate max-w-[180px]">
                                {{ $lastMsg->product->nama_barang }}
                            </span>
                        @endif
                    </div>

                    <p class="text-xs {{ $unread > 0 ? 'font-semibold text-gray-900' : 'text-gray-500' }} truncate mt-1">
                        {{ $lastMsg->sender_id === auth()->id() ? 'Anda: ' : '' }}{{ $lastMsg->body }}
                    </p>
                </div>

                <div class="flex flex-col items-end gap-1.5 shrink-0">
                    <span class="text-[11px] {{ $unread > 0 ? 'text-orange-600 font-semibold' : 'text-gray-400' }}">
                        {{ $lastMsg->created_at->format('H:i') }}
                    </span>

                    @if($unread > 0)
                        <span class="min-w-[20px] h-5 px-1.5 rounded-full bg-red-600 text-white text-[11px] font-bold flex items-center justify-center">
                            {{ $unread }}
                        </span>
                    @endif
                </div>
            </a>
        @empty
            <div class="h-full flex items-center justify-center px-6 text-center text-sm text-gray-400">
                Belum ada percakapan.
            </div>
        @endforelse
    </div>
</div>
@endsection