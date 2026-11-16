<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autentikasi - Jualin PCR')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center antialiased">

    <!-- Konten Utama (Form Login/Register) -->
    <main class="w-full max-w-md px-4 py-8">
        @yield('content')
    </main>

</body>
</html>