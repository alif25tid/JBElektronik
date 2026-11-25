<header class="sticky top-0 z-50 bg-transparent py-3 transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-center gap-2 sm:gap-2.5">

            <!-- 1. Lingkaran Logo Rapat di Sebelah Kiri Lonjong -->
            <a
                href="{{ route('mahasiswa.dashboard') }}"
                title="Beranda Jualin PCR"
                class="w-12 h-12 rounded-full bg-orange-500 hover:bg-orange-600 ring-2 ring-orange-200 flex items-center justify-center flex-shrink-0 shadow-md transition-all overflow-hidden"
            >
                <img src="{{ asset('images/logosaja.png') }}" alt="Logo Jualin" class="w-7 h-7 object-contain">
            </a>

            <!-- 2. Wadah Lonjong Putih (Rapat dengan Logo) -->
            <div class="flex-1 max-w-5xl bg-white rounded-full border border-orange-200 shadow-md px-3 sm:px-5 py-2 flex items-center justify-between gap-2 sm:gap-4">

                <!-- Bagian Kiri di Dalam Lonjong: Katalog & Toko -->
                <div class="flex items-center gap-1 sm:gap-1.5 flex-shrink-0">
                    @auth
                        <a
                            href="{{ route('mahasiswa.dashboard') }}"
                            title="Katalog Produk"
                            aria-label="Katalog Produk"
                            class="p-2 rounded-full {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-orange-500 text-white shadow-xs' : 'text-orange-600 hover:bg-orange-50' }} transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </a>

                        <a
                            href="{{ route('mahasiswa.toko.index') }}"
                            title="Toko Saya"
                            aria-label="Toko Saya"
                            class="p-2 rounded-full {{ request()->routeIs('mahasiswa.toko.*') ? 'bg-orange-500 text-white shadow-xs' : 'text-orange-600 hover:bg-orange-50' }} transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l1.5-4.5A2 2 0 016.4 3h11.2a2 2 0 011.9 1.5L21 9M3 9h18M4 9v10a2 2 0 002 2h12a2 2 0 002-2V9M9 21v-6h6v6" />
                            </svg>
                        </a>
                    @endauth
                </div>

                <!-- Bagian Tengah di Dalam Lonjong: Kolom Pencarian -->
                <div class="flex-1 max-w-md mx-1 sm:mx-2" id="header-search-wrapper">
                    <form action="{{ route('mahasiswa.dashboard') }}" method="GET" class="relative w-full" autocomplete="off">
                        <div class="relative flex items-center">
                            <input
                                type="search"
                                id="header-search-input"
                                name="search"
                                placeholder="Cari barang, kategori, atau mahasiswa..."
                                value="{{ request('search') }}"
                                class="w-full pl-9 pr-3.5 py-1.5 text-xs sm:text-sm text-gray-800 placeholder-gray-400 bg-orange-50/60 border border-orange-200 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all"
                            />
                            <svg class="w-4 h-4 text-orange-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        @php
                            $headerCategories = \App\Models\Category::orderBy('nama_kategori')->get();
                        @endphp

                        <!-- Dropdown Pilihan Kategori -->
                        <div
                            id="header-category-dropdown"
                            class="hidden absolute left-0 right-0 mt-2 bg-white border border-orange-200 rounded-2xl shadow-lg py-2 z-50"
                        >
                            <div class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-wider text-orange-500">
                                Pilihan Kategori
                            </div>

                            <div class="max-h-60 overflow-y-auto divide-y divide-gray-50">
                                @forelse($headerCategories as $cat)
                                    <a
                                        href="{{ route('mahasiswa.dashboard', ['search' => $cat->nama_kategori]) }}"
                                        class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors"
                                    >
                                        <span>{{ $cat->nama_kategori }}</span>
                                        <span class="text-xs text-gray-400">Kategori</span>
                                    </a>
                                @empty
                                    <div class="px-4 py-2 text-xs text-gray-400">
                                        Belum ada kategori tersedia.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Bagian Kanan di Dalam Lonjong: Chat, Keranjang, & Logout -->
                <div class="flex items-center gap-1 sm:gap-1.5 flex-shrink-0">
                    <!-- Chat -->
                    @php
                        $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
                            ->whereNull('read_at')
                            ->count();
                    @endphp

                    <a
                        href="{{ route('mahasiswa.chat.index') }}"
                        title="Pesan"
                        aria-label="Pesan"
                        class="relative p-2 rounded-full {{ request()->routeIs('mahasiswa.chat.*') ? 'bg-orange-500 text-white shadow-xs' : 'text-orange-600 hover:bg-orange-50' }} transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>

                        @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                            <span class="absolute top-0.5 right-0.5 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">
                                {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Keranjang -->
                    <a
                        href="{{ route('mahasiswa.cart.index') }}"
                        title="Keranjang"
                        aria-label="Keranjang"
                        class="relative p-2 rounded-full {{ request()->routeIs('mahasiswa.cart.*') ? 'bg-orange-500 text-white shadow-xs' : 'text-orange-600 hover:bg-orange-50' }} transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>

                        @if(isset($cartCount) && $cartCount > 0)
                            <span class="absolute top-0.5 right-0.5 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full shadow-sm">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button
                            type="submit"
                            title="Logout"
                            aria-label="Logout"
                            class="p-2 rounded-full text-red-600 hover:bg-red-50 transition-colors cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                            </svg>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('header-search-wrapper');
        const input = document.getElementById('header-search-input');
        const dropdown = document.getElementById('header-category-dropdown');

        if (!wrapper || !input || !dropdown) return;

        input.addEventListener('focus', function () {
            dropdown.classList.remove('hidden');
        });

        input.addEventListener('click', function () {
            dropdown.classList.remove('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>