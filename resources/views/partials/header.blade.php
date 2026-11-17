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
            <div class="hidden lg:flex flex-1 max-w-md mx-8">
                <form action="{{ route('mahasiswa.dashboard') }}" method="GET" class="relative w-full">
                    <input type="search" name="search" placeholder="Cari barang elektronik..." value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>
            </div>

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