    @extends('layouts.app')

    @section('content')
        <div class="flex h-screen bg-gray-100">

            {{-- Sidebar --}}
            <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-200 shadow-lg z-2">
                {{-- Logo --}}
                <div class="flex items-center justify-between px-6 py-3 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-gray-900 font-bold">EcoSchool</h2>
                            <p class="text-xs text-gray-500">Quản lý rác thải</p>
                        </div>
                    </div>
                </div>

                {{-- Menu --}}
                @php
                    $menuItems = [
                        ['id' => 'home', 'label' => 'Trang chủ'],
                        ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard.admin'],
                        ['id' => 'users', 'label' => 'Quản lý người dùng', 'route' => 'admin.users.index'],
                        [
                            'id' => 'schedules',
                            'label' => 'Quản lý lịch thu gom',
                            'route' => 'admin.collection-schedules.index',
                        ],
                        ['id' => 'posts', 'label' => 'Quản lý bài viết', 'route' => 'admin.posts.index'],
                        ['id' => 'permissions', 'label' => 'Phân quyền'],
                        ['id' => 'events', 'label' => 'Quản lý sự kiện'],
                        ['id' => 'participants', 'label' => 'Quản lý sinh viên tham gia'],
                        ['id' => 'reports', 'label' => 'Báo cáo người dùng'],
                        ['id' => 'notifications', 'label' => 'Gửi thông báo'],
                        ['id' => 'personal-stats', 'label' => 'Thống kê cá nhân'],
                        ['id' => 'finance', 'label' => 'Quản lý tài chính'],
                        ['id' => 'rewards', 'label' => 'Danh sách điểm thưởng'],
                        ['id' => 'banners', 'label' => 'Quản lý banner', 'route' => 'banners.index'],
                    ];

                    $currentPage = request()->route() ? request()->route()->getName() : '';

                    // 1) Thử khớp chính xác tên route
                    $currentItem = collect($menuItems)->firstWhere('route', $currentPage);

                    // 2) Nếu không khớp (ví dụ đang ở edit/show/create), khớp theo "prefix nhóm"
                    if (!$currentItem && $currentPage) {
                        $currentItem = collect($menuItems)->first(function ($item) use ($currentPage) {
                            return isset($item['route']) &&
                                Str::startsWith($currentPage, Str::of($item['route'])->beforeLast('.')->value() . '.');
                        });
                    }

                    $pageTitle = $currentItem['label'] ?? 'Dashboard';
                @endphp

                <nav class="flex-1 p-4 overflow-y-auto">
                    <ul class="space-y-1">
                        @foreach ($menuItems as $item)
                            @if (isset($item['route']))
                                <a href="{{ route($item['route']) }}"
                                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                                {{ $currentPage == $item['route'] ? 'text-white bg-green-600 hover:bg-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                    <span class="text-sm">{{ $item['label'] }}</span>
                                </a>
                            @else
                                <span class="flex items-center gap-3 px-4 py-2.5 text-gray-400 cursor-not-allowed">
                                    {{ $item['label'] }}
                                </span>
                            @endif
                        @endforeach

                    </ul>
                </nav>

                {{-- User profile --}}
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                            A
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">Admin</p>
                            <p class="text-xs text-gray-500">admin@school.edu</p>
                        </div>
                        <button
                            class="h-8 w-8 flex items-center justify-center rounded-xl cursor-pointer hover:text-red-600 hover:bg-red-200">
                            ⏻
                        </button>
                    </div>
                </div>
            </aside>

            <div class="flex-1 flex flex-col overflow-hidden">
                {{-- Header --}}
                <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
                    <h1 class="text-lg font-bold text-gray-900">{{ $pageTitle }}</h1>
                    <button class="p-2 rounded-xl border-[1.5px] border-green-100 bg-[#f8fdf9] hover:bg-green-100 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
                            color="#000000" fill="none">
                            <path d="M15.5 18C15.5 19.933 13.933 21.5 12 21.5C10.067 21.5 8.5 19.933 8.5 18" stroke="#141B34"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M19.2311 18H4.76887C3.79195 18 3 17.208 3 16.2311C3 15.762 3.18636 15.3121 3.51809 14.9803L4.12132 14.3771C4.68393 13.8145 5 13.0514 5 12.2558V9.5C5 5.63401 8.13401 2.5 12 2.5C15.866 2.5 19 5.634 19 9.5V12.2558C19 13.0514 19.3161 13.8145 19.8787 14.3771L20.4819 14.9803C20.8136 15.3121 21 15.762 21 16.2311C21 17.208 20.208 18 19.2311 18Z"
                                stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </header>
                {{-- Content --}}
                <main class="flex-1 overflow-y-auto p-4 space-y-6 bg-[#f8fdf9]">
                    {{-- Nội dung chính --}}
                    @yield('main-content')
                </main>
            </div>
        </div>

        {{-- Include Chart.js --}}
        {{-- @vite(['resources/js/dashboard.js']) --}}
    @endsection
