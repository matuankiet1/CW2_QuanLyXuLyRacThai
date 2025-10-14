{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EcoSchool</title>

    {{-- Tích hợp Vite để nạp CSS và JS --}}
    @vite('resources/css/app.css')
</head>
<body class="antialiased">
    @yield('content') {{-- Nội dung của các trang con sẽ được chèn vào đây --}}
</body>
</html>