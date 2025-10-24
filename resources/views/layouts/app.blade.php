

{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Bảo mật form --}}

    <title>@yield('title', 'EcoSchool')</title>

    {{-- Font hoặc favicon (nếu có) --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

    {{-- CSS của ứng dụng (dùng Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    @yield('content') {{-- Nội dung của các trang con sẽ được chèn vào đây --}}

    @vite('resources/js/checkbox.js')
    @vite('resources/js/loader.js')
    @vite('resources/js/toast.js')
    {{-- Hiển thị toast (nếu có) --}}
    @if (session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast(
                "{{ session('status')['type'] }}",
                "{{ session('status')['message'] }}"
            );
        });
    </script>
    @endif

</body>

</html>
