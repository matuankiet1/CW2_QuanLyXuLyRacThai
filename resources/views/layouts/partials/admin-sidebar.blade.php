@php
    // Default menu items - can be overridden by views
    $menuItems = $menuItems ?? [
        [
            'section' => 'Tổng quan',
            'items' => [
                [
                    'label' => 'Trang chủ',
                    'icon' => 'fa-home',
                    'route' => 'admin.home',
                    'active' => request()->routeIs('admin.home'),
                ],
                [
                    'label' => 'Dashboard',
                    'icon' => 'fa-chart-line',
                    'route' => 'dashboard.admin',
                    'active' => request()->routeIs('dashboard.admin'),
                ],
                [
                    'label' => 'Thông báo',
                    'icon' => 'fa-bell',
                    'route' => 'admin.notifications.index',
                    'active' => request()->routeIs('admin.notifications.*'),
                ],
            ],
        ],
        [
            'section' => 'Quản lý',
            'items' => [
                [
                    'label' => 'Người dùng',
                    'icon' => 'fa-users',
                    'route' => 'admin.users.index',
                    'active' => request()->routeIs('admin.users.*'),
                ],
                [
                    'label' => 'Bài viết',
                    'icon' => 'fa-newspaper',
                    'route' => 'admin.posts.index',
                    'active' => request()->routeIs('admin.posts.*'),
                ],
                [
                    'label' => 'Sự kiện',
                    'icon' => 'fa-calendar-check',
                    'route' => 'admin.events.index',
                    'active' => request()->routeIs('admin.events.*'),
                ],
                [
                    'label' => 'Lịch thu gom rác',
                    'icon' => 'fa-calendar-alt',
                    'route' => 'admin.collection-schedules.index',
                    'active' => request()->routeIs('admin.collection-schedules.*'),
                ],
                [
                    'label' => 'Banner',
                    'icon' => 'fa-image',
                    'route' => 'admin.banners.index',
                    'active' => request()->routeIs('admin.banners.*'),
                ],
                [
                    'label' => 'Phản hồi',
                    'icon' => 'fa-comment-dots',
                    'route' => 'admin.feedback.index',
                    'active' => request()->routeIs('admin.feedback.*'),
                ],
            ],
        ],
        [
            'section' => 'Báo cáo & Phân quyền',
            'items' => [
                [
                    'label' => 'Báo cáo người dùng',
                    'icon' => 'fa-flag',
                    'route' => 'admin.reports.user-reports',
                    'active' => request()->routeIs('admin.reports.*'),
                ],
                [
                    'label' => 'Báo cáo thu gom rác',
                    'icon' => 'fa-user-shield',
                    'route' => 'admin.collection_reports.index',
                    'active' => request()->routeIs('admin.roles.*'),
                ],
                [
                    'label' => 'Phân quyền',
                    'icon' => 'fa-user-shield',
                    'route' => 'admin.roles.index',
                    'active' => request()->routeIs('admin.roles.*'),
                ],
            ],
        ],
        [
            'section' => 'Hỗ trợ',
            'items' => [
                [
                    'label' => 'Giới thiệu',
                    'icon' => 'fa-info-circle',
                    'route' => 'admin.home.about',
                    'active' => request()->routeIs('admin.home.about'),
                ],
                [
                    'label' => 'Liên hệ',
                    'icon' => 'fa-envelope',
                    'route' => 'admin.home.contact',
                    'active' => request()->routeIs('admin.home.contact'),
                ],
            ],
        ],
    ];
@endphp

<aside id="sidebar"
    class="sidebar fixed top-16 left-0 h-[calc(100vh-4rem)] bg-white border-r border-gray-200 shadow-lg z-40 overflow-y-auto">
    <!-- Sidebar Header -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4">
        <h5 class="font-semibold text-lg mb-1">
            <i class="fas fa-tachometer-alt mr-2"></i>Admin Panel
        </h5>
        <p class="text-sm text-white/90">Quản lý hệ thống</p>
    </div>

    <!-- Sidebar Menu -->
    <nav class="p-2">
        @foreach ($menuItems as $section)
            @if (isset($section['section']))
                <div class="px-4 py-2 mt-4 mb-2">
                    <h6 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        {{ $section['section'] }}
                    </h6>
                </div>
            @endif

            @if (isset($section['items']) && is_array($section['items']))
                @foreach ($section['items'] as $item)
                    @php
                        $isActive = $item['active'] ?? request()->routeIs($item['route'] ?? '');
                        $route = $item['route'] ?? '#';
                    @endphp
                    <a href="{{ route($route) }}"
                        class="flex items-center px-4 py-2.5 mb-1 rounded-lg transition-all duration-200 {{ $isActive ? 'bg-green-100 text-green-700 font-semibold border-l-4 border-green-500' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas {{ $item['icon'] ?? 'fa-circle' }} w-5 text-center mr-3"></i>
                        <span>{{ $item['label'] ?? 'Menu Item' }}</span>
                        @if (isset($item['badge']))
                            <span
                                class="ml-auto px-2 py-1 text-xs font-semibold rounded {{ $item['badge']['class'] ?? 'bg-green-500 text-white' }}">
                                {{ $item['badge']['text'] ?? '' }}
                            </span>
                        @endif
                    </a>
                @endforeach
            @endif
        @endforeach
    </nav>
</aside>
