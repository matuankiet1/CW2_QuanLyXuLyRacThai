@extends('layouts.admin-with-sidebar')

@section('title', 'Giới thiệu - EcoWaste Admin')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4">
                        Về hệ thống EcoWaste
                    </h1>
                    <p class="lead mb-4">
                        Hệ thống quản lý xử lý rác thải thông minh, được thiết kế để giúp quản lý viên dễ dàng quản lý và theo dõi hoạt động của hệ thống.
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-recycle" style="font-size: 8rem; color: rgba(255,255,255,0.3);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body p-5">
                        <h2 class="section-title text-center mb-5">Giới thiệu về EcoWaste Admin</h2>
                        
                        <div class="row mb-5">
                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <h4>Bảo mật cao</h4>
                                    <p class="text-muted">Hệ thống được bảo vệ với các lớp bảo mật tiên tiến, đảm bảo an toàn cho dữ liệu và thông tin người dùng.</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <h4>Quản lý toàn diện</h4>
                                    <p class="text-muted">Cung cấp đầy đủ các công cụ quản lý người dùng, phân quyền, nội dung và báo cáo chi tiết.</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="mb-4">Tính năng chính</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Quản lý người dùng:</strong> Tạo, chỉnh sửa và quản lý tài khoản
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Phân quyền:</strong> Cấp quyền admin và quản lý vai trò
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Quản lý nội dung:</strong> Tạo và quản lý bài viết, banner
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Lịch thu gom:</strong> Quản lý lịch trình thu gom rác thải
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Báo cáo chi tiết:</strong> Thống kê và báo cáo hoạt động
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Dashboard:</strong> Tổng quan hệ thống trực quan
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Thông báo:</strong> Gửi thông báo đến người dùng
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>Bảo mật:</strong> Hệ thống bảo mật đa lớp
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-5 p-4 bg-light rounded">
                            <h4 class="mb-3">Mục tiêu</h4>
                            <p class="mb-0">
                                EcoWaste Admin được phát triển với mục tiêu tạo ra một hệ thống quản lý xử lý rác thải hiệu quả, 
                                giúp các quản trị viên dễ dàng quản lý và theo dõi hoạt động của hệ thống, góp phần bảo vệ môi trường 
                                và xây dựng một tương lai bền vững.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Bắt đầu quản lý hệ thống</h3>
                <p class="mb-0">Truy cập dashboard để khám phá các tính năng quản lý mạnh mẽ của EcoWaste Admin.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('dashboard.admin') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-tachometer-alt me-2"></i>Truy cập Dashboard
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
