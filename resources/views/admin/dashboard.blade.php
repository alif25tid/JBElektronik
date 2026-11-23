@extends('layouts.admin')

@section('title', 'Dashboard Admin - Jualin PCR')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-sm text-gray-600">Moderasi barang dagangan, pantau daftar pengguna, dan kelola seluruh barang.</p>
        </div>
    </div>

    {{-- Notifikasi Sukses / Error --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 1. RINGKASAN STATISTIK --}}
    <section id="ringkasan" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white/95 rounded-xl border border-amber-300 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Menunggu Konfirmasi</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ $pendingProducts->count() }}</p>
            <p class="mt-1 text-xs text-gray-500">Barang berstatus pending</p>
        </div>

        <div class="bg-white/95 rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Barang Disetujui</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600">
                {{ $allProducts->whereIn('status', ['tersedia', 'approved'])->count() }}
            </p>
            <p class="mt-1 text-xs text-gray-500">Tampil di katalog mahasiswa</p>
        </div>

        <div class="bg-white/95 rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Barang Ditolak</p>
            <p class="mt-2 text-3xl font-bold text-red-600">
                {{ $allProducts->where('status', 'rejected')->count() }}
            </p>
            <p class="mt-1 text-xs text-gray-500">Diblokir / perlu revisi penjual</p>
        </div>

        <div class="bg-white/95 rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Pengguna</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $users->count() }}</p>
            <p class="mt-1 text-xs text-gray-500">Mahasiswa terdaftar</p>
        </div>
    </section>

    {{-- 2. ANTREAN MODERASI BARANG (TOMBOL DETAIL -> POP-UP MODAL) --}}
    <section id="moderasi-barang" class="bg-white/95 rounded-xl border border-amber-400 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-amber-50">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Antrean Moderasi Barang</h2>
                <p class="text-xs text-gray-600">Klik tombol Detail untuk memeriksa informasi lengkap sebelum konfirmasi atau menolak barang.</p>
            </div>
            <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-500 text-slate-950">
                {{ $pendingProducts->count() }} Pending
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Barang</th>
                        <th class="px-6 py-3 text-left font-semibold">Penjual</th>
                        <th class="px-6 py-3 text-left font-semibold">Kategori &amp; Harga</th>
                        <th class="px-6 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($pendingProducts as $product)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($product->foto)
                                        <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_barang }}" class="h-12 w-12 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-[10px] text-gray-400 flex-shrink-0">
                                            No Img
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $product->nama_barang }}</div>
                                        <div class="text-xs text-gray-500">{{ $product->titik_lokasi }} · {{ $product->metode_serah_terima }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $product->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $product->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-600">{{ $product->category->nama_kategori ?? $product->category->name ?? '-' }}</div>
                                <div class="font-bold text-orange-600 mt-0.5">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button
                                    type="button"
                                    onclick="openModerasiModal(this)"
                                    data-nama="{{ $product->nama_barang }}"
                                    data-harga="Rp {{ number_format($product->harga, 0, ',', '.') }}"
                                    data-kategori="{{ $product->category->nama_kategori ?? $product->category->name ?? '-' }}"
                                    data-penjual="{{ $product->user->name ?? '-' }}"
                                    data-email="{{ $product->user->email ?? '-' }}"
                                    data-wa="{{ $product->user->no_whatsapp ?? '-' }}"
                                    data-lokasi="{{ $product->titik_lokasi ?? '-' }}"
                                    data-metode="{{ $product->metode_serah_terima ?? '-' }}"
                                    data-deskripsi="{{ $product->deskripsi ?? '-' }}"
                                    data-foto="{{ $product->foto ? asset('storage/' . $product->foto) : '' }}"
                                    data-video="{{ ($product->video_review && $product->video_review !== '-') ? asset('storage/' . $product->video_review) : '' }}"
                                    data-approve-url="{{ route('admin.products.approve', $product->id) }}"
                                    data-reject-url="{{ route('admin.products.reject', $product->id) }}"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-sm transition-colors cursor-pointer"
                                >
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                Tidak ada barang yang menunggu konfirmasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- 3. DAFTAR PENGGUNA --}}
    <section id="daftar-pengguna" class="bg-white/95 rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Daftar Pengguna</h2>
                <p class="text-xs text-gray-600">Daftar akun mahasiswa terdaftar (akun admin tidak ditampilkan).</p>
            </div>
            <span class="text-xs font-semibold text-gray-500">{{ $users->count() }} pengguna</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Nama</th>
                        <th class="px-6 py-3 text-left font-semibold">Email</th>
                        <th class="px-6 py-3 text-left font-semibold">No. WhatsApp</th>
                        <th class="px-6 py-3 text-left font-semibold">Jumlah Barang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-3.5 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-3.5 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-3.5 text-gray-600">{{ $user->no_whatsapp ?? '-' }}</td>
                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                    {{ $user->products_count ?? 0 }} barang
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                Belum ada pengguna terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- 4. DAFTAR SEMUA BARANG --}}
    <section id="daftar-barang" class="bg-white/95 rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Daftar Barang</h2>
                <p class="text-xs text-gray-600">Seluruh barang yang diunggah beserta status moderasi dan alasan penolakan.</p>
            </div>
            <span class="text-xs font-semibold text-gray-500">{{ $allProducts->count() }} barang</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Nama Barang</th>
                        <th class="px-6 py-3 text-left font-semibold">Penjual</th>
                        <th class="px-6 py-3 text-left font-semibold">Harga</th>
                        <th class="px-6 py-3 text-left font-semibold">Status</th>
                        <th class="px-6 py-3 text-left font-semibold">Alasan Penolakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($allProducts as $product)
                        <tr>
                            <td class="px-6 py-3.5 font-medium text-gray-900">{{ $product->nama_barang }}</td>
                            <td class="px-6 py-3.5 text-gray-600">{{ $product->user->name ?? '-' }}</td>
                            <td class="px-6 py-3.5 font-semibold text-gray-900">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                @if(in_array($product->status, ['tersedia', 'approved']))
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Disetujui</span>
                                @elseif($product->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Pending</span>
                                @elseif($product->status === 'rejected')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">{{ ucfirst($product->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-xs text-gray-600">
                                {{ $product->rejection_reason ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                Belum ada data barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- POP-UP MODAL DETAIL & MODERASI BARANG --}}
    <div id="moderasiModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-gray-200">
            {{-- Header Modal --}}
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Moderasi Produk</span>
                    <h3 id="modalNamaBarang" class="text-lg font-bold text-gray-900">Detail Barang</h3>
                </div>
                <button type="button" onclick="closeModerasiModal()" class="p-2 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Body Modal: Detail Lengkap Barang --}}
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Foto Produk --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden flex items-center justify-center h-56">
                        <img id="modalFoto" src="" alt="Foto Produk" class="w-full h-full object-cover hidden">
                        <span id="modalNoFoto" class="text-xs text-gray-400">Tidak ada foto produk</span>
                    </div>

                    {{-- Info Ringkas --}}
                    <div class="space-y-3 text-sm">
                        <div>
                            <span id="modalKategori" class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                Kategori
                            </span>
                            <p id="modalHarga" class="mt-2 text-2xl font-extrabold text-orange-600">Rp 0</p>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-1 text-xs">
                            <p class="font-semibold text-gray-800">Penjual: <span id="modalPenjual" class="font-normal text-gray-600">-</span></p>
                            <p class="font-semibold text-gray-800">Email: <span id="modalEmail" class="font-normal text-gray-600">-</span></p>
                            <p class="font-semibold text-gray-800">WhatsApp: <span id="modalWa" class="font-normal text-gray-600">-</span></p>
                        </div>

                        <div class="text-xs text-gray-600 space-y-1">
                            <p><span class="font-semibold text-gray-800">Serah Terima:</span> <span id="modalMetode">-</span></p>
                            <p><span class="font-semibold text-gray-800">Titik Lokasi:</span> <span id="modalLokasi">-</span></p>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Barang --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Deskripsi Barang</h4>
                    <div id="modalDeskripsi" class="p-3.5 rounded-xl bg-gray-50 border border-gray-200 text-sm text-gray-700 whitespace-pre-line">
                        -
                    </div>
                </div>

                {{-- Video Review (Muncul jika ada) --}}
                <div id="modalVideoWrapper" class="hidden">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Video Review</h4>
                    <video id="modalVideo" controls class="w-full max-h-64 rounded-xl border border-gray-200 bg-black"></video>
                </div>

                {{-- Panel Keputusan Moderasi (Konfirmasi & Tolak + Alasan) --}}
                <div class="pt-4 border-t border-gray-200 space-y-4">
                    {{-- Tombol Konfirmasi --}}
<form id="modalApproveForm" action="" method="POST">
    @csrf
    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition-colors cursor-pointer">
        Konfirmasi &amp; Tampilkan ke Katalog
    </button>
</form>

{{-- Form Tolak + Alasan Penolakan --}}
<form id="modalRejectForm" action="" method="POST" class="p-4 rounded-xl bg-red-50/80 border border-red-200 space-y-2.5">
    @csrf
    <label for="modalRejectionReason" class="block text-xs font-bold text-red-800">
        Alasan Penolakan <span class="font-normal text-red-600">(Wajib diisi jika menolak barang)</span>
    </label>
    <textarea
        id="modalRejectionReason"
        name="rejection_reason"
        rows="2"
        required
        placeholder="Contoh: Barang termasuk kategori terlarang di lingkungan kampus (vape/rokok elektrik)..."
        class="w-full px-3 py-2 rounded-lg border border-red-300 bg-white text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500"
    ></textarea>
    <button type="submit" class="w-full py-2 px-4 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition-colors cursor-pointer">
        Tolak Barang
    </button>
</form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('moderasiModal');

        function openModerasiModal(btn) {
            document.getElementById('modalNamaBarang').textContent = btn.dataset.nama;
            document.getElementById('modalHarga').textContent = btn.dataset.harga;
            document.getElementById('modalKategori').textContent = btn.dataset.kategori;
            document.getElementById('modalPenjual').textContent = btn.dataset.penjual;
            document.getElementById('modalEmail').textContent = btn.dataset.email;
            document.getElementById('modalWa').textContent = btn.dataset.wa;
            document.getElementById('modalLokasi').textContent = btn.dataset.lokasi;
            document.getElementById('modalMetode').textContent = btn.dataset.metode;
            document.getElementById('modalDeskripsi').textContent = btn.dataset.deskripsi;

            // Foto produk
            const img = document.getElementById('modalFoto');
            const noImg = document.getElementById('modalNoFoto');
            if (btn.dataset.foto) {
                img.src = btn.dataset.foto;
                img.classList.remove('hidden');
                noImg.classList.add('hidden');
            } else {
                img.src = '';
                img.classList.add('hidden');
                noImg.classList.remove('hidden');
            }

            // Video review
            const videoWrapper = document.getElementById('modalVideoWrapper');
            const video = document.getElementById('modalVideo');
            if (btn.dataset.video) {
                video.src = btn.dataset.video;
                videoWrapper.classList.remove('hidden');
            } else {
                video.pause();
                video.src = '';
                videoWrapper.classList.add('hidden');
            }

            // Action URLs & reset input alasan
            document.getElementById('modalApproveForm').action = btn.dataset.approveUrl;
            document.getElementById('modalRejectForm').action = btn.dataset.rejectUrl;
            document.getElementById('modalRejectionReason').value = '';

            modal.classList.remove('hidden');
        }

        function closeModerasiModal() {
            const video = document.getElementById('modalVideo');
            video.pause();
            modal.classList.add('hidden');
        }

        // Tutup modal saat klik area gelap di luar kotak atau menekan Escape
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModerasiModal();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModerasiModal();
            }
        });
    </script>
@endpush