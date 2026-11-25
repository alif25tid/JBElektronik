<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Dashboard Admin - Jualin PCR')</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
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
</html>