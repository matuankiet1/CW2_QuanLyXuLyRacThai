<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hệ thống quản lý xử lý rác thải - Admin')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#10b981',
                        secondary: '#059669',
                        accent: '#34d399',
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary-color: #10b981;
            --secondary-color: #059669;
            --accent-color: #34d399;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            width: var(--sidebar-width);
            transition: transform 0.3s ease;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Button styles */
        .btn-admin {
            @apply px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg;
        }

        .btn-primary {
            @apply px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200;
        }

        .btn-secondary {
            @apply px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-all duration-200;
        }

        .btn-danger {
            @apply px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-all duration-200;
        }

        /* Card styles */
        .card {
            @apply bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden;
        }

        .card-header {
            @apply px-6 py-4 border-b border-gray-200 bg-gray-50;
        }

        .card-body {
            @apply px-6 py-4;
        }

        /* Table styles */
        .table {
            @apply w-full border-collapse;
        }

        .table thead {
            @apply bg-gray-50;
        }

        .table th {
            @apply px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200;
        }

        .table td {
            @apply px-4 py-3 text-sm text-gray-900 border-b border-gray-200;
        }

        .table tbody tr:hover {
            @apply bg-gray-50;
        }

        /* Form styles */
        .form-control {
            @apply w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500;
        }

        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }

        /* Alert styles */
        .alert {
            @apply px-4 py-3 rounded-lg mb-4 border;
        }

        .alert-success {
            @apply bg-green-100 border-green-400 text-green-700;
        }

        .alert-danger {
            @apply bg-red-100 border-red-400 text-red-700;
        }

        .alert-warning {
            @apply bg-yellow-100 border-yellow-400 text-yellow-700;
        }

        .alert-info {
            @apply bg-blue-100 border-blue-400 text-blue-700;
        }

        /* Badge styles */
        .badge {
            @apply px-2 py-1 text-xs font-semibold rounded;
        }

        .badge-success {
            @apply bg-green-500 text-white;
        }

        .badge-danger {
            @apply bg-red-500 text-white;
        }

        .badge-warning {
            @apply bg-yellow-500 text-white;
        }

        .badge-info {
            @apply bg-blue-500 text-white;
        }

        .badge-primary {
            @apply bg-green-500 text-white;
        }

        /* Breadcrumb styles */
        .breadcrumb {
            @apply flex items-center space-x-2 text-sm text-gray-600;
        }

        .breadcrumb-item {
            @apply flex items-center;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: ">";
            @apply mx-2 text-gray-400;
        }

        .breadcrumb-item.active {
            @apply text-gray-900 font-semibold;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <button id="sidebarToggle"
                        class="lg:hidden text-white p-2 mr-2 hover:bg-white/10 rounded-lg transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <a href="{{ route('admin.home') }}"
                        class="flex items-center text-white font-bold text-xl hover:text-white/90 transition">
                        <i class="fas fa-recycle mr-2"></i>
                        <span>EcoWaste Admin</span>
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative group">
                            <button class="flex items-center text-white hover:bg-white/10 rounded-lg px-3 py-2 transition">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-2">
                                    @if (auth()->user()->avatar)
                                        <img class="w-8 h-8 object-cover rounded-full"
                                            src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                                    @else
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    @endif
                                </div>
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <span class="ml-2 px-2 py-1 bg-green-400 rounded text-xs font-semibold">ADMIN</span>
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <a href="{{ route('dashboard.admin') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-users mr-2"></i>Quản lý người dùng
                                    </a>
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-user-shield mr-2"></i>Phân quyền
                                    </a>
                                    <a href="{{ route('admin.reports.user-reports') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fas fa-flag mr-2"></i>Báo cáo
                                    </a>
                                    <a href="{{ route('profile.show') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                        <i class="fa-solid fa-address-card mr-2"></i>Hồ sơ
                                    </a>
                                    <hr class="my-2 border-gray-200">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50 transition">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-white hover:bg-white/10 rounded-lg px-4 py-2 transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-16">
        <!-- Sidebar -->
        @include('layouts.partials.admin-sidebar')

        <!-- Main Content -->
        <main class="main-content flex-1 min-h-screen">
            <!-- Page Header -->
            @hasSection('page-header')
                <div class="bg-white border-b border-gray-200 px-6 py-4">
                    @yield('page-header')
                </div>
            @endif

            <!-- Breadcrumb -->
            @hasSection('breadcrumb')
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-2">
                    <nav class="breadcrumb">
                        @yield('breadcrumb')
                    </nav>
                </div>
            @endif

            <!-- Content Wrapper -->
            <div class="p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success relative" role="alert">
                        <span>{{ session('success') }}</span>
                        <button type="button" class="absolute top-0 right-0 px-4 py-3"
                            onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger relative" role="alert">
                        <span>{{ session('error') }}</span>
                        <button type="button" class="absolute top-0 right-0 px-4 py-3"
                            onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning relative" role="alert">
                        <span>{{ session('warning') }}</span>
                        <button type="button" class="absolute top-0 right-0 px-4 py-3"
                            onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info relative" role="alert">
                        <span>{{ session('info') }}</span>
                        <button type="button" class="absolute top-0 right-0 px-4 py-3"
                            onclick="this.parentElement.style.display='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    @vite('resources/js/toast.js')
    {{-- Hiển thị toast (nếu có) --}}
    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast(
                    "{{ session('status')['type'] }}",
                    "{{ session('status')['message'] }}"
                );
            });
        </script>
    @endif

    <!-- Scripts -->
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar?.classList.toggle('show');
            overlay?.classList.toggle('hidden');
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar?.classList.remove('show');
            overlay?.classList.add('hidden');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            if (window.innerWidth <= 768 && sidebar && !sidebar.contains(event.target) && !toggle?.contains(event
                    .target)) {
                sidebar.classList.remove('show');
                overlay?.classList.add('hidden');
            }
        });
    </script>

    <script>
        // Xóa #_=_ do Facebook tự động thêm vào URL
        if (window.location.hash === '#_=_') {
            if (window.history && window.history.replaceState) {
                window.history.replaceState('', document.title, window.location.pathname);
            } else {
                // Fallback cho trình duyệt cũ
                window.location.hash = '';
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
