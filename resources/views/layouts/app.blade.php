{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Bảo mật form --}}

    <title>@yield('title', 'EcoSchool')</title>

    {{-- Font hoặc favicon (nếu có) --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- CSS của ứng dụng (dùng Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900">

    {{-- Header chung --}}
    <header class="p-4 bg-green-600 text-white shadow">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">
                <a href="{{ url('/') }}">EcoSchool</a>
            </h1>
            <nav>
                <a href="{{ url('/') }}" class="px-3 hover:underline">Trang chủ</a>
                <a href="{{ url('/about') }}" class="px-3 hover:underline">Giới thiệu</a>
                <a href="{{ url('/contact') }}" class="px-3 hover:underline">Liên hệ</a>
            </nav>
        </div>
    </header>

    {{-- Nội dung chính --}}
    <main class="container mx-auto py-6 px-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-200 text-center py-4">
        <p>&copy; {{ date('Y') }} EcoSchool. All rights reserved.</p>
    </footer>

</body>
</html>
