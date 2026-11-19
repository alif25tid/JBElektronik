<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Jual Beli Elektronik Mahasiswa - PCR')</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed relative flex flex-col antialiased" style="background-image: url('{{ asset('images/background.jpg') }}');">

        <!-- Lapisan Overlay Putih Semi-Transparan (Menutupi background agar teks lebih kontras) -->
        <div class="fixed inset-0 bg-white/70 z-0 pointer-events-none"></div>

        <!-- Bungkus Konten Utama dengan z-index agar berada di atas overlay -->
        <div class="relative z-10 flex flex-col min-h-screen">
            
            <!-- Panggil Partial Header -->
            @include('partials.header')

            <!-- Main Content Dinamis -->
            <main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @yield('content')
            </main>

            <!-- Panggil Partial Footer -->
            @include('partials.footer')

        </div>

    </body>
</html>