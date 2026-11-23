<header class="sticky top-0 z-50 bg-slate-900/95 backdrop-blur-md border-b border-slate-800 text-white shadow-sm transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 py-4">
        <div class="flex items-center justify-between gap-4">

            <!-- Logo & Navigasi Kiri -->
            <div class="flex items-center space-x-6 lg:space-x-10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logosaja.png') }}" alt="Logo Jualin" width="46" height="28" class="rounded">
                    <div class="leading-tight">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-base tracking-tight text-white">Jualin PCR</span>
                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30">
                                Admin
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 hidden sm:block">Panel Moderasi &amp; Data</span>
                    </div>
                </a>

                <nav class="hidden md:flex items-center space-x-1" role="navigation">
                    <a href="{{ route('admin.dashboard') }}#ringkasan" class="py-2 px-3.5 rounded-lg text-sm font-medium text-slate-200 hover:bg-slate-800 hover:text-amber-300 transition-all">
                        Ringkasan
                    </a>
                    <a href="{{ route('admin.dashboard') }}#moderasi-barang" class="py-2 px-3.5 rounded-lg text-sm font-medium text-slate-200 hover:bg-slate-800 hover:text-amber-300 transition-all inline-flex items-center gap-1.5">
                        <span>Moderasi</span>
                        @isset($pendingProducts)
                            <span class="px-1.5 py-0.5 text-[11px] font-bold rounded-full bg-amber-500 text-slate-950">
                                {{ $pendingProducts->count() }}
                            </span>
                        @endisset
                    </a>
                    <a href="{{ route('admin.dashboard') }}#daftar-pengguna" class="py-2 px-3.5 rounded-lg text-sm font-medium text-slate-200 hover:bg-slate-800 hover:text-amber-300 transition-all">
                        Pengguna
                    </a>
                    <a href="{{ route('admin.dashboard') }}#daftar-barang" class="py-2 px-3.5 rounded-lg text-sm font-medium text-slate-200 hover:bg-slate-800 hover:text-amber-300 transition-all">
                        Daftar Barang
                    </a>
                </nav>
            </div>

            <!-- Info Admin & Tombol Logout Kanan -->
            <div class="flex items-center space-x-4">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-full transition-colors cursor-pointer">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</header>