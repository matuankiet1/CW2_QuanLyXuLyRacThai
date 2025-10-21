<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bảng điều khiển')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex h-screen bg-gray-100">

    {{-- Sidebar --}}
    <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-200">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center text-white font-bold">
                    E
                </div>
                <div>
                    <h2 class="text-gray-800 font-semibold">EcoSchool</h2>
                    <p class="text-xs text-gray-500">Quản lý rác thải</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto p-4">
            <ul class="space-y-1">
                @php
                    $menuItems = [
                        ['label' => 'Trang chủ'],
                        ['label' => 'Dashboard'],
                        ['label' => 'Người dùng'],
                        ['label' => 'Lịch thu gom'],
                        ['label' => 'Bài viết', 'route' => 'admin.posts.index'],
                        ['label' => 'Phân quyền'],
                        ['label' => 'Sự kiện'],
                        ['label' => 'Sinh viên tham gia'],
                        ['label' => 'Báo cáo'],
                        ['label' => 'Thông báo'],
                        ['label' => 'Thống kê cá nhân'],
                        ['label' => 'Tài chính'],
                        ['label' => 'Điểm thưởng'],
                    ];
                @endphp

                @foreach($menuItems as $item)
                            @php
                                $routeName = $item['route'] ?? '#';
                            @endphp
                            <li>
                                <a href="{{ $routeName !== '#' ? route($routeName) : '#' }}" class="block px-4 py-2.5 rounded-lg text-sm transition
                       {{ request()->routeIs($routeName) ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                    {{ $item['label'] }}
                                </a>
                            </li>
                @endforeach
            </ul>
        </nav>

        {{-- User profile --}}
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-100">
                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">A
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">Admin</p>
                    <p class="text-xs text-gray-500">admin@school.edu</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title')</h1>
                <p class="text-sm text-gray-500">Chào mừng đến với hệ thống quản lý rác thải</p>
            </div>
            <button class="p-2 rounded-lg border hover:bg-gray-100 text-xl">🔔</button>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

</body>

</html>