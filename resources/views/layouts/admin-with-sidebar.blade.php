<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hệ thống quản lý xử lý rác thải - Admin')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #10b981;
            --secondary-color: #059669;
            --accent-color: #34d399;
            --admin-color: #dc2626;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
            --sidebar-width: 280px;
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
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
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

        .admin-badge {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            margin-left: 0.5rem;
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

        .btn-admin {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
            color: white;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - 80px);
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-right: 1px solid var(--border-color);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            z-index: 1020;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .sidebar-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .sidebar-header p {
            margin: 0.25rem 0 0 0;
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-item {
            margin: 0.25rem 0;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-link:hover {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            text-decoration: none;
        }

        .menu-link.active {
            background-color: rgba(16, 185, 129, 0.15);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }

        .menu-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .menu-section {
            margin: 1.5rem 0 0.5rem 0;
            padding: 0 1.5rem;
        }

        .menu-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            transition: all 0.3s ease;
        }

        .content-wrapper {
            padding: 2rem;
        }

        /* Responsive */
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

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0;
            margin-bottom: 2rem;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .section-title {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.home') }}">
                <i class="fas fa-recycle me-2"></i>
                EcoWaste Admin
            </a>
            
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                                <span class="admin-badge">ADMIN</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard.admin') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users me-2"></i>Quản lý người dùng
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.roles.index') }}">
                                    <i class="fas fa-user-shield me-2"></i>Phân quyền
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.reports.user-reports') }}">
                                    <i class="fas fa-flag me-2"></i>Báo cáo
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h5><i class="fas fa-tachometer-alt me-2"></i>Admin Panel</h5>
            <p>Quản lý hệ thống</p>
        </div>
        
        <nav class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Tổng quan</div>
                <div class="menu-item">
                    <a href="{{ route('admin.home') }}" class="menu-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Trang chủ</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('dashboard.admin') }}" class="menu-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Quản lý</div>
                <div class="menu-item">
                    <a href="{{ route('admin.users.index') }}" class="menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Người dùng</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.posts.index') }}" class="menu-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i>
                        <span>Bài viết</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.collection-schedules.index') }}" class="menu-link {{ request()->routeIs('admin.collection-schedules.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Lịch thu gom</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.events.index') }}" class="menu-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Sự kiện</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('banners.index') }}" class="menu-link {{ request()->routeIs('banners.*') ? 'active' : '' }}">
                        <i class="fas fa-image"></i>
                        <span>Banner</span>
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Báo cáo & Phân quyền</div>
                <div class="menu-item">
                    <a href="{{ route('admin.reports.user-reports') }}" class="menu-link {{ request()->routeIs('admin.reports.user-reports*') ? 'active' : '' }}">
                        <i class="fas fa-flag"></i>
                        <span>Báo cáo người dùng</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.roles.index') }}" class="menu-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Phân quyền</span>
                    </a>
                </div>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Hỗ trợ</div>
                <div class="menu-item">
                    <a href="{{ route('admin.home.about') }}" class="menu-link {{ request()->routeIs('admin.home.about') ? 'active' : '' }}">
                        <i class="fas fa-info-circle"></i>
                        <span>Giới thiệu</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.home.contact') }}" class="menu-link {{ request()->routeIs('admin.home.contact') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i>
                        <span>Liên hệ</span>
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
