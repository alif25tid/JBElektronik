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
            @endphp

            <a href="{{ route('mahasiswa.chat.show', $partner->id) }}"
               class="flex items-center justify-between gap-4 px-6 py-4 hover:bg-orange-50/50 transition-colors">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $partner->name }}</p>
                        @if(($partner->role ?? '') === 'admin' || (method_exists($partner, 'isAdmin') && $partner->isAdmin()))
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-semibold shrink-0">
                                Admin
                            </span>
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