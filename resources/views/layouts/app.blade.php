<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Jual Beli Elektronik Mahasiswa - PCR')</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>     
            <!-- Panggil Partial Header -->
            @include('partials.header')

            <!-- Main Content Dinamis -->
            <main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @yield('content')
            </main>

            <!-- Panggil Partial Footer -->
            @include('partials.footer')

        </div>
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
</html>