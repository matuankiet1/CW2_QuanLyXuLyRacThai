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
        :root {
            --primary-color: #10b981;
            --secondary-color: #059669;
            --accent-color: #34d399;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--bg-light);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            margin: 0 0.25rem;
        }

        .nav-link:hover {
            color: white !important;
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,100 1000,0"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: var(--text-light);
            margin-bottom: 3rem;
        }

        .feature-icon {
            width: 4rem;
            height: 4rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 1.5rem;
        }

        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .footer {
            background: var(--text-dark);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer h5 {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--accent-color);
        }

        .post-card {
            height: 100%;
            transition: all 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
        }

        .post-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .schedule-card {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-left: 4px solid var(--primary-color);
        }

        .banner-slide {
            height: 300px;
            background-size: cover;
            background-position: center;
            border-radius: 1rem;
            position: relative;
            overflow: hidden;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(0,0,0,0.6), rgba(0,0,0,0.3));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
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
            white-space: nowrap;
        }

        .nav-link-item:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
            
            .hero-section {
                padding: 2rem 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar fixed top-0 left-0 right-0 z-50">
        <div class="w-full mx-auto px-3">
            <div class="flex items-center justify-between h-16 gap-2">
                <!-- Logo - Left Side -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center text-white font-bold text-base hover:text-white/90 transition whitespace-nowrap">
                        <i class="fas fa-recycle mr-1.5"></i>
                        <span class="hidden sm:inline">EcoWaste</span>
                    </a>
                </div>
                
                <!-- Navigation Links - Center -->
                <div class="hidden lg:flex items-center justify-center flex-1 min-w-0 px-1">
                    <nav class="flex items-center gap-0.5 justify-center w-full">
                        <a href="{{ route('home') }}" class="nav-link-item" title="Trang chủ">
                            <i class="fas fa-home mr-1"></i><span>Trang chủ</span>
                        </a>
                        <a href="{{ route('user.posts.home') }}" class="nav-link-item" title="Bài viết">
                            <i class="fas fa-newspaper mr-1"></i><span>Bài viết</span>
                        </a>
                        <a href="{{ route('user.events.index') }}" class="nav-link-item" title="Sự kiện">
                            <i class="fas fa-calendar-alt me-1"></i>Sự kiện
                        </a>
                        <a href="{{ route('home.about') }}" class="nav-link-item" title="Giới thiệu">
                            <i class="fas fa-info-circle mr-1"></i><span>Giới thiệu</span>
                        </a>
                        <a href="{{ route('home.contact') }}" class="nav-link-item" title="Liên hệ">
                            <i class="fas fa-envelope mr-1"></i><span>Liên hệ</span>
                        </a>
                        @auth
                        <a href="{{ route('user.reports.create') }}" class="nav-link-item" title="Báo cáo">
                            <i class="fas fa-flag mr-1"></i><span>Báo cáo</span>
                        </a>
                        <a href="{{ route('user.notifications.index') }}" class="nav-link-item relative" title="Thông báo">
                            <i class="fas fa-bell mr-1"></i><span>Thông báo</span>
                            @php
                                $unreadCount = App\Models\NotificationUser::where('user_id', auth()->user()->user_id)
                                    ->whereNull('read_at')
                                    ->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('user.simple-notifications.index') }}" class="nav-link-item relative" title="Thông báo mới">
                            <i class="fas fa-envelope mr-1"></i><span>Thông báo mới</span>
                            @php
                                $simpleUnreadCount = App\Models\SimpleNotification::where('user_id', auth()->user()->user_id)
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            @if($simpleUnreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $simpleUnreadCount > 9 ? '9+' : $simpleUnreadCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('user.notification-preferences.index') }}" class="nav-link-item" title="Cài đặt thông báo">
                            <i class="fas fa-cog mr-1"></i><span>Cài đặt</span>
                        </a>
                        <a href="{{ route('user.statistics.index') }}" class="nav-link-item" title="Thống kê cá nhân">
                            <i class="fas fa-chart-line mr-1"></i><span>Thống kê</span>
                        </a>
                        @endauth
                    </nav>
                </div>
                
                <!-- User Profile - Right Side -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    @auth
                        <div class="relative">
                            <button id="userMenuToggle" class="flex items-center text-white hover:bg-white/10 rounded-lg px-2 py-1.5 transition whitespace-nowrap">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                                    <span class="text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                </div>
                                <span class="font-medium hidden xl:block text-sm">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1.5 text-xs hidden xl:block"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="userMenuDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl opacity-0 invisible transition-all duration-200 z-50">
                                <div class="py-2">
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('dashboard.admin') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition rounded-lg mx-1">
                                            <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                                        </a>
                                        <hr class="my-1 border-gray-200">
                                    @endif
                                    <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50 transition rounded-lg mx-1">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link-item">
                            <i class="fas fa-sign-in-alt mr-1"></i>Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="nav-link-item bg-white/20 hover:bg-white/30">
                            <i class="fas fa-user-plus mr-1"></i>Đăng ký
                        </a>
                    @endauth
                    
                    <!-- Mobile Menu Toggle -->
                    <button id="mobileMenuToggle" class="lg:hidden text-white p-2 hover:bg-white/10 rounded-lg transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="lg:hidden hidden border-t border-white/20 pb-4">
                <div class="flex flex-col gap-2 mt-4">
                    <a href="{{ route('home') }}" class="nav-link-item">
                        <i class="fas fa-home mr-2"></i>Trang chủ
                    </a>
                    <a href="{{ route('user.posts.home') }}" class="nav-link-item">
                        <i class="fas fa-newspaper mr-2"></i>Bài viết
                    </a>
                    <a href="{{ route('user.events.index') }}" class="nav-link-item">
                        <i class="fas fa-calendar-alt mr-2"></i>Sự kiện
                    </a>
                    <a href="{{ route('home.about') }}" class="nav-link-item">
                        <i class="fas fa-info-circle mr-2"></i>Giới thiệu
                    </a>
                    <a href="{{ route('home.contact') }}" class="nav-link-item">
                        <i class="fas fa-envelope mr-2"></i>Liên hệ
                    </a>
                    @auth
                    <a href="{{ route('user.reports.create') }}" class="nav-link-item">
                        <i class="fas fa-flag mr-2"></i>Báo cáo
                    </a>
                    <a href="{{ route('user.notifications.index') }}" class="nav-link-item relative">
                        <i class="fas fa-bell mr-2"></i>Thông báo
                        @php
                            $unreadCount = App\Models\NotificationUser::where('user_id', auth()->user()->user_id)
                                ->whereNull('read_at')
                                ->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('user.simple-notifications.index') }}" class="nav-link-item relative">
                        <i class="fas fa-envelope mr-2"></i>Thông báo mới
                        @php
                            $simpleUnreadCount = App\Models\SimpleNotification::where('user_id', auth()->user()->user_id)
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if($simpleUnreadCount > 0)
                            <span class="ml-2 bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $simpleUnreadCount > 9 ? '9+' : $simpleUnreadCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('user.notification-preferences.index') }}" class="nav-link-item">
                        <i class="fas fa-cog mr-2"></i>Cài đặt thông báo
                    </a>
                    <a href="{{ route('user.statistics.index') }}" class="nav-link-item">
                        <i class="fas fa-chart-line mr-2"></i>Thống kê cá nhân
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 80px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-recycle me-2"></i>EcoWaste</h5>
                    <p class="mb-3">Hệ thống quản lý xử lý rác thải thông minh, góp phần bảo vệ môi trường và xây dựng tương lai bền vững.</p>
                    <div class="d-flex gap-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Liên kết</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="mb-2"><a href="{{ route('user.posts.home') }}">Bài viết</a></li>
                        <li class="mb-2"><a href="{{ route('home.about') }}">Giới thiệu</a></li>
                        <li class="mb-2"><a href="{{ route('home.contact') }}">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Dịch vụ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Thu gom rác thải</a></li>
                        <li class="mb-2"><a href="#">Tái chế</a></li>
                        <li class="mb-2"><a href="#">Xử lý rác thải</a></li>
                        <li class="mb-2"><a href="#">Tư vấn môi trường</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5>Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>123 Đường ABC, Quận XYZ, TP.HCM</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i>+84 123 456 789</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i>info@ecowaste.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 EcoWaste. Tất cả quyền được bảo lưu.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="me-3">Chính sách bảo mật</a>
                    <a href="#">Điều khoản sử dụng</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // User menu dropdown toggle
            const userMenuToggle = document.getElementById('userMenuToggle');
            const userMenuDropdown = document.getElementById('userMenuDropdown');
            
            if (userMenuToggle && userMenuDropdown) {
                userMenuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = !userMenuDropdown.classList.contains('invisible');
                    
                    if (isVisible) {
                        userMenuDropdown.classList.add('opacity-0', 'invisible');
                    } else {
                        userMenuDropdown.classList.remove('opacity-0', 'invisible');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenuToggle.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                        userMenuDropdown.classList.add('opacity-0', 'invisible');
                    }
                });
            }

            // Logout confirmation
            const logoutForm = document.getElementById('logoutForm');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    if (!confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
