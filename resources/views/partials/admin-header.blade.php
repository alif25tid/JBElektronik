<header class="sticky top-0 z-50 bg-transparent py-3 transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-center gap-2 sm:gap-2.5">

            <!-- 1. Lingkaran Logo Navy Rapat di Sebelah Kiri Kapsul -->
            <a
                href="{{ route('admin.dashboard') }}#ringkasan"
                data-admin-tab-trigger="ringkasan"
                data-admin-tab-index="0"
                title="Ringkasan Dashboard Admin"
                aria-label="Ringkasan Dashboard Admin"
                class="w-12 h-12 rounded-full bg-slate-900 hover:bg-slate-800 ring-2 ring-slate-900/20 flex items-center justify-center flex-shrink-0 shadow-md transition-all overflow-hidden"
            >
                <img src="{{ asset('images/logosaja.png') }}" alt="Logo Jualin" class="w-7 h-7 object-contain">
            </a>

            <!-- 2. Wadah Kapsul Putih dengan Border Navy -->
            <div class="flex-1 max-w-5xl bg-white text-slate-900 rounded-full border-2 border-slate-900 shadow-md px-3 sm:px-5 py-2 flex items-center justify-between gap-2 sm:gap-4">

                <!-- Menu Navigasi Berbasis Ikon & Kapsul Aktif Navy -->
                <nav class="flex items-center gap-1 sm:gap-2 overflow-x-auto" role="navigation" aria-label="Navigasi Admin">
                    <!-- Menu 0: Ringkasan -->
                    <a
                        href="{{ route('admin.dashboard') }}#ringkasan"
                        data-admin-tab-trigger="ringkasan"
                        data-admin-tab-index="0"
                        title="Ringkasan"
                        aria-label="Ringkasan"
                        class="admin-nav-btn inline-flex items-center gap-2 px-3.5 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all bg-slate-900 text-white shadow-xs"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span class="hidden sm:inline">Ringkasan</span>
                    </a>

                    <!-- Menu 1: Moderasi -->
                    <a
                        href="{{ route('admin.dashboard') }}#moderasi-barang"
                        data-admin-tab-trigger="moderasi"
                        data-admin-tab-index="1"
                        title="Moderasi Barang"
                        aria-label="Moderasi Barang"
                        class="admin-nav-btn inline-flex items-center gap-2 px-3.5 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap transition-all text-slate-900 hover:bg-slate-100"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="hidden sm:inline">Moderasi</span>
                    </a>

                    <!-- Menu 2: Pengguna -->
                    <a
                        href="{{ route('admin.dashboard') }}#daftar-pengguna"
                        data-admin-tab-trigger="pengguna"
                        data-admin-tab-index="2"
                        title="Daftar Pengguna"
                        aria-label="Daftar Pengguna"
                        class="admin-nav-btn inline-flex items-center gap-2 px-3.5 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap transition-all text-slate-900 hover:bg-slate-100"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="hidden sm:inline">Pengguna</span>
                    </a>

                    <!-- Menu 3: Daftar Barang -->
                    <a
                        href="{{ route('admin.dashboard') }}#daftar-barang"
                        data-admin-tab-trigger="barang"
                        data-admin-tab-index="3"
                        title="Daftar Barang"
                        aria-label="Daftar Barang"
                        class="admin-nav-btn inline-flex items-center gap-2 px-3.5 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap transition-all text-slate-900 hover:bg-slate-100"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="hidden sm:inline">Daftar Barang</span>
                    </a>
                </nav>

                <!-- Tombol Logout Berbasis Ikon / Logo -->
                <div class="flex items-center flex-shrink-0">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button
                            type="submit"
                            title="Logout"
                            aria-label="Logout"
                            class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-xs sm:text-sm font-medium px-3.5 py-2 rounded-full transition-colors cursor-pointer"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                            </svg>
                            <span class="hidden md:inline">Logout</span>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</header>