<header id="mahasiswa-header" class="sticky top-0 z-50 bg-transparent py-4 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
    <style>
        @keyframes navBulgeEnter {
            0% {
                transform: scale(0.75);
                opacity: 0.7;
            }
            70% {
                transform: scale(1.03);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        .nav-active-smooth {
            animation: navBulgeEnter 420ms cubic-bezier(0.22, 1, 0.36, 1) both;
        }
    </style>

    <div class="container mx-auto px-4 sm:px-6 relative">
        <div class="flex items-center justify-center gap-3">

            <!-- 1. Lingkaran Logo Kiri (Orange Gradasi Penuh + Hover Gradasi, Tanpa Border) -->
            <a
                href="{{ route('mahasiswa.dashboard') }}"
                title="Beranda Jualin PCR"
                class="group w-14 h-14 aspect-square rounded-full bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 hover:from-amber-400 hover:via-orange-500 hover:to-red-500 flex items-center justify-center flex-shrink-0 shadow-md hover:shadow-lg hover:scale-105 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] overflow-hidden"
            >
                <img
                    src="{{ asset('images/logosaja.png') }}"
                    alt="Logo Jualin"
                    class="w-7 h-7 object-contain group-hover:scale-110 transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
                >
            </a>

            <!-- 2. Wadah Header Putih dengan drop-shadow menyatu (tanpa shadow terpisah yang bikin garis sambungan) -->
            <div class="flex-1 max-w-5xl filter drop-shadow-[0_4px_12px_rgba(15,23,42,0.08)] transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                <div class="relative h-14 bg-white rounded-full px-3 sm:px-6 flex items-center justify-between gap-2 sm:gap-4 overflow-visible">

                    <!-- Bagian Kiri: Katalog & Toko -->
                    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                        @auth
                            @php $isKatalogActive = request()->routeIs('mahasiswa.dashboard'); @endphp
                            <div class="relative flex items-center justify-center {{ $isKatalogActive ? 'w-16 h-14' : 'w-10 h-14' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                                @if($isKatalogActive)
                                    <span aria-hidden="true" class="nav-active-smooth absolute w-20 h-20 rounded-full bg-white pointer-events-none"></span>
                                @endif
                                <a
                                    href="{{ route('mahasiswa.dashboard') }}"
                                    title="Katalog Produk"
                                    aria-label="Katalog Produk"
                                    class="relative z-10 aspect-square rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] {{ $isKatalogActive ? 'nav-active-smooth w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 text-white shadow-md' : 'w-10 h-10 text-orange-600 hover:bg-orange-50 hover:scale-110' }}"
                                >
                                    <svg
                                        class="{{ $isKatalogActive ? 'w-7 h-7' : 'w-5 h-5' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </a>
                            </div>

                            @php $isTokoActive = request()->routeIs('mahasiswa.toko.*'); @endphp
                            <div class="relative flex items-center justify-center {{ $isTokoActive ? 'w-16 h-14' : 'w-10 h-14' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                                @if($isTokoActive)
                                    <span aria-hidden="true" class="nav-active-smooth absolute w-20 h-20 rounded-full bg-white pointer-events-none"></span>
                                @endif
                                <a
                                    href="{{ route('mahasiswa.toko.index') }}"
                                    title="Toko Saya"
                                    aria-label="Toko Saya"
                                    class="relative z-10 aspect-square rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] {{ $isTokoActive ? 'nav-active-smooth w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 text-white shadow-md' : 'w-10 h-10 text-orange-600 hover:bg-orange-50 hover:scale-110' }}"
                                >
                                    <svg
                                        class="{{ $isTokoActive ? 'w-7 h-7' : 'w-5 h-5' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l1.5-4.5A2 2 0 016.4 3h11.2a2 2 0 011.9 1.5L21 9M3 9h18M4 9v10a2 2 0 002 2h12a2 2 0 002-2V9M9 21v-6h6v6" />
                                    </svg>
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Bagian Kanan: Pencarian, Chat, Keranjang, Akun, & Logout -->
                    <div class="flex items-center gap-1.5 sm:gap-2.5 flex-shrink-0">
                        <!-- Tombol Icon Pencarian -->
                        <div class="relative flex items-center justify-center w-10 h-14">
                            <button
                                type="button"
                                id="header-search-toggle-btn"
                                title="Cari Barang"
                                aria-label="Cari Barang"
                                aria-expanded="{{ request('search') ? 'true' : 'false' }}"
                                class="relative z-10 w-10 h-10 aspect-square rounded-full flex items-center justify-center flex-shrink-0 text-orange-600 hover:bg-orange-50 hover:scale-110 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] cursor-pointer"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Chat -->
                        @php
                            $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())
                                ->whereNull('read_at')
                                ->count();
                            $isChatActive = request()->routeIs('mahasiswa.chat.*');
                        @endphp
                        <div class="relative flex items-center justify-center {{ $isChatActive ? 'w-16 h-14' : 'w-10 h-14' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                            @if($isChatActive)
                                <span aria-hidden="true" class="nav-active-smooth absolute w-20 h-20 rounded-full bg-white pointer-events-none"></span>
                            @endif
                            <a
                                href="{{ route('mahasiswa.chat.index') }}"
                                title="Pesan"
                                aria-label="Pesan"
                                class="relative z-10 aspect-square rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] {{ $isChatActive ? 'nav-active-smooth w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 text-white shadow-md' : 'w-10 h-10 text-orange-600 hover:bg-orange-50 hover:scale-110' }}"
                            >
                                <svg
                                    class="{{ $isChatActive ? 'w-7 h-7' : 'w-5 h-5' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>

                                @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                    <span class="absolute {{ $isChatActive ? 'top-1 right-1' : 'top-0 right-0' }} bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">
                                        {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <!-- Keranjang -->
                        @php $isCartActive = request()->routeIs('mahasiswa.cart.*'); @endphp
                        <div class="relative flex items-center justify-center {{ $isCartActive ? 'w-16 h-14' : 'w-10 h-14' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                            @if($isCartActive)
                                <span aria-hidden="true" class="nav-active-smooth absolute w-20 h-20 rounded-full bg-white pointer-events-none"></span>
                            @endif
                            <a
                                href="{{ route('mahasiswa.cart.index') }}"
                                title="Keranjang"
                                aria-label="Keranjang"
                                class="relative z-10 aspect-square rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] {{ $isCartActive ? 'nav-active-smooth w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 text-white shadow-md' : 'w-10 h-10 text-orange-600 hover:bg-orange-50 hover:scale-110' }}"
                            >
                                <svg
                                    class="{{ $isCartActive ? 'w-7 h-7' : 'w-5 h-5' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>

                                @if(isset($cartCount) && $cartCount > 0)
                                    <span class="absolute {{ $isCartActive ? 'top-1 right-1' : 'top-0 right-0' }} bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full shadow-sm">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <!-- Akun -->
                        @auth
                            @php $isProfileActive = request()->routeIs('mahasiswa.profile.*'); @endphp
                            <div class="relative flex items-center justify-center {{ $isProfileActive ? 'w-16 h-14' : 'w-10 h-14' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                                @if($isProfileActive)
                                    <span aria-hidden="true" class="nav-active-smooth absolute w-20 h-20 rounded-full bg-white pointer-events-none"></span>
                                @endif
                                <a
                                    href="{{ route('mahasiswa.profile.index') }}"
                                    title="Akun Saya"
                                    aria-label="Akun Saya"
                                    class="relative z-10 aspect-square rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] {{ $isProfileActive ? 'nav-active-smooth w-16 h-16 bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 text-white shadow-md' : 'w-10 h-10 text-orange-600 hover:bg-orange-50 hover:scale-110' }}"
                                >
                                    <svg
                                        class="{{ $isProfileActive ? 'w-7 h-7' : 'w-5 h-5' }} transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </a>
                            </div>
                        @endauth

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="inline-flex items-center justify-center flex-shrink-0 m-0">
                            @csrf
                            <button
                                type="submit"
                                title="Logout"
                                aria-label="Logout"
                                class="w-10 h-10 aspect-square rounded-full flex items-center justify-center text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-300 cursor-pointer"
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

        <!-- Panel Pencarian Melayang (Tidak Mendorong Konten Dashboard) -->
        <div
            id="header-search-drawer"
            class="{{ request('search') ? '' : 'hidden opacity-0 -translate-y-2 pointer-events-none' }} absolute left-4 right-4 sm:left-6 sm:right-6 top-full mt-3 z-50 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
        >
            <div
                id="header-search-wrapper"
                class="max-w-2xl mx-auto bg-white p-2.5 rounded-2xl shadow-xl relative"
            >
                <form action="{{ route('mahasiswa.dashboard') }}" method="GET" class="relative w-full" autocomplete="off">
                    <div class="relative flex items-center gap-2">
                        <div class="relative flex-1 flex items-center">
                            <input
                                type="search"
                                id="header-search-input"
                                name="search"
                                placeholder="Cari barang, kategori, atau mahasiswa..."
                                value="{{ request('search') }}"
                                class="w-full pl-9 pr-3.5 py-2 text-xs sm:text-sm text-gray-800 placeholder-gray-400 bg-orange-50/60 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition-all duration-300"
                            />
                            <svg class="w-4 h-4 text-orange-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <button
                            type="submit"
                            class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-xs sm:text-sm font-bold shadow-xs transition-all duration-300 cursor-pointer flex-shrink-0"
                        >
                            Cari
                        </button>
                    </div>

                    @php
                        $headerCategories = \App\Models\Category::orderBy('nama_kategori')->get();
                    @endphp

                    <!-- Dropdown Pilihan Kategori -->
                    <div
                        id="header-category-dropdown"
                        class="hidden mt-2 bg-white rounded-xl shadow-lg py-2 z-50"
                    >
                        <div class="px-4 py-1 text-[11px] font-semibold uppercase tracking-wider text-orange-500">
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
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('header-search-toggle-btn');
        const drawer = document.getElementById('header-search-drawer');
        const wrapper = document.getElementById('header-search-wrapper');
        const input = document.getElementById('header-search-input');
        const dropdown = document.getElementById('header-category-dropdown');

        if (!toggleBtn || !drawer || !wrapper || !input || !dropdown) return;

        let isOpen = !drawer.classList.contains('hidden');

        function setSearchToggleActive(active) {
            toggleBtn.classList.toggle('bg-gradient-to-br', active);
            toggleBtn.classList.toggle('from-orange-400', active);
            toggleBtn.classList.toggle('via-orange-500', active);
            toggleBtn.classList.toggle('to-orange-600', active);
            toggleBtn.classList.toggle('text-white', active);
            toggleBtn.classList.toggle('shadow-md', active);

            toggleBtn.classList.toggle('text-orange-600', !active);
            toggleBtn.classList.toggle('hover:bg-orange-50', !active);
        }

        function openSearchDrawer() {
            drawer.classList.remove('hidden');
            requestAnimationFrame(() => {
                drawer.classList.remove('opacity-0', '-translate-y-2', 'pointer-events-none');
                drawer.classList.add('opacity-100', 'translate-y-0');
            });
            setSearchToggleActive(true);
            toggleBtn.setAttribute('aria-expanded', 'true');
            isOpen = true;

            setTimeout(() => {
                input.focus();
                dropdown.classList.remove('hidden');
            }, 140);
        }

        function closeSearchDrawer() {
            drawer.classList.add('opacity-0', '-translate-y-2', 'pointer-events-none');
            drawer.classList.remove('opacity-100', 'translate-y-0');
            dropdown.classList.add('hidden');
            setSearchToggleActive(false);
            toggleBtn.setAttribute('aria-expanded', 'false');
            isOpen = false;

            setTimeout(() => {
                if (!isOpen) drawer.classList.add('hidden');
            }, 350);
        }

        if (isOpen) {
            setSearchToggleActive(true);
        }

        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (isOpen) {
                closeSearchDrawer();
            } else {
                openSearchDrawer();
            }
        });

        input.addEventListener('focus', function () {
            dropdown.classList.remove('hidden');
        });

        input.addEventListener('click', function () {
            dropdown.classList.remove('hidden');
        });

        document.addEventListener('click', function (e) {
            if (isOpen && !wrapper.contains(e.target) && !toggleBtn.contains(e.target)) {
                closeSearchDrawer();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && isOpen) {
                closeSearchDrawer();
            }
        });
    });
</script>