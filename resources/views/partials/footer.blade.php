<footer class="bg-orange-500 border-t border-orange-600 text-white mt-auto">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="py-12 border-b border-orange-400/60">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="text-2xl font-bold text-white mb-2">Jual Beli Aman Antar Mahasiswa</h3>
                <p class="text-orange-100 text-sm mb-6">Platform khusus civitas akademika PCR untuk memperjualbelikan perlengkapan kuliah, komponen praktikum, dan peralatan kos.</p>
            </div>
        </div>

        <div class="py-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <img src="{{ asset('images/logonama.png') }}" alt="Logo Jualin" width="70" height="30" class="brightness-0 invert">
                <p class="text-sm text-orange-100 mt-3">Solusi praktis sirkulasi barang elektronik preloved di lingkungan kampus.</p>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider">Navigasi Cepat</h4>
                <ul class="space-y-2 text-sm text-orange-100">
                    <li><a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-white hover:underline transition-colors">Katalog Beranda</a></li>
                    <li><a href="{{ route('mahasiswa.toko.index') }}" class="hover:text-white hover:underline transition-colors">Kelola Toko Saya</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider">Dukungan Kampus</h4>
                <ul class="space-y-2 text-sm text-orange-100">
                    <li><span>Politeknik Caltex Riau</span></li>
                    <li><span>Kampus Rumbai, Pekanbaru</span></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-white mb-4 uppercase tracking-wider">Keamanan Transaksi</h4>
                <p class="text-sm text-orange-100">Wajib menggunakan email domain resmi PCR dan koordinasi COD langsung di titik kawasan kampus.</p>
            </div>
        </div>

        <div class="border-t border-orange-400/60 my-4"></div>

        <div class="py-6 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-orange-100">
            <div>
                <p>© 2026 Jualin PCR. Template adapted from BloomShop™.</p>
            </div>
            <div class="flex items-center gap-6">
                <span>Sistem Internal Mahasiswa</span>
            </div>
        </div>

    </div>
</footer>