<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jual Beli Elektronik Mahasiswa - PCR')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen antialiased">

    <!-- Panggil Partial Header -->
    @include('partials.header')

    <!-- Main Content Dinamis -->
    <main class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Panggil Partial Footer -->
    @include('partials.footer')

</body>
</html>