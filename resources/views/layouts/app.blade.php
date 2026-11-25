<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Jual Beli Elektronik Mahasiswa - PCR')</title>

        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            html {
                scroll-behavior: smooth;
            }

            /* Transisi halus antarhalaman (Browser modern) */
            @view-transition {
                navigation: auto;
            }

            ::view-transition-old(root),
            ::view-transition-new(root) {
                animation-duration: 0.22s;
                animation-timing-function: cubic-bezier(0.22, 1, 0.36, 1);
            }

            /* Animasi masuk konten utama */
            @keyframes pageFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(6px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .page-smooth-enter {
                animation: pageFadeIn 0.24s cubic-bezier(0.22, 1, 0.36, 1) both;
            }

            /* Progress bar tipis di atas layar saat pindah halaman */
            #top-nav-progress {
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                width: 0%;
                background: linear-gradient(90deg, #f97316, #fb923c);
                z-index: 9999;
                opacity: 0;
                transition: width 0.25s ease, opacity 0.2s ease;
                pointer-events: none;
            }
        </style>
        @stack('styles')
    </head>
    <body class="min-h-screen bg-gray-50 flex flex-col antialiased text-gray-800">
        <!-- Indikator Progress Pindah Halaman -->
        <div id="top-nav-progress"></div>

        <div class="min-h-screen flex flex-col">
            <!-- Panggil Partial Header -->
            @include('partials.header')

            <!-- Main Content Dinamis -->
            <main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8 page-smooth-enter">
                @yield('content')
            </main>

            <!-- Panggil Partial Footer -->
            @include('partials.footer')
        </div>

        <!-- Pop-up Peringatan Pelanggaran dari Admin -->
        @auth
            @php
                $activeWarning = \App\Models\UserWarning::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->latest()
                    ->first();
            @endphp

            @if($activeWarning)
                <div class="fixed inset-0 z-[99] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full border-2 border-amber-400 overflow-hidden">
                        <div class="px-6 py-4 bg-amber-50 border-b border-amber-200 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Peringatan Admin</span>
                                <h3 class="text-base font-bold text-gray-900">Pemberitahuan Pelanggaran Akun</h3>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <p class="text-xs text-gray-600">
                                Akun Anda mendapatkan peringatan dari admin dengan alasan berikut:
                            </p>

                            <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200 text-sm text-gray-800 whitespace-pre-line">
                                {{ $activeWarning->reason }}
                            </div>

                            <form action="{{ route('mahasiswa.warnings.acknowledge', $activeWarning->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-sm font-bold transition-colors cursor-pointer">
                                    Saya Mengerti &amp; Lanjutkan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Script Prefetch & Transisi Navigasi Smooth -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const progressBar = document.getElementById('top-nav-progress');
                const prefetched = new Set();

                function prefetchUrl(url) {
                    if (!url || prefetched.has(url)) return;
                    if (!url.startsWith(window.location.origin)) return;

                    prefetched.add(url);
                    const link = document.createElement('link');
                    link.rel = 'prefetch';
                    link.href = url;
                    document.head.appendChild(link);
                }

                document.querySelectorAll('a[href]').forEach(function (anchor) {
                    const href = anchor.getAttribute('href');
                    if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

                    // Prefetch saat kursor diarahkan atau layar disentuh
                    anchor.addEventListener('pointerenter', function () {
                        prefetchUrl(anchor.href);
                    }, { passive: true });

                    anchor.addEventListener('touchstart', function () {
                        prefetchUrl(anchor.href);
                    }, { passive: true });

                    // Jalankan top progress bar saat klik link internal
                    anchor.addEventListener('click', function (e) {
                        if (
                            e.defaultPrevented ||
                            e.button !== 0 ||
                            e.metaKey ||
                            e.ctrlKey ||
                            e.shiftKey ||
                            e.altKey ||
                            anchor.target === '_blank'
                        ) {
                            return;
                        }

                        if (anchor.href.startsWith(window.location.origin) && progressBar) {
                            progressBar.style.opacity = '1';
                            progressBar.style.width = '75%';
                        }
                    });
                });

                window.addEventListener('pageshow', function () {
                    if (progressBar) {
                        progressBar.style.width = '100%';
                        setTimeout(function () {
                            progressBar.style.opacity = '0';
                            progressBar.style.width = '0%';
                        }, 200);
                    }
                });
            });
        </script>

        @stack('scripts')
    </body>
</html>