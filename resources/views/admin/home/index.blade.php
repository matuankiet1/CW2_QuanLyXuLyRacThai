@extends('layouts.admin-with-sidebar')

@section('title', 'Trang chủ Admin - Hệ thống quản lý xử lý rác thải')

@section('content')
<!-- 1. Hero Section với Banner Top Carousel -->
<section class="hero-section">
    <div class="container">
        <!-- Banner Top Carousel -->
        @if($topBanners->count() > 0)
        <div class="row mb-4"> <!-- Giảm margin-bottom -->
            <div class="col-12">
                <div id="bannerCarousel" class="carousel slide compact-carousel" data-bs-ride="carousel">
                    <!-- Indicators -->
                    @if($topBanners->count() > 1)
                    <div class="carousel-indicators compact-indicators">
                        @foreach($topBanners as $key => $banner)
                        <button type="button" 
                                data-bs-target="#bannerCarousel" 
                                data-bs-slide-to="{{ $key }}" 
                                class="{{ $key === 0 ? 'active' : '' }}"
                                aria-current="{{ $key === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $key + 1 }}"></button>
                        @endforeach
                    </div>
                    @endif

                    <!-- Carousel items -->
                    <div class="carousel-inner compact-carousel-inner">
                        @foreach($topBanners as $key => $banner)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}" data-bs-interval="5000">
                            @if($banner->link)
                                <a href="{{ $banner->link }}" target="_blank" class="d-block">
                                    <img src="{{ asset('storage/' . $banner->image) }}" 
                                         alt="{{ $banner->title }}" 
                                         class="d-block w-100 compact-carousel-image">
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $banner->image) }}" 
                                     alt="{{ $banner->title }}" 
                                     class="d-block w-100 compact-carousel-image">
                            @endif
                            
                            <!-- Caption -->
                            @if($banner->title || $banner->description)
                            <div class="carousel-caption compact-caption d-none d-md-block">
                                @if($banner->title)
                                    <h5 class="compact-carousel-title">{{ $banner->title }}</h5>
                                @endif
                                @if($banner->description)
                                    <p class="compact-carousel-description">{{ Str::limit($banner->description, 80) }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Navigation buttons -->
                    @if($topBanners->count() > 1)
                    <button class="carousel-control-prev compact-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon compact-control-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next compact-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon compact-control-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

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


<!-- Sidebar Banner Section -->
@if($sidebarBanners->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Thông tin quan trọng</h2>
                <p class="section-subtitle">Các thông báo và sự kiện đặc biệt</p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($sidebarBanners as $banner)
            <div class="col-lg-4 col-md-6">
                <div class="card banner-card h-100">
                    <div class="card-body text-center p-4">
                        @if($banner->image)
                            @if($banner->link)
                                <a href="{{ $banner->link }}" target="_blank" class="d-block mb-3">
                                    <img src="{{ asset('storage/' . $banner->image) }}" 
                                         alt="{{ $banner->title }}" 
                                         class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $banner->image) }}" 
                                     alt="{{ $banner->title }}" 
                                     class="img-fluid rounded mb-3" style="max-height: 200px; object-fit: cover;">
                            @endif
                        @endif
                        <h5 class="card-title">{{ $banner->title }}</h5>
                        @if($banner->description)
                            <p class="card-text text-muted">{{ Str::limit($banner->description, 100) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

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
                        <a href="{{ route('admin.reports.user-reports') }}" class="btn btn-primary">Xem báo cáo</a>
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


<!-- Footer Banner Section -->
@if($footerBanners->count() > 0)
<section class="py-5" style="background: #f8f9fa;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h3 class="section-title">Đối tác & Hỗ trợ</h3>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($footerBanners as $banner)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="footer-banner text-center">
                    @if($banner->link)
                        <a href="{{ $banner->link }}" target="_blank" class="d-block">
                            <img src="{{ asset('storage/' . $banner->image) }}" 
                                 alt="{{ $banner->title }}" 
                                 class="img-fluid" style="max-height: 80px; object-fit: contain;">
                        </a>
                    @else
                        <img src="{{ asset('storage/' . $banner->image) }}" 
                             alt="{{ $banner->title }}" 
                             class="img-fluid" style="max-height: 80px; object-fit: contain;">
                    @endif
                    @if($banner->title)
                        <p class="mt-2 mb-0 small text-muted">{{ $banner->title }}</p>
                    @endif
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


<!-- CSS -->
<style>
/* Compact Carousel Styles */
.compact-carousel {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.compact-carousel-inner {
    border-radius: 12px;
}

.compact-carousel-image {
    height: 250px; /* Giảm chiều cao */
    object-fit: cover;
}

.compact-caption {
    background: linear-gradient(transparent, rgba(0,0,0,0.6));
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1.5rem;
    text-align: left;
}

.compact-carousel-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
}

.compact-carousel-description {
    font-size: 0.9rem;
    margin-bottom: 0;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.6);
    opacity: 0.9;
}

.compact-indicators {
    bottom: 10px;
}

.compact-indicators button {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin: 0 4px;
    background-color: rgba(255,255,255,0.5);
    border: none;
}

.compact-indicators button.active {
    background-color: white;
}

.compact-control-prev,
.compact-control-next {
    width: 40px;
    height: 40px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.3);
    border-radius: 50%;
    margin: 0 10px;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.compact-control-prev:hover,
.compact-control-next:hover {
    background: rgba(0,0,0,0.6);
    opacity: 1;
}

.compact-control-icon {
    width: 20px;
    height: 20px;
}

/* Các style khác giữ nguyên */
.banner-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.banner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}
.footer-banner {
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}
.footer-banner:hover {
    transform: scale(1.05);
}

/* Responsive cho carousel nhỏ */
@media (max-width: 768px) {
    .compact-carousel-image {
        height: 180px;
    }
    
    .compact-carousel-title {
        font-size: 1.1rem;
    }
    
    .compact-carousel-description {
        font-size: 0.8rem;
    }
    
    .compact-caption {
        padding: 1rem;
    }
    
    .compact-control-prev,
    .compact-control-next {
        width: 35px;
        height: 35px;
        margin: 0 5px;
    }
}

@media (max-width: 576px) {
    .compact-carousel-image {
        height: 150px;
    }
}
</style>


<!-- JavaScript cho Carousel -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('bannerCarousel');
    
    if (carousel) {
        const carouselInstance = new bootstrap.Carousel(carousel, {
            interval: 5000,
            wrap: true,
            pause: 'hover'
        });
    }
});
</script>
@endsection