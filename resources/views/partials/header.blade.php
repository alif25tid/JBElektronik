<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 py-4">
        <div class="flex items-center justify-between">
            
            <!-- Logo & Navigasi Kiri -->
            <div class="flex items-center space-x-8 lg:space-x-12">
                <div class="logo-container">
                    <a href="{{ route('mahasiswa.dashboard') }}">
                        <img src="{{ asset('images/logosaja.png') }}" alt="Logo Jualin" width="50" height="30">
                    </a>
                </div>

                <nav class="hidden md:flex items-center space-x-1" role="navigation">
                    @auth
                        <a href="{{ route('mahasiswa.dashboard') }}" class="py-2 px-4 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition-all">Katalog Produk</a>
                        <a href="{{ route('mahasiswa.toko.index') }}" class="py-2 px-4 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-100 transition-all">Toko Saya</a>
                    @endauth
                </nav>
            </div>

            <!-- Form Pencarian (Desktop) -->
            <div class="hidden lg:flex flex-1 max-w-md mx-8" id="header-search-wrapper">
                <form action="{{ route('mahasiswa.dashboard') }}" method="GET" class="relative w-full" autocomplete="off">
                    <div class="relative flex items-center">
                        <input
                            type="search"
                            id="header-search-input"
                            name="search"
                            placeholder="Cari nama barang atau kategori..."
                            value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                        />
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    @php
                        $headerCategories = \App\Models\Category::orderBy('nama_kategori')->get();
                    @endphp

                    <!-- Dropdown Pilihan Kategori di Bawah Kolom Pencarian -->
                    <div
                        id="header-category-dropdown"
                        class="hidden absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-2xl shadow-lg py-2 z-50"
                    >
                        <div class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400">
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
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const wrapper = document.getElementById('header-search-wrapper');
                    const input = document.getElementById('header-search-input');
                    const dropdown = document.getElementById('header-category-dropdown');

                    if (!wrapper || !input || !dropdown) return;

                    // Tampilkan pilihan kategori saat kolom pencarian diklik / difokuskan
                    input.addEventListener('focus', function () {
                        dropdown.classList.remove('hidden');
                    });

                    input.addEventListener('click', function () {
                        dropdown.classList.remove('hidden');
                    });

                    // Tutup dropdown saat klik di luar area pencarian
                    document.addEventListener('click', function (e) {
                        if (!wrapper.contains(e.target)) {
                            dropdown.classList.add('hidden');
                        }
                    });

                    // Tutup dropdown saat menekan tombol Escape
                    input.addEventListener('keydown', function (e) {
                        if (e.key === 'Escape') {
                            dropdown.classList.add('hidden');
                        }
                    });
                });
            </script>

            <!-- Tombol Kanan (Auth & Menu) -->
            <div class="flex items-center space-x-6">
                
                <!-- IKON KERANJANG DENGAN BADGE ANGKA -->
                <a href="{{ route('mahasiswa.cart.index') }}" class="relative text-gray-700 hover:text-orange-600 p-2 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>

                    <!-- Badge Angka Dinamis -->
                    @if(isset($cartCount) && $cartCount > 0)
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-sm">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Teks Halo & Tombol Logout -->
                <span class="text-sm font-medium text-gray-700">Halo, {{ Auth::user()->name ?? 'Alif Al Fikhri' }}</span>
                
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-full transition-colors">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>
</header>