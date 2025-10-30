@extends('layouts.admin')

@section('title', 'Trang chủ Admin - Hệ thống quản lý xử lý rác thải')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4">
                        Chào mừng Admin đến với EcoWaste
                    </h1>
                    <p class="lead mb-4">
                        Quản lý hệ thống xử lý rác thải thông minh, góp phần bảo vệ môi trường và xây dựng tương lai bền vững.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('dashboard.admin') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-users me-2"></i>Quản lý người dùng
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-user-shield" style="font-size: 8rem; color: rgba(255,255,255,0.3);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Thống kê hệ thống</h2>
                <p class="section-subtitle">Tổng quan về hoạt động của hệ thống</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['total_users'] }}</div>
                    <h5>Tổng người dùng</h5>
                    <p class="text-muted mb-0">Tất cả tài khoản trong hệ thống</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['admin_users'] }}</div>
                    <h5>Quản trị viên</h5>
                    <p class="text-muted mb-0">Tài khoản có quyền quản lý</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['total_posts'] }}</div>
                    <h5>Bài viết</h5>
                    <p class="text-muted mb-0">Nội dung đã được xuất bản</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['total_schedules'] }}</div>
                    <h5>Lịch thu gom</h5>
                    <p class="text-muted mb-0">Lịch trình đã được lên kế hoạch</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Thao tác nhanh</h2>
                <p class="section-subtitle">Các chức năng quản lý chính</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">Quản lý người dùng</h5>
                        <p class="card-text">Xem, chỉnh sửa và quản lý tài khoản người dùng trong hệ thống.</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Quản lý</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h5 class="card-title">Phân quyền</h5>
                        <p class="card-text">Cấp quyền admin cho người dùng và quản lý vai trò trong hệ thống.</p>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">Phân quyền</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h5 class="card-title">Quản lý bài viết</h5>
                        <p class="card-text">Tạo, chỉnh sửa và quản lý nội dung bài viết trên trang web.</p>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">Quản lý</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5 class="card-title">Báo cáo</h5>
                        <p class="card-text">Xem báo cáo chi tiết về hoạt động của người dùng và hệ thống.</p>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-primary">Xem báo cáo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Posts Section -->
@if($latestPosts->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Bài viết mới nhất</h2>
                <p class="section-subtitle">Những nội dung mới được cập nhật</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($latestPosts as $post)
            <div class="col-lg-4 col-md-6">
                <div class="card post-card">
                    @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top post-image" alt="{{ $post->title }}">
                    @else
                    <div class="card-img-top post-image bg-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($post->title, 50) }}</h5>
                        <p class="card-text text-muted">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $post->publish_date ? \Carbon\Carbon::parse($post->publish_date)->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                            </small>
                            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary">Đọc thêm</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('posts.home') }}" class="btn btn-primary">Xem tất cả bài viết</a>
        </div>
    </div>
</section>
@endif

<!-- Upcoming Schedules Section -->
@if($upcomingSchedules->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Lịch thu gom sắp tới</h2>
                <p class="section-subtitle">Các hoạt động thu gom rác thải được lên kế hoạch</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($upcomingSchedules as $schedule)
            <div class="col-lg-4 col-md-6">
                <div class="card schedule-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-calendar-alt me-2"></i>
                            {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}
                        </h5>
                        <p class="card-text">
                            <strong>Khu vực:</strong> {{ $schedule->area }}<br>
                            <strong>Thời gian:</strong> {{ $schedule->time_slot }}<br>
                            <strong>Loại rác:</strong> {{ $schedule->waste_type }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">{{ $schedule->status }}</span>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($schedule->scheduled_date)->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('admin.collection-schedules.index') }}" class="btn btn-primary">Quản lý lịch thu gom</a>
        </div>
    </div>
</section>
@endif

<!-- Banners Section -->
@if($banners->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Thông báo quan trọng</h2>
                <p class="section-subtitle">Những thông tin cần được chú ý</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($banners as $banner)
            <div class="col-lg-4 col-md-6">
                <div class="banner-slide" style="background-image: url('{{ asset('storage/' . $banner->image) }}')">
                    <div class="banner-overlay">
                        <div>
                            <h4 class="fw-bold">{{ $banner->title }}</h4>
                            <p class="mb-0">{{ Str::limit($banner->description, 100) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call to Action Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Sẵn sàng quản lý hệ thống?</h3>
                <p class="mb-0">Truy cập dashboard để bắt đầu quản lý và theo dõi hoạt động của hệ thống.</p>
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
