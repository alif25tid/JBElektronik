<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Dashboard Admin - Jualin PCR')</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed relative flex flex-col antialiased" style="background-image: url('{{ asset('images/background.jpg') }}');">

        <!-- Lapisan Overlay Semi-Transparan -->
        <div class="fixed inset-0 bg-slate-100/80 z-0 pointer-events-none"></div>

        <!-- Bungkus Konten Utama -->
        <div class="relative z-10 flex flex-col min-h-screen">

            <!-- Panggil Partial Header Khusus Admin -->
            @include('partials.admin-header')

            <!-- Main Content Dinamis -->
            <main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
                @yield('content')
            </main>

            <!-- Panggil Partial Footer Khusus Admin -->
            @include('partials.admin-footer')

        </div>

        @stack('scripts')
    </body>
</html>