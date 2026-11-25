@extends('layouts.admin')

@section('title', 'Dashboard Admin - Jualin PCR')

@section('content')
@php
    $tz = config('app.timezone', 'Asia/Jakarta');

    $approvedProducts = $allProducts->where('moderation_status', 'approved');
    $rejectedProducts = $allProducts->where('moderation_status', 'rejected');

    $approvedProductsPayload = $approvedProducts
        ->map(function ($p) use ($tz) {
            $dt = $p->created_at ? \Carbon\Carbon::parse($p->created_at)->timezone($tz) : null;
            return ['ts' => $dt ? $dt->timestamp : 0];
        })
        ->values();

    $rejectedProductsPayload = $rejectedProducts
        ->map(function ($p) use ($tz) {
            $dt = $p->created_at ? \Carbon\Carbon::parse($p->created_at)->timezone($tz) : null;
            return ['ts' => $dt ? $dt->timestamp : 0];
        })
        ->values();

    $usersPayload = $users
        ->map(function ($u) use ($tz) {
            $dt = $u->created_at ? \Carbon\Carbon::parse($u->created_at)->timezone($tz) : null;
            return ['ts' => $dt ? $dt->timestamp : 0];
        })
        ->values();
@endphp

<div class="max-w-5xl mx-auto w-full px-1 sm:px-2">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard Admin</h1>
            <p class="text-sm text-slate-600">Moderasi barang dagangan, pantau daftar pengguna, dan kelola seluruh barang.</p>
        </div>
    </div>

    {{-- Notifikasi Sukses / Error --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CONTAINER PANEL --}}
    <div id="admin-panels-wrapper" class="relative overflow-hidden">

        {{-- 0. RINGKASAN STATISTIK + LINE CHART AKUMULASI --}}
        <section
            id="ringkasan"
            data-admin-panel="ringkasan"
            data-admin-panel-index="0"
            class="admin-tab-panel space-y-5"
        >
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border-2 border-slate-900 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Menunggu Konfirmasi</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $pendingProducts->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Barang berstatus pending</p>
                </div>

                <div class="bg-white rounded-2xl border-2 border-slate-900 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Barang Disetujui</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">
                        {{ $approvedProducts->count() }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Akumulasi barang pernah disetujui</p>
                </div>

                <div class="bg-white rounded-2xl border-2 border-slate-900 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Barang Ditolak</p>
                    <p class="mt-2 text-3xl font-bold text-red-600">
                        {{ $rejectedProducts->count() }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Akumulasi barang pernah ditolak</p>
                </div>

                <div class="bg-white rounded-2xl border-2 border-slate-900 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pengguna</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $users->count() }}</p>
                    <p class="mt-1 text-xs text-slate-500">Mahasiswa terdaftar</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border-2 border-slate-900 px-5 py-3.5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Grafik Akumulasi Pertumbuhan</h2>
                    <p class="text-xs text-slate-500">Filter rentang waktu akumulasi barang dan pengguna.</p>
                </div>
                <div class="inline-flex items-center gap-1 p-1 rounded-xl bg-slate-100 border border-slate-200 self-start sm:self-auto" role="group" aria-label="Filter rentang waktu line chart">
                    <button type="button" data-chart-range="day" class="chart-range-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 transition-colors cursor-pointer">
                        Sehari
                    </button>
                    <button type="button" data-chart-range="week" class="chart-range-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 text-white shadow-2xs transition-colors cursor-pointer">
                        Seminggu
                    </button>
                    <button type="button" data-chart-range="month" class="chart-range-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 transition-colors cursor-pointer">
                        Sebulan
                    </button>
                    <button type="button" data-chart-range="year" class="chart-range-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 transition-colors cursor-pointer">
                        Setahun
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border-2 border-slate-900 p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-2 mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Akumulasi Barang</h3>
                            <p id="productsChartSubtitle" class="text-xs text-slate-500">Rentang: Seminggu (7 hari terakhir)</p>
                        </div>
                        <div class="flex items-center gap-3 text-xs font-semibold shrink-0">
                            <span class="inline-flex items-center gap-1.5 text-emerald-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 inline-block"></span>
                                Disetujui
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-red-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-600 inline-block"></span>
                                Ditolak
                            </span>
                        </div>
                    </div>
                    <div class="relative h-64 w-full">
                        <canvas id="productsAccumulationChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border-2 border-slate-900 p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-2 mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Akumulasi Pengguna</h3>
                            <p id="usersChartSubtitle" class="text-xs text-slate-500">Rentang: Seminggu (7 hari terakhir)</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-800 shrink-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-900 inline-block"></span>
                            Total Pengguna
                        </span>
                    </div>
                    <div class="relative h-64 w-full">
                        <canvas id="usersAccumulationChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        {{-- 1. ANTREAN MODERASI BARANG --}}
        <section
            id="moderasi-barang"
            data-admin-panel="moderasi"
            data-admin-panel-index="1"
            class="admin-tab-panel hidden bg-white rounded-2xl border-2 border-slate-900 shadow-sm overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Antrean Moderasi Barang</h2>
                    <p class="text-xs text-slate-600">Klik tombol Detail untuk memeriksa informasi lengkap sebelum konfirmasi atau menolak barang.</p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-900 text-white">
                    {{ $pendingProducts->count() }} Pending
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Barang</th>
                            <th class="px-6 py-3 text-left font-semibold">Penjual</th>
                            <th class="px-6 py-3 text-left font-semibold">Kategori &amp; Harga</th>
                            <th class="px-6 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($pendingProducts as $product)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($product->foto)
                                            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_barang }}" class="h-12 w-12 rounded-lg object-cover border border-slate-200 flex-shrink-0">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] text-slate-400 flex-shrink-0">
                                                No Img
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $product->nama_barang }}</div>
                                            <div class="text-xs text-slate-500">{{ $product->titik_lokasi }} &middot; {{ $product->metode_serah_terima }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-slate-900">{{ $product->user->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $product->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs font-medium text-slate-600">{{ $product->category->nama_kategori ?? $product->category->name ?? '-' }}</div>
                                    <div class="font-bold text-slate-900 mt-0.5">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
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
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">
                                    Tidak ada barang yang menunggu konfirmasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- 2. DAFTAR PENGGUNA --}}
        <section
            id="daftar-pengguna"
            data-admin-panel="pengguna"
            data-admin-panel-index="2"
            class="admin-tab-panel hidden bg-white rounded-2xl border-2 border-slate-900 shadow-sm overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Daftar Pengguna</h2>
                    <p class="text-xs text-slate-600">Daftar akun mahasiswa terdaftar beserta tanggal bergabung.</p>
                </div>
                <span id="usersFilteredCountBadge" class="px-3 py-1 text-xs font-bold rounded-full bg-slate-900 text-white">
                    {{ $users->count() }} pengguna
                </span>
            </div>

            <div class="px-6 py-3.5 border-b border-slate-200 bg-white flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="search"
                        id="userSearchInput"
                        placeholder="Cari nama, email, atau nomor WhatsApp..."
                        class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 bg-slate-50 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all"
                    >
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <label for="userSortField" class="text-xs font-semibold text-slate-600 whitespace-nowrap">
                        Urutkan:
                    </label>
                    <select
                        id="userSortField"
                        class="px-3 py-2 text-xs sm:text-sm font-medium text-slate-800 bg-slate-50 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white cursor-pointer"
                    >
                        <option value="name">Nama Pengguna</option>
                        <option value="joined" selected>Tanggal Bergabung</option>
                    </select>

                    <button
                        type="button"
                        id="userSortDirectionBtn"
                        data-direction="desc"
                        title="Urutan: Menurun (DESC) — Klik untuk mengubah ASC / DESC"
                        aria-label="Ubah arah urutan ASC atau DESC"
                        class="w-9 h-9 rounded-xl border border-slate-300 bg-slate-900 hover:bg-slate-800 text-white flex items-center justify-center transition-colors cursor-pointer shrink-0"
                    >
                        <svg id="userSortArrowIcon" class="w-4 h-4 transition-transform duration-200 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold">Nama</th>
                            <th class="px-5 py-3 text-left font-semibold">Email</th>
                            <th class="px-5 py-3 text-left font-semibold">No. WhatsApp</th>
                            <th class="px-5 py-3 text-left font-semibold">Tanggal Bergabung</th>
                            <th class="px-5 py-3 text-left font-semibold">Barang</th>
                            <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" class="divide-y divide-slate-200 bg-white">
                        @forelse($users as $user)
                            @php
                                $joinedDt = $user->created_at
                                    ? \Carbon\Carbon::parse($user->created_at)->timezone($tz)
                                    : null;
                                $joinedFormatted = $joinedDt
                                    ? ($joinedDt->day . ' ' . ($bulanIndoShort[$joinedDt->month] ?? $joinedDt->format('M')) . ' ' . $joinedDt->year)
                                    : '-';
                                $joinedTimestamp = $joinedDt ? $joinedDt->timestamp : 0;
                            @endphp
                            <tr
                                class="user-data-row hover:bg-slate-50/70 transition-colors"
                                data-name="{{ mb_strtolower($user->name ?? '') }}"
                                data-email="{{ mb_strtolower($user->email ?? '') }}"
                                data-wa="{{ mb_strtolower($user->no_whatsapp ?? '') }}"
                                data-joined="{{ $joinedTimestamp }}"
                            >
                                <td class="px-5 py-3.5 font-semibold text-slate-900 whitespace-nowrap">{{ $user->name }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $user->email }}</td>
                                <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">{{ $user->no_whatsapp ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap text-xs">
                                    {{ $joinedFormatted }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-900">
                                        {{ $user->products_count ?? 0 }} barang
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button
                                            type="button"
                                            onclick="openWarningModal(this)"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-warn-url="{{ route('admin.users.warn', $user->id) }}"
                                            title="Beri Peringatan Pelanggaran"
                                            aria-label="Beri Peringatan Pelanggaran"
                                            class="p-2 rounded-lg text-slate-900 hover:bg-slate-100 border border-slate-300 transition-colors cursor-pointer"
                                        >
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <button
                                            type="button"
                                            onclick="openAdminMessageModal(this)"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-message-url="{{ route('admin.users.message', $user->id) }}"
                                            title="Kirim Pesan Admin"
                                            aria-label="Kirim Pesan Admin"
                                            class="p-2 rounded-lg text-slate-900 hover:bg-slate-100 border border-slate-300 transition-colors cursor-pointer"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="usersInitialEmptyRow">
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                    Belum ada pengguna terdaftar.
                                </td>
                            </tr>
                        @endforelse

                        <tr id="usersSearchEmptyRow" class="hidden">
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                Tidak ada pengguna yang cocok dengan kata kunci pencarian.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="usersPaginationBar" class="px-6 py-3.5 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p id="usersPaginationInfo" class="text-xs text-slate-600">
                    Menampilkan 0 - 0 dari 0 pengguna
                </p>

                <div class="flex items-center gap-1.5">
                    <button
                        type="button"
                        id="usersPrevPageBtn"
                        class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:pointer-events-none transition-colors cursor-pointer"
                    >
                        &larr; Sebelumnya
                    </button>

                    <div id="usersPageNumbers" class="flex items-center gap-1"></div>

                    <button
                        type="button"
                        id="usersNextPageBtn"
                        class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:pointer-events-none transition-colors cursor-pointer"
                    >
                        Selanjutnya &rarr;
                    </button>
                </div>
            </div>
        </section>

        {{-- 3. DAFTAR SEMUA BARANG --}}
        <section
            id="daftar-barang"
            data-admin-panel="barang"
            data-admin-panel-index="3"
            class="admin-tab-panel hidden bg-white rounded-2xl border-2 border-slate-900 shadow-sm overflow-hidden"
        >
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Daftar Barang</h2>
                    <p class="text-xs text-slate-600">Seluruh barang yang diunggah beserta tanggal bergabung pengguna, status moderasi, dan waktu upload.</p>
                </div>
                <span id="productsFilteredCountBadge" class="px-3 py-1 text-xs font-bold rounded-full bg-slate-900 text-white">
                    {{ $allProducts->count() }} barang
                </span>
            </div>

            <div class="px-6 py-3.5 border-b border-slate-200 bg-white flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="search"
                        id="productUserSearchInput"
                        placeholder="Cari nama pengguna..."
                        class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 bg-slate-50 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition-all"
                    >
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <label for="productSortField" class="text-xs font-semibold text-slate-600 whitespace-nowrap">
                        Urutkan:
                    </label>
                    <select
                        id="productSortField"
                        class="px-3 py-2 text-xs sm:text-sm font-medium text-slate-800 bg-slate-50 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white cursor-pointer"
                    >
                        <option value="product_name">Nama Produk</option>
                        <option value="user_name">Nama Pengguna</option>
                        <option value="uploaded_at" selected>Tanggal Upload</option>
                    </select>

                    <button
                        type="button"
                        id="productSortDirectionBtn"
                        data-direction="desc"
                        title="Urutan: Menurun (DESC) — Klik untuk mengubah ASC / DESC"
                        aria-label="Ubah arah urutan barang ASC atau DESC"
                        class="w-9 h-9 rounded-xl border border-slate-300 bg-slate-900 hover:bg-slate-800 text-white flex items-center justify-center transition-colors cursor-pointer shrink-0"
                    >
                        <svg id="productSortArrowIcon" class="w-4 h-4 transition-transform duration-200 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold">Nama Produk</th>
                            <th class="px-5 py-3 text-left font-semibold">Nama Pengguna</th>
                            <th class="px-5 py-3 text-left font-semibold">Tgl. Bergabung</th>
                            <th class="px-5 py-3 text-left font-semibold">Harga</th>
                            <th class="px-5 py-3 text-left font-semibold">Status</th>
                            <th class="px-5 py-3 text-left font-semibold">Tgl. Upload</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody" class="divide-y divide-slate-200 bg-white">
                        @forelse($allProducts as $product)
                            @php
                                $userJoinedDt = optional($product->user)->created_at
                                    ? \Carbon\Carbon::parse($product->user->created_at)->timezone($tz)
                                    : null;
                                $userJoinedFormatted = $userJoinedDt
                                    ? ($userJoinedDt->day . ' ' . ($bulanIndoShort[$userJoinedDt->month] ?? $userJoinedDt->format('M')) . ' ' . $userJoinedDt->year)
                                    : '-';

                                $uploadDt = $product->created_at
                                    ? \Carbon\Carbon::parse($product->created_at)->timezone($tz)
                                    : null;
                                $uploadFormatted = $uploadDt
                                    ? ($uploadDt->day . ' ' . ($bulanIndoShort[$uploadDt->month] ?? $uploadDt->format('M')) . ' ' . $uploadDt->year . ' ' . $uploadDt->format('H:i'))
                                    : '-';
                                $uploadTimestamp = $uploadDt ? $uploadDt->timestamp : 0;
                            @endphp
                            <tr
                                class="product-data-row hover:bg-slate-50/70 transition-colors"
                                data-product-name="{{ mb_strtolower($product->nama_barang ?? '') }}"
                                data-user-name="{{ mb_strtolower($product->user->name ?? '') }}"
                                data-uploaded-at="{{ $uploadTimestamp }}"
                            >
                                <td class="px-5 py-3.5 font-semibold text-slate-900">
                                    <div>{{ $product->nama_barang }}</div>
                                    @if($product->status === 'rejected' && $product->rejection_reason)
                                        <div class="text-[11px] font-normal text-red-600 mt-0.5">
                                            Alasan: {{ $product->rejection_reason }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 whitespace-nowrap">{{ $product->user->name ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap text-xs">{{ $userJoinedFormatted }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-900 whitespace-nowrap">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if(in_array($product->status, ['tersedia', 'approved']))
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Disetujui</span>
                                    @elseif($product->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-900">Pending</span>
                                    @elseif($product->status === 'rejected')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                    @elseif($product->status === 'dihapus')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">Dihapus</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">{{ ucfirst($product->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap text-xs">{{ $uploadFormatted }}</td>
                            </tr>
                        @empty
                            <tr id="productsInitialEmptyRow">
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                    Belum ada data barang.
                                </td>
                            </tr>
                        @endforelse

                        <tr id="productsSearchEmptyRow" class="hidden">
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                Tidak ada barang yang cocok dengan nama pengguna tersebut.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="productsPaginationBar" class="px-6 py-3.5 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p id="productsPaginationInfo" class="text-xs text-slate-600">
                    Menampilkan 0 - 0 dari 0 barang
                </p>

                <div class="flex items-center gap-1.5">
                    <button
                        type="button"
                        id="productsPrevPageBtn"
                        class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:pointer-events-none transition-colors cursor-pointer"
                    >
                        &larr; Sebelumnya
                    </button>

                    <div id="productsPageNumbers" class="flex items-center gap-1"></div>

                    <button
                        type="button"
                        id="productsNextPageBtn"
                        class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:pointer-events-none transition-colors cursor-pointer"
                    >
                        Selanjutnya &rarr;
                    </button>
                </div>
            </div>
        </section>

    </div>
</div>

{{-- POP-UP MODAL DETAIL & MODERASI BARANG --}}
<div id="moderasiModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border-2 border-slate-900">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Moderasi Produk</span>
                <h3 id="modalNamaBarang" class="text-lg font-bold text-slate-900">Detail Barang</h3>
            </div>
            <button type="button" onclick="closeModerasiModal()" class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center h-56">
                    <img id="modalFoto" src="" alt="Foto Produk" class="w-full h-full object-cover hidden">
                    <span id="modalNoFoto" class="text-xs text-slate-400">Tidak ada foto produk</span>
                </div>

                <div class="space-y-3 text-sm">
                    <div>
                        <span id="modalKategori" class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-900">
                            Kategori
                        </span>
                        <p id="modalHarga" class="mt-2 text-2xl font-extrabold text-slate-900">Rp 0</p>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-1 text-xs">
                        <p class="font-semibold text-slate-800">Penjual: <span id="modalPenjual" class="font-normal text-slate-600">-</span></p>
                        <p class="font-semibold text-slate-800">Email: <span id="modalEmail" class="font-normal text-slate-600">-</span></p>
                        <p class="font-semibold text-slate-800">WhatsApp: <span id="modalWa" class="font-normal text-slate-600">-</span></p>
                    </div>

                    <div class="text-xs text-slate-600 space-y-1">
                        <p><span class="font-semibold text-slate-800">Serah Terima:</span> <span id="modalMetode">-</span></p>
                        <p><span class="font-semibold text-slate-800">Titik Lokasi:</span> <span id="modalLokasi">-</span></p>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Deskripsi Barang</h4>
                <div id="modalDeskripsi" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 whitespace-pre-line">
                    -
                </div>
            </div>

            <div id="modalVideoWrapper" class="hidden">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Video Review</h4>
                <video id="modalVideo" controls class="w-full max-h-64 rounded-xl border border-slate-200 bg-black"></video>
            </div>

            <div class="pt-4 border-t border-slate-200 space-y-4">
                <form id="modalApproveForm" action="" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition-colors cursor-pointer">
                        Konfirmasi &amp; Tampilkan ke Katalog
                    </button>
                </form>

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
                        class="w-full px-3 py-2 rounded-lg border border-red-300 bg-white text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-red-500"
                    ></textarea>
                    <button type="submit" class="w-full py-2 px-4 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition-colors cursor-pointer">
                        Tolak Barang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- POP-UP MODAL PERINGATAN PELANGGARAN PENGGUNA --}}
<div id="warningModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full border-2 border-slate-900 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-slate-900 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Peringatan Pelanggaran</h3>
                    <p id="warningTargetUser" class="text-xs text-slate-600">-</p>
                </div>
            </div>
            <button type="button" onclick="closeWarningModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="warningForm" action="" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label for="warningReasonInput" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Alasan Pelanggaran <span class="text-red-600">*</span>
                </label>
                <textarea
                    id="warningReasonInput"
                    name="reason"
                    rows="3"
                    required
                    placeholder="Tuliskan alasan pelanggaran pengguna..."
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900"
                ></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeWarningModal()" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-xs cursor-pointer">
                    Kirim Peringatan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- POP-UP MODAL KIRIM PESAN ADMIN --}}
<div id="adminMessageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full border-2 border-slate-900 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Kirim Pesan Resmi Admin</h3>
                <p id="messageTargetUser" class="text-xs text-slate-600">-</p>
            </div>
            <button type="button" onclick="closeAdminMessageModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="adminMessageForm" action="" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label for="adminMessageBody" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Isi Pesan <span class="text-red-600">*</span>
                </label>
                <textarea
                    id="adminMessageBody"
                    name="body"
                    rows="3"
                    required
                    placeholder="Tulis pesan untuk pengguna..."
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900"
                ></textarea>
                <p class="mt-1.5 text-[11px] text-slate-600">
                    Pesan ini akan tampil di halaman chat mahasiswa dan tidak dapat dibalas.
                </p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeAdminMessageModal()" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-xs cursor-pointer">
                    Kirim Pesan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. TRANSISI 2-FASE HALUS ANTAR PANEL
            const navButtons = Array.from(document.querySelectorAll('.admin-nav-btn'));
            const panels = Array.from(document.querySelectorAll('.admin-tab-panel'));

            if (navButtons.length && panels.length) {
                let currentIndex = 0;
                let transitionTimer = null;
                const outDuration = 140;
                const inDuration = 180;

                function setActiveNav(targetIndex) {
                    navButtons.forEach((btn) => {
                        const btnIndex = Number(btn.dataset.adminTabIndex);
                        const isActive = btnIndex === targetIndex;
                        const badge = btn.querySelector('.admin-badge-count');

                        btn.classList.toggle('bg-slate-900', isActive);
                        btn.classList.toggle('text-white', isActive);
                        btn.classList.toggle('font-semibold', isActive);
                        btn.classList.toggle('shadow-xs', isActive);

                        btn.classList.toggle('text-slate-900', !isActive);
                        btn.classList.toggle('font-medium', !isActive);
                        btn.classList.toggle('hover:bg-slate-100', !isActive);

                        if (badge) {
                            badge.classList.toggle('bg-white', isActive);
                            badge.classList.toggle('text-slate-900', isActive);
                            badge.classList.toggle('bg-slate-900', !isActive);
                            badge.classList.toggle('text-white', !isActive);
                        }
                    });
                }

                function switchToTab(nextIndex, instant = false) {
                    if (nextIndex < 0 || nextIndex >= panels.length) return;
                    if (nextIndex === currentIndex && !instant) return;

                    if (transitionTimer) {
                        clearTimeout(transitionTimer);
                        transitionTimer = null;
                    }

                    const currentPanel = panels[currentIndex];
                    const nextPanel = panels[nextIndex];
                    const movingForward = nextIndex > currentIndex;

                    setActiveNav(nextIndex);

                    if (instant) {
                        panels.forEach((panel, idx) => {
                            panel.classList.toggle('hidden', idx !== nextIndex);
                            panel.style.transition = '';
                            panel.style.transform = '';
                            panel.style.opacity = '';
                        });
                        currentIndex = nextIndex;
                        return;
                    }

                    panels.forEach((panel, idx) => {
                        if (idx !== currentIndex) {
                            panel.classList.add('hidden');
                            panel.style.transition = '';
                            panel.style.transform = '';
                            panel.style.opacity = '';
                        }
                    });

                    currentPanel.style.transition = `transform ${outDuration}ms ease-out, opacity ${outDuration}ms ease-out`;
                    currentPanel.style.opacity = '0';
                    currentPanel.style.transform = movingForward ? 'translateX(-14px)' : 'translateX(14px)';

                    transitionTimer = setTimeout(() => {
                        currentPanel.classList.add('hidden');
                        currentPanel.style.transition = '';
                        currentPanel.style.transform = '';
                        currentPanel.style.opacity = '';

                        nextPanel.classList.remove('hidden');
                        nextPanel.style.transition = 'none';
                        nextPanel.style.opacity = '0';
                        nextPanel.style.transform = movingForward ? 'translateX(14px)' : 'translateX(-14px)';

                        void nextPanel.offsetWidth;

                        nextPanel.style.transition = `transform ${inDuration}ms cubic-bezier(0.22, 1, 0.36, 1), opacity ${inDuration}ms ease-out`;
                        nextPanel.style.opacity = '1';
                        nextPanel.style.transform = 'translateX(0)';

                        currentIndex = nextIndex;

                        transitionTimer = setTimeout(() => {
                            nextPanel.style.transition = '';
                            nextPanel.style.transform = '';
                            nextPanel.style.opacity = '';
                            transitionTimer = null;
                        }, inDuration);
                    }, outDuration);
                }

                document.querySelectorAll('[data-admin-tab-index]').forEach((trigger) => {
                    trigger.addEventListener('click', function (e) {
                        const targetIndex = Number(this.dataset.adminTabIndex);
                        const href = this.getAttribute('href') || '';
                        const hashIndex = href.indexOf('#');
                        const hash = hashIndex !== -1 ? href.slice(hashIndex) : '#ringkasan';

                        if (window.location.pathname === new URL(this.href, window.location.origin).pathname) {
                            e.preventDefault();
                            history.replaceState(null, '', hash);
                            switchToTab(targetIndex, false);
                        }
                    });
                });

                const hashMap = {
                    '#ringkasan': 0,
                    '#moderasi-barang': 1,
                    '#daftar-pengguna': 2,
                    '#daftar-barang': 3,
                };

                if (window.location.hash && Object.prototype.hasOwnProperty.call(hashMap, window.location.hash)) {
                    switchToTab(hashMap[window.location.hash], true);
                }
            }

            // 2. LINE CHART AKUMULASI BARANG (2 LINE) & PENGGUNA (1 LINE) DENGAN FILTER PERIODE
            const approvedItems = @json($approvedProductsPayload);
            const rejectedItems = @json($rejectedProductsPayload);
            const userItems = @json($usersPayload);

            const productsCanvas = document.getElementById('productsAccumulationChart');
            const usersCanvas = document.getElementById('usersAccumulationChart');
            const rangeButtons = Array.from(document.querySelectorAll('.chart-range-btn'));
            const productsSubtitle = document.getElementById('productsChartSubtitle');
            const usersSubtitle = document.getElementById('usersChartSubtitle');

            if (productsCanvas && usersCanvas && typeof Chart !== 'undefined') {
                const bulanShort = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                function buildBuckets(rangeKey) {
                const now = new Date();
                const buckets = [];

                if (rangeKey === 'day') {
                    const endOfHour = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours(), 0, 0, 0);
                    for (let i = 11; i >= 0; i--) {
                        const end = new Date(endOfHour.getTime() - i * 2 * 3600 * 1000);
                        const hh = String(end.getHours()).padStart(2, '0');
                        buckets.push({
                            label: `${hh}:00`,
                            endTs: Math.floor(end.getTime() / 1000),
                        });
                    }
                    return { buckets, labelText: 'Rentang: Sehari (24 jam terakhir)' };
                }

                if (rangeKey === 'month') {
                    const todayEnd = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59, 999);
                    for (let i = 9; i >= 0; i--) {
                        const end = new Date(todayEnd.getTime() - i * 3 * 86400 * 1000);
                        buckets.push({
                            label: `${end.getDate()} ${bulanShort[end.getMonth()]}`,
                            endTs: Math.floor(end.getTime() / 1000),
                        });
                    }
                    return { buckets, labelText: 'Rentang: Sebulan (30 hari terakhir)' };
                }

                if (rangeKey === 'year') {
                    const currentYear = now.getFullYear();
                    for (let m = 0; m < 12; m++) {
                        const endOfMonth = new Date(currentYear, m + 1, 0, 23, 59, 59, 999);
                        buckets.push({
                            label: bulanShort[m],
                            endTs: Math.floor(endOfMonth.getTime() / 1000),
                        });
                    }
                    return { buckets, labelText: 'Rentang: Setahun (Jan – Des)' };
                }

                const todayEnd = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59, 999);
                for (let i = 6; i >= 0; i--) {
                    const dayEnd = new Date(todayEnd.getTime() - i * 86400 * 1000);
                    buckets.push({
                        label: `${dayEnd.getDate()} ${bulanShort[dayEnd.getMonth()]}`,
                        endTs: Math.floor(dayEnd.getTime() / 1000),
                    });
                }
                return { buckets, labelText: 'Rentang: Seminggu (7 hari terakhir)' };
            }

                function computeCumulativeSeries(items, buckets) {
                    return buckets.map((b) => {
                        return items.reduce((acc, item) => (item.ts > 0 && item.ts <= b.endTs ? acc + 1 : acc), 0);
                    });
                }

                const initial = buildBuckets('week');

                const productsChart = new Chart(productsCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: initial.buckets.map((b) => b.label),
                        datasets: [
                            {
                                label: 'Barang Disetujui',
                                data: computeCumulativeSeries(approvedItems, initial.buckets),
                                borderColor: '#059669',
                                backgroundColor: 'rgba(5, 150, 105, 0.12)',
                                borderWidth: 2.5,
                                tension: 0.3,
                                pointRadius: 3.5,
                                pointHoverRadius: 5,
                                fill: false,
                            },
                            {
                                label: 'Barang Ditolak',
                                data: computeCumulativeSeries(rejectedItems, initial.buckets),
                                borderColor: '#dc2626',
                                backgroundColor: 'rgba(220, 38, 38, 0.12)',
                                borderWidth: 2.5,
                                tension: 0.3,
                                pointRadius: 3.5,
                                pointHoverRadius: 5,
                                fill: false,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, color: '#475569', font: { size: 11 } },
                                grid: { color: '#e2e8f0' },
                            },
                            x: {
                                ticks: { color: '#475569', font: { size: 11 } },
                                grid: { display: false },
                            },
                        },
                    },
                });

                const usersChart = new Chart(usersCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: initial.buckets.map((b) => b.label),
                        datasets: [
                            {
                                label: 'Akumulasi Pengguna',
                                data: computeCumulativeSeries(userItems, initial.buckets),
                                borderColor: '#0f172a',
                                backgroundColor: 'rgba(15, 23, 42, 0.12)',
                                borderWidth: 2.5,
                                tension: 0.3,
                                pointRadius: 3.5,
                                pointHoverRadius: 5,
                                fill: true,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, color: '#475569', font: { size: 11 } },
                                grid: { color: '#e2e8f0' },
                            },
                            x: {
                                ticks: { color: '#475569', font: { size: 11 } },
                                grid: { display: false },
                            },
                        },
                    },
                });

                function applyChartRange(rangeKey) {
                    const { buckets, labelText } = buildBuckets(rangeKey);
                    const labels = buckets.map((b) => b.label);

                    productsChart.data.labels = labels;
                    productsChart.data.datasets[0].data = computeCumulativeSeries(approvedItems, buckets);
                    productsChart.data.datasets[1].data = computeCumulativeSeries(rejectedItems, buckets);
                    productsChart.update();

                    usersChart.data.labels = labels;
                    usersChart.data.datasets[0].data = computeCumulativeSeries(userItems, buckets);
                    usersChart.update();

                    if (productsSubtitle) productsSubtitle.textContent = labelText;
                    if (usersSubtitle) usersSubtitle.textContent = labelText;

                    rangeButtons.forEach((btn) => {
                        const active = btn.dataset.chartRange === rangeKey;
                        btn.classList.toggle('bg-slate-900', active);
                        btn.classList.toggle('text-white', active);
                        btn.classList.toggle('shadow-2xs', active);
                        btn.classList.toggle('text-slate-700', !active);
                    });
                }

                rangeButtons.forEach((btn) => {
                    btn.addEventListener('click', () => applyChartRange(btn.dataset.chartRange));
                });
            }

            // 3. PENCARIAN, SORTING TOGGLE (ASC/DESC), & PAGINATION (10 DATA) DAFTAR PENGGUNA
            const usersTableBody = document.getElementById('usersTableBody');
            const userSearchInput = document.getElementById('userSearchInput');
            const userSortFieldSelect = document.getElementById('userSortField');
            const userSortDirBtn = document.getElementById('userSortDirectionBtn');
            const userSortArrowIcon = document.getElementById('userSortArrowIcon');
            const usersSearchEmptyRow = document.getElementById('usersSearchEmptyRow');
            const usersCountBadge = document.getElementById('usersFilteredCountBadge');
            const usersPaginationInfo = document.getElementById('usersPaginationInfo');
            const usersPrevBtn = document.getElementById('usersPrevPageBtn');
            const usersNextBtn = document.getElementById('usersNextPageBtn');
            const usersPageNumbers = document.getElementById('usersPageNumbers');

            if (usersTableBody && userSearchInput && userSortFieldSelect && userSortDirBtn) {
                const allUserRows = Array.from(usersTableBody.querySelectorAll('.user-data-row'));
                const perPage = 10;
                let currentPage = 1;
                let sortDirection = userSortDirBtn.dataset.direction === 'asc' ? 'asc' : 'desc';

                function syncUserDirectionUI() {
                    const isAsc = sortDirection === 'asc';
                    userSortDirBtn.dataset.direction = sortDirection;
                    userSortDirBtn.title = isAsc
                        ? 'Urutan: Menaik (ASC) — Klik untuk jadi DESC'
                        : 'Urutan: Menurun (DESC) — Klik untuk jadi ASC';

                    if (userSortArrowIcon) {
                        userSortArrowIcon.classList.toggle('rotate-180', !isAsc);
                    }
                }

                function renderUsersTable() {
                    const keyword = userSearchInput.value.trim().toLowerCase();
                    const sortField = userSortFieldSelect.value;

                    syncUserDirectionUI();

                    const filteredRows = allUserRows.filter((row) => {
                        if (!keyword) return true;
                        const name = row.dataset.name || '';
                        const email = row.dataset.email || '';
                        const wa = row.dataset.wa || '';
                        return name.includes(keyword) || email.includes(keyword) || wa.includes(keyword);
                    });

                    filteredRows.sort((a, b) => {
                        let comparison = 0;
                        if (sortField === 'name') {
                            comparison = (a.dataset.name || '').localeCompare(b.dataset.name || '', 'id');
                        } else {
                            comparison = Number(a.dataset.joined || 0) - Number(b.dataset.joined || 0);
                        }
                        return sortDirection === 'asc' ? comparison : -comparison;
                    });

                    filteredRows.forEach((row) => usersTableBody.insertBefore(row, usersSearchEmptyRow));

                    const totalItems = filteredRows.length;
                    const totalPages = Math.max(1, Math.ceil(totalItems / perPage));
                    if (currentPage > totalPages) currentPage = totalPages;

                    const startIdx = (currentPage - 1) * perPage;
                    const endIdx = Math.min(startIdx + perPage, totalItems);

                    allUserRows.forEach((row) => row.classList.add('hidden'));
                    filteredRows.slice(startIdx, endIdx).forEach((row) => row.classList.remove('hidden'));

                    if (usersSearchEmptyRow) {
                        usersSearchEmptyRow.classList.toggle('hidden', !(allUserRows.length > 0 && totalItems === 0));
                    }

                    if (usersCountBadge) {
                        usersCountBadge.textContent = `${totalItems} pengguna`;
                    }

                    if (usersPaginationInfo) {
                        usersPaginationInfo.textContent = totalItems === 0
                            ? 'Menampilkan 0 dari 0 pengguna'
                            : `Menampilkan ${startIdx + 1} - ${endIdx} dari ${totalItems} pengguna`;
                    }

                    if (usersPrevBtn) usersPrevBtn.disabled = currentPage <= 1;
                    if (usersNextBtn) usersNextBtn.disabled = currentPage >= totalPages || totalItems === 0;

                    if (usersPageNumbers) {
                        usersPageNumbers.innerHTML = '';
                        for (let page = 1; page <= totalPages; page++) {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.textContent = String(page);
                            btn.className = page === currentPage
                                ? 'w-8 h-8 rounded-lg bg-slate-900 text-white text-xs font-bold shadow-2xs cursor-pointer'
                                : 'w-8 h-8 rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 text-xs font-semibold cursor-pointer';
                            btn.addEventListener('click', () => {
                                currentPage = page;
                                renderUsersTable();
                            });
                            usersPageNumbers.appendChild(btn);
                        }
                    }
                }

                userSearchInput.addEventListener('input', () => {
                    currentPage = 1;
                    renderUsersTable();
                });

                userSortFieldSelect.addEventListener('change', () => {
                    currentPage = 1;
                    renderUsersTable();
                });

                userSortDirBtn.addEventListener('click', () => {
                    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                    currentPage = 1;
                    renderUsersTable();
                });

                if (usersPrevBtn) {
                    usersPrevBtn.addEventListener('click', () => {
                        if (currentPage > 1) {
                            currentPage--;
                            renderUsersTable();
                        }
                    });
                }

                if (usersNextBtn) {
                    usersNextBtn.addEventListener('click', () => {
                        currentPage++;
                        renderUsersTable();
                    });
                }

                renderUsersTable();
            }

            // 4. PENCARIAN NAMA PENGGUNA, SORTING TOGGLE (ASC/DESC), & PAGINATION (10 DATA) DAFTAR BARANG
            const productsTableBody = document.getElementById('productsTableBody');
            const productSearchInput = document.getElementById('productUserSearchInput');
            const productSortFieldSelect = document.getElementById('productSortField');
            const productSortDirBtn = document.getElementById('productSortDirectionBtn');
            const productSortArrowIcon = document.getElementById('productSortArrowIcon');
            const productsSearchEmptyRow = document.getElementById('productsSearchEmptyRow');
            const productsCountBadge = document.getElementById('productsFilteredCountBadge');
            const productsPaginationInfo = document.getElementById('productsPaginationInfo');
            const productsPrevBtn = document.getElementById('productsPrevPageBtn');
            const productsNextBtn = document.getElementById('productsNextPageBtn');
            const productsPageNumbers = document.getElementById('productsPageNumbers');

            if (productsTableBody && productSearchInput && productSortFieldSelect && productSortDirBtn) {
                const allProductRows = Array.from(productsTableBody.querySelectorAll('.product-data-row'));
                const perPage = 10;
                let currentPage = 1;
                let productSortDirection = productSortDirBtn.dataset.direction === 'asc' ? 'asc' : 'desc';

                function syncProductDirectionUI() {
                    const isAsc = productSortDirection === 'asc';
                    productSortDirBtn.dataset.direction = productSortDirection;
                    productSortDirBtn.title = isAsc
                        ? 'Urutan: Menaik (ASC) — Klik untuk jadi DESC'
                        : 'Urutan: Menurun (DESC) — Klik untuk jadi ASC';

                    if (productSortArrowIcon) {
                        productSortArrowIcon.classList.toggle('rotate-180', !isAsc);
                    }
                }

                function renderProductsTable() {
                    const keyword = productSearchInput.value.trim().toLowerCase();
                    const sortField = productSortFieldSelect.value;

                    syncProductDirectionUI();

                    const filteredRows = allProductRows.filter((row) => {
                        if (!keyword) return true;
                        const userName = row.dataset.userName || '';
                        return userName.includes(keyword);
                    });

                    filteredRows.sort((a, b) => {
                        let comparison = 0;
                        if (sortField === 'product_name') {
                            comparison = (a.dataset.productName || '').localeCompare(b.dataset.productName || '', 'id');
                        } else if (sortField === 'user_name') {
                            comparison = (a.dataset.userName || '').localeCompare(b.dataset.userName || '', 'id');
                        } else {
                            comparison = Number(a.dataset.uploadedAt || 0) - Number(b.dataset.uploadedAt || 0);
                        }
                        return productSortDirection === 'asc' ? comparison : -comparison;
                    });

                    filteredRows.forEach((row) => productsTableBody.insertBefore(row, productsSearchEmptyRow));

                    const totalItems = filteredRows.length;
                    const totalPages = Math.max(1, Math.ceil(totalItems / perPage));
                    if (currentPage > totalPages) currentPage = totalPages;

                    const startIdx = (currentPage - 1) * perPage;
                    const endIdx = Math.min(startIdx + perPage, totalItems);

                    allProductRows.forEach((row) => row.classList.add('hidden'));
                    filteredRows.slice(startIdx, endIdx).forEach((row) => row.classList.remove('hidden'));

                    if (productsSearchEmptyRow) {
                        productsSearchEmptyRow.classList.toggle('hidden', !(allProductRows.length > 0 && totalItems === 0));
                    }

                    if (productsCountBadge) {
                        productsCountBadge.textContent = `${totalItems} barang`;
                    }

                    if (productsPaginationInfo) {
                        productsPaginationInfo.textContent = totalItems === 0
                            ? 'Menampilkan 0 dari 0 barang'
                            : `Menampilkan ${startIdx + 1} - ${endIdx} dari ${totalItems} barang`;
                    }

                    if (productsPrevBtn) productsPrevBtn.disabled = currentPage <= 1;
                    if (productsNextBtn) productsNextBtn.disabled = currentPage >= totalPages || totalItems === 0;

                    if (productsPageNumbers) {
                        productsPageNumbers.innerHTML = '';
                        for (let page = 1; page <= totalPages; page++) {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.textContent = String(page);
                            btn.className = page === currentPage
                                ? 'w-8 h-8 rounded-lg bg-slate-900 text-white text-xs font-bold shadow-2xs cursor-pointer'
                                : 'w-8 h-8 rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 text-xs font-semibold cursor-pointer';
                            btn.addEventListener('click', () => {
                                currentPage = page;
                                renderProductsTable();
                            });
                            productsPageNumbers.appendChild(btn);
                        }
                    }
                }

                productSearchInput.addEventListener('input', () => {
                    currentPage = 1;
                    renderProductsTable();
                });

                productSortFieldSelect.addEventListener('change', () => {
                    currentPage = 1;
                    renderProductsTable();
                });

                productSortDirBtn.addEventListener('click', () => {
                    productSortDirection = productSortDirection === 'asc' ? 'desc' : 'asc';
                    currentPage = 1;
                    renderProductsTable();
                });

                if (productsPrevBtn) {
                    productsPrevBtn.addEventListener('click', () => {
                        if (currentPage > 1) {
                            currentPage--;
                            renderProductsTable();
                        }
                    });
                }

                if (productsNextBtn) {
                    productsNextBtn.addEventListener('click', () => {
                        currentPage++;
                        renderProductsTable();
                    });
                }

                renderProductsTable();
            }
        });

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

            document.getElementById('modalApproveForm').action = btn.dataset.approveUrl;
            document.getElementById('modalRejectForm').action = btn.dataset.rejectUrl;
            document.getElementById('modalRejectionReason').value = '';

            modal.classList.remove('hidden');
        }

        function closeModerasiModal() {
            const video = document.getElementById('modalVideo');
            if (video) video.pause();
            if (modal) modal.classList.add('hidden');
        }

        const warningModal = document.getElementById('warningModal');

        function openWarningModal(btn) {
            if (!warningModal) return;
            document.getElementById('warningTargetUser').textContent = `${btn.dataset.userName} (${btn.dataset.userEmail})`;
            document.getElementById('warningForm').action = btn.dataset.warnUrl;
            document.getElementById('warningReasonInput').value = '';
            warningModal.classList.remove('hidden');
        }

        function closeWarningModal() {
            if (!warningModal) return;
            warningModal.classList.add('hidden');
        }

        const adminMessageModal = document.getElementById('adminMessageModal');

        function openAdminMessageModal(btn) {
            if (!adminMessageModal) return;
            document.getElementById('messageTargetUser').textContent = `${btn.dataset.userName} (${btn.dataset.userEmail})`;
            document.getElementById('adminMessageForm').action = btn.dataset.messageUrl;
            document.getElementById('adminMessageBody').value = '';
            adminMessageModal.classList.remove('hidden');
        }

        function closeAdminMessageModal() {
            if (!adminMessageModal) return;
            adminMessageModal.classList.add('hidden');
        }

        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModerasiModal();
            });
        }

        if (warningModal) {
            warningModal.addEventListener('click', function (e) {
                if (e.target === warningModal) closeWarningModal();
            });
        }

        if (adminMessageModal) {
            adminMessageModal.addEventListener('click', function (e) {
                if (e.target === adminMessageModal) closeAdminMessageModal();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (modal && !modal.classList.contains('hidden')) closeModerasiModal();
                if (warningModal && !warningModal.classList.contains('hidden')) closeWarningModal();
                if (adminMessageModal && !adminMessageModal.classList.contains('hidden')) closeAdminMessageModal();
            }
        });
    </script>
@endpush