@extends('layouts.app') {{-- Hoặc bỏ dòng này nếu bạn không có layout chính --}}

@section('content')
    <div class="flex h-screen bg-gray-100">

        {{-- Sidebar --}}
        <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-200">
            {{-- Logo --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
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
                    ['id' => 'home', 'label' => 'Trang chủ', 'route' => 'posts.home'],
                    ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard.admin'],
                    ['id' => 'users', 'label' => 'Quản lý người dùng', 'route' => 'admin.users.index'],
                    ['id' => 'schedules', 'label' => 'Quản lý lịch thu gom', 'route' => 'collection-schedule.index'],
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
            @endphp

            <nav class="flex-1 p-4 overflow-y-auto">
                <ul class="space-y-1">
                    @foreach ($menuItems as $item)
                        @if (isset($item['route']))
                            <a href="{{ route($item['route']) }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                              {{ $currentPage == $item['route'] ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
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
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="h-8 w-8 flex items-center justify-center rounded hover:bg-gray-200" title="Đăng xuất">
                            ⏻
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Header --}}
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h1 class="text-lg font-bold text-gray-900">Dashboard</h1>
                <button class="px-3 py-1 border rounded hover:bg-gray-100">Thông báo</button>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto bg-gray-100">
                @yield('dashboard-content')
            </main>
        </div>

    </div>

@endsection