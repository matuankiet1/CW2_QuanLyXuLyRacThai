<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hệ thống quản lý xử lý rác thải')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        /* Sử dụng giống layout user bình thường */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }

        .navbar {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .nav-link-item {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link-item:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar fixed top-0 left-0 right-0 z-50">
        <div class="w-full mx-auto px-3">
            <div class="flex items-center justify-between h-16 gap-2">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('staff.home.index') }}"
                        class="flex items-center text-white font-bold text-base hover:text-white/90">
                        <i class="fas fa-recycle mr-1.5"></i>
                        <span class="hidden sm:inline">EcoWaste</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:flex items-center justify-center flex-1 min-w-0 px-1">
                    <nav class="flex items-center gap-0.5 justify-center w-full">
                        <a href="{{ route('staff.home.index') }}" class="nav-link-item"><i class="fas fa-home mr-1"></i>Trang
                            chủ</a>
                        <a href="{{ route('user.posts.home') }}" class="nav-link-item"><i
                                class="fas fa-newspaper mr-1"></i>Bài viết</a>
                        <a href="{{ route('user.events.index') }}" class="nav-link-item"><i
                                class="fas fa-calendar-alt mr-1"></i>Sự kiện</a>
                        <a href="{{ route('user.waste-logs.index') }}" class="nav-link-item"><i
                                class="fas fa-trash-alt mr-1"></i>Thu gom rác</a>
                        <a href="{{ route('home.about') }}" class="nav-link-item"><i
                                class="fas fa-info-circle mr-1"></i>Giới thiệu</a>
                        <a href="{{ route('home.contact') }}" class="nav-link-item"><i
                                class="fas fa-envelope mr-1"></i>Liên hệ</a>

                        <!-- Cá Nhân Dropdown -->
                        <div class="relative">
                            <button id="personalMenuToggle" class="nav-link-item flex items-center">
                                <i class="fas fa-user mr-1"></i>Cá nhân <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div id="personalMenuDropdown"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <a href="{{ route('user.reports.create') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg mx-1">
                                        <i class="fas fa-flag mr-2"></i>Báo cáo
                                    </a>
                                    <a href="{{ route('user.waste-logs.index') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg mx-1">
                                        <i class="fas fa-file-alt mr-2"></i>Báo cáo thu gom
                                    </a>
                                    <a href="{{ route('user.statistics.index') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg mx-1">
                                        <i class="fas fa-chart-line mr-2"></i>Thống kê
                                    </a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- User Profile & Mobile Menu -->
                <div class="flex items-center gap-2 shrink-0">
                    @auth
                        <div class="relative">
                            <button id="userMenuToggle"
                                class="flex items-center text-white hover:bg-white/10 rounded-lg px-2 py-1.5">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-2">
                                    <span
                                        class="text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                </div>
                                <span class="font-medium hidden xl:block text-sm">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1.5 text-xs hidden xl:block"></i>
                            </button>
                            <div id="userMenuDropdown"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl opacity-0 invisible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg mx-1">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <!-- Mobile Menu Toggle -->
                    <button id="mobileMenuToggle"
                        class="lg:hidden text-white p-2 hover:bg-white/10 rounded-lg transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="lg:hidden hidden border-t border-white/20 pb-4">
                <div class="flex flex-col gap-2 mt-4">
                    <a href="{{ route('staff.home.index') }}" class="nav-link-item"><i class="fas fa-home mr-2"></i>Trang
                        chủ</a>
                    <a href="{{ route('user.posts.home') }}" class="nav-link-item"><i
                            class="fas fa-newspaper mr-2"></i>Bài viết</a>
                    <a href="{{ route('user.events.index') }}" class="nav-link-item"><i
                            class="fas fa-calendar-alt mr-2"></i>Sự kiện</a>
                    <a href="{{ route('user.waste-logs.index') }}" class="nav-link-item"><i
                            class="fas fa-trash-alt mr-2"></i>Thu gom rác</a>
                    <a href="{{ route('home.about') }}" class="nav-link-item"><i
                            class="fas fa-info-circle mr-2"></i>Giới thiệu</a>
                    <a href="{{ route('home.contact') }}" class="nav-link-item"><i class="fas fa-envelope mr-2"></i>Liên
                        hệ</a>
                    <div class="border-t border-white/20 pt-2 mt-2">
                        <p class="text-white/70 text-xs font-semibold mb-2 px-2">CÁ NHÂN</p>
                        <a href="{{ route('user.reports.create') }}" class="nav-link-item"><i
                                class="fas fa-flag mr-2"></i>Báo cáo</a>
                        <a href="{{ route('user.waste-logs.index') }}" class="nav-link-item"><i
                                class="fas fa-file-alt mr-2"></i>Báo cáo thu gom</a>
                        <a href="{{ route('user.statistics.index') }}" class="nav-link-item"><i
                                class="fas fa-chart-line mr-2"></i>Thống kê</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main style="margin-top: 80px;">
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        // Dropdown toggle script giống layout user
        document.addEventListener('DOMContentLoaded', function () {
            const personalMenuToggle = document.getElementById('personalMenuToggle');
            const personalMenuDropdown = document.getElementById('personalMenuDropdown');
            personalMenuToggle?.addEventListener('click', function (e) {
                e.stopPropagation();
                const isVisible = !personalMenuDropdown.classList.contains('invisible');
                personalMenuDropdown.classList.toggle('invisible', isVisible);
                personalMenuDropdown.classList.toggle('opacity-0', isVisible);
            });
            document.addEventListener('click', function (e) {
                if (!personalMenuToggle.contains(e.target) && !personalMenuDropdown.contains(e.target)) {
                    personalMenuDropdown.classList.add('invisible', 'opacity-0');
                }
            });
        });
    </script>
</body>

</html>