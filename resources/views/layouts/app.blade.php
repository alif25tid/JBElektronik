<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jual Beli Elektronik Mahasiswa - PCR')</title>
    
    <!-- 1. CSS Utama (Tailwind via Vite) -->
    @vite(['resources/css/app.css'])
    
    <!-- Tempat CSS tambahan khusus halaman tertentu (opsional) -->
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen antialiased">

    <!-- 2. Panggil Partial Header -->
    @include('partials.header')

    <!-- 3. Main Content Dinamis -->
    <main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- 4. Panggil Partial Footer -->
    @include('partials.footer')

    <!-- 5. JS Utama (Vite) -->
    @vite(['resources/js/app.js'])
    
    <!-- Tempat Script JS tambahan khusus halaman tertentu (opsional) -->
    @stack('scripts')
</body>
</html>