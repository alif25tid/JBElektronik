<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 py-4">
        <div class="flex items-center justify-between">
            
            <!-- Logo & Navigasi Kiri -->
            <div class="flex items-center space-x-8 lg:space-x-12">
                <a href="{{ route('mahasiswa.dashboard') }}" class="text-2xl tracking-tight font-bold text-gray-900 hover:text-gray-700 transition-colors">
                    JUALIN-<span class="text-orange-600">PCR</span>
                </a>

                <nav class="hidden md:flex items-center space-x-1" role="navigation">
                    @auth
                        <a href="{{ route('mahasiswa.dashboard') }}" class="py-2 px-4 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-all">Katalog Produk</a>
                        <a href="{{ route('mahasiswa.toko.index') }}" class="py-2 px-4 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-all">Toko Saya</a>
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
            <div class="flex items-center space-x-3">
                @auth
                    <div class="hidden sm:flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-700">Halo, {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="hidden sm:flex items-center space-x-2">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 px-4 py-2 hover:bg-gray-100 rounded-full transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm font-medium bg-orange-600 text-white px-4 py-2 rounded-full hover:bg-orange-700 transition-colors">Daftar</a>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</header>