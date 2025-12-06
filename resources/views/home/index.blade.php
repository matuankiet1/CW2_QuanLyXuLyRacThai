@extends('layouts.user')

@section('title', 'Trang chủ - Hệ thống quản lý xử lý rác thải')

@section('content')
<!-- Hero Section -->
@if(isset($topBanners) && $topBanners->count() > 0)
<section class="hero-banner-section">
    <div id="fullWidthBannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($topBanners as $key => $banner)
            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                <div class="banner-container">
                    <img src="{{ route('banner.image', basename($banner->image)) }}" 
                         class="d-block w-100 banner-image" 
                         alt="{{ $banner->title }}">
                    <div class="banner-content">
                        <div class="container px-5">
                            <div class="row align-items-center min-vh-75">
                                <div class="col-lg-6">
                                    <h1 class="display-4 fw-bold text-white mb-4">
                                        Quản lý rác thải thông minh
                                        <span class="text-warning">vì tương lai xanh</span>
                                    </h1>
                                    <p class="lead text-white mb-4">
                                        Hệ thống quản lý xử lý rác thải hiện đại, góp phần bảo vệ môi trường 
                                        và xây dựng cộng đồng bền vững.
                                    </p>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <a href="{{ route('user.posts.home') }}" class="btn btn-light btn-lg">
                                            <i class="fas fa-newspaper me-2"></i>Xem bài viết
                                        </a>
                                        <a href="{{ route('home.about') }}" class="btn btn-outline-light btn-lg">
                                            <i class="fas fa-info-circle me-2"></i>Tìm hiểu thêm
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($topBanners->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#fullWidthBannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#fullWidthBannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
</section>
@else
<!-- Fallback Hero Section khi không có banner -->
<section class="hero-section">
    <div class="container px-5">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="display-4 fw-bold mb-4">
                    Quản lý rác thải thông minh
                    <span class="text-warning">vì tương lai xanh</span>
                </h1>
                <p class="lead mb-4">
                    Hệ thống quản lý xử lý rác thải hiện đại, góp phần bảo vệ môi trường 
                    và xây dựng cộng đồng bền vững.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('user.posts.home') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-newspaper me-2"></i>Xem bài viết
                    </a>
                    <a href="{{ route('home.about') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle me-2"></i>Tìm hiểu thêm
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center d-none d-md-block">
                <div class="hero-image">
                    <i class="fas fa-recycle" style="font-size: 15rem; color: rgba(255,255,255,0.3);"></i>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Top Banners Section - Hiển thị banner vị trí "top"
@if(isset($topBanners) && $topBanners->count() > 0)
<section class="py-4">
    <div class="container">
        <div id="topBannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner rounded-3" style="max-height: 500px; overflow: hidden;">
                @foreach($topBanners as $key => $banner)
                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                    @if($banner->link)
                    <a href="{{ $banner->link }}" target="_blank" class="d-block">
                        <img src="{{ route('banner.image', basename($banner->image)) }}" 
                             class="d-block w-100" 
                             alt="{{ $banner->title }}"
                             style="height: 500px; object-fit: cover;">
                    </a>
                    @else
                    <img src="{{ route('banner.image', basename($banner->image)) }}" 
                         class="d-block w-100" 
                         alt="{{ $banner->title }}"
                         style="height: 500px; object-fit: cover;">
                    @endif
                    @if($banner->title || $banner->description)
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                        @if($banner->title)
                        <h3 class="mb-2">{{ $banner->title }}</h3>
                        @endif
                        @if($banner->description)
                        <p class="mb-0">{{ Str::limit($banner->description, 150) }}</p>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            
            @if($topBanners->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#topBannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#topBannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            @endif
        </div>
    </div>
</section>
@endif -->

<!-- Stats Section -->
<section class="m-3 m-md-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['total_posts'] }}</div>
                    <h6 class="text-muted">Bài viết</h6>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['total_schedules'] }}</div>
                    <h6 class="text-muted">Lịch thu gom</h6>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['upcoming_schedules'] }}</div>
                    <h6 class="text-muted">Sự kiện sắp tới</h6>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-number">100%</div>
                    <h6 class="text-muted">Cam kết xanh</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="m-3 m-md-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Tại sao chọn chúng tôi?</h2>
            <p class="section-subtitle">Những tính năng nổi bật giúp quản lý rác thải hiệu quả</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5 class="mb-3">Lịch thu gom thông minh</h5>
                    <p class="text-muted">
                        Quản lý lịch thu gom rác thải một cách khoa học và hiệu quả, 
                        đảm bảo môi trường luôn sạch sẽ.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h5 class="mb-3">Thông tin cập nhật</h5>
                    <p class="text-muted">
                        Cập nhật thường xuyên các bài viết, tin tức về môi trường 
                        và hướng dẫn xử lý rác thải đúng cách.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="mb-3">Báo cáo chi tiết</h5>
                    <p class="text-muted">
                        Theo dõi và phân tích hiệu quả hoạt động thu gom rác thải 
                        thông qua các báo cáo chi tiết.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 class="mb-3">Cộng đồng tham gia</h5>
                    <p class="text-muted">
                        Khuyến khích sự tham gia tích cực của cộng đồng trong 
                        việc bảo vệ môi trường.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h5 class="mb-3">Giao diện thân thiện</h5>
                    <p class="text-muted">
                        Thiết kế giao diện đơn giản, dễ sử dụng trên mọi thiết bị 
                        từ máy tính đến điện thoại.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="mb-3">Bảo mật cao</h5>
                    <p class="text-muted">
                        Đảm bảo thông tin cá nhân và dữ liệu được bảo vệ an toàn 
                        với các tiêu chuẩn bảo mật cao nhất.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Posts Section -->
@if($latestPosts->count() > 0)
<section class="m-3 m-md-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Bài viết mới nhất</h2>
            <p class="section-subtitle">Cập nhật thông tin mới nhất về môi trường và xử lý rác thải</p>
        </div>
        
        <div class="row g-4">
            @foreach($latestPosts as $post)
            <div class="col-lg-4 col-md-6">
                <div class="card post-card">
                    @if($post->image)
                        <img src="{{ asset($post->image) }}" class="post-image" alt="{{ $post->title }}">
                    @else
                        <div class="post-image bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit(strip_tags($post->content), 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $post->created_at->format('d/m/Y') }}
                            </small>
                            <a href="{{ route('user.posts.show', $post->id) }}" class="btn btn-outline-primary btn-sm">
                                Đọc tiếp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('user.posts.home') }}" class="btn btn-primary">
                <i class="fas fa-newspaper me-2"></i>Xem tất cả bài viết
            </a>
        </div>
    </div>
</section>
@endif

<!-- Upcoming Schedules Section -->
@if($upcomingSchedules->count() > 0)
<section class="m-3 m-md-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Lịch thu gom sắp tới</h2>
            <p class="section-subtitle">Thông tin lịch thu gom rác thải trong thời gian tới</p>
        </div>
        
        <div class="row g-4">
            @foreach($upcomingSchedules as $schedule)
            <div class="col-lg-4 col-md-6">
                <div class="card schedule-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $schedule->title ?? 'Thu gom rác thải' }}</h6>
                                <small class="text-muted">{{ $schedule->location ?? 'Địa điểm chưa xác định' }}</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Ngày:</span>
                                <strong>{{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Thời gian:</span>
                                <strong>{{ $schedule->start_time ?? '08:00' }} - {{ $schedule->end_time ?? '17:00' }}</strong>
                            </div>
                        </div>
                        @if($schedule->description)
                            <p class="text-muted small">{{ Str::limit($schedule->description, 80) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Banners Section -->
<!-- Sidebar Banners Section - Hiển thị banner vị trí "sidebar" -->
@if(isset($sidebarBanners) && $sidebarBanners->count() > 0)
<section class="m-3 m-md-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Thông tin quan trọng</h2>
            <p class="section-subtitle">Các thông báo và sự kiện đặc biệt</p>
        </div>
        
        <div class="row g-4">
            @foreach($sidebarBanners as $banner)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    @if($banner->image)
                        @if($banner->link)
                        <a href="{{ $banner->link }}" target="_blank" class="text-decoration-none">
                            <img src="{{ route('banner.image', basename($banner->image)) }}" 
                                 class="card-img-top" 
                                 alt="{{ $banner->title }}"
                                 style="height: 200px; object-fit: cover;">
                        </a>
                        @else
                        <img src="{{ route('banner.image', basename($banner->image)) }}" 
                             class="card-img-top" 
                             alt="{{ $banner->title }}"
                             style="height: 200px; object-fit: cover;">
                        @endif
                    @endif
                    <div class="card-body text-center d-flex flex-column">
                        <h5 class="card-title">{{ $banner->title }}</h5>
                        @if($banner->description)
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($banner->description, 100) }}</p>
                        @endif
                        @if($banner->link)
                        <a href="{{ $banner->link }}" class="btn btn-primary btn-sm mt-auto" target="_blank">
                            Tìm hiểu thêm
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Footer Banners Section - Hiển thị banner vị trí "footer" -->
@if(isset($footerBanners) && $footerBanners->count() > 0)
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title text-white mb-3">Đối tác & Hỗ trợ</h2>
            <p class="section-subtitle text-light opacity-75">Các tổ chức và đơn vị đồng hành cùng chúng tôi</p>
        </div>
        
        <div class="row justify-content-center align-items-center g-4">
            @foreach($footerBanners as $banner)
            <div class="col-lg-2 col-md-3 col-6">
                <div class="partner-item text-center p-3">
                    @if($banner->link)
                    <a href="{{ $banner->link }}" target="_blank" class="d-block text-decoration-none">
                        <div class="partner-logo-container mb-3">
                            <img src="{{ route('banner.image', basename($banner->image)) }}" 
                                 class="partner-logo img-fluid" 
                                 alt="{{ $banner->title }}"
                                 loading="lazy">
                        </div>
                    </a>
                    @else
                    <div class="partner-logo-container mb-3">
                        <img src="{{ route('banner.image', basename($banner->image)) }}" 
                             class="partner-logo img-fluid" 
                             alt="{{ $banner->title }}"
                             loading="lazy">
                    </div>
                    @endif
                    
                    @if($banner->title)
                    <p class="partner-name mb-0 small text-light fw-medium">{{ $banner->title }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
.partner-item {
    transition: all 0.3s ease;
    border-radius: 8px;
}

.partner-item:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.05);
}

.partner-logo-container {
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.partner-item:hover .partner-logo-container {
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.partner-logo {
    max-height: 70px;
    max-width: 100%;
    object-fit: contain;
    filter: grayscale(100%) brightness(0.7);
    transition: all 0.3s ease;
}

.partner-item:hover .partner-logo {
    filter: grayscale(0%) brightness(1);
    transform: scale(1.05);
}

.partner-name {
    transition: all 0.3s ease;
    opacity: 0.8;
}

.partner-item:hover .partner-name {
    opacity: 1;
    color: #fff !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .partner-logo-container {
        height: 80px;
        padding: 10px;
    }
    
    .partner-logo {
        max-height: 60px;
    }
    
    .partner-name {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .partner-logo-container {
        height: 70px;
    }
    
    .partner-logo {
        max-height: 50px;
    }
}
</style>
@endIf

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4">Hãy cùng chúng tôi bảo vệ môi trường!</h2>
                <p class="lead mb-4">
                    Tham gia vào cộng đồng EcoWaste để cùng nhau xây dựng một tương lai xanh và bền vững.
                </p>
                @auth
                    <a href="{{ route('user.posts.home') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-newspaper me-2"></i>Khám phá ngay
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i>Đăng ký ngay
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection


@push('styles')
<style>
.hero-banner-section {
    position: relative;
    overflow: hidden;
}

.banner-container {
    position: relative;
    height: 100vh;
    min-height: 600px;
    max-height: 800px;
}

.banner-image {
    height: 100%;
    object-fit: cover;
    filter: brightness(0.7);
}

.banner-content {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    background: linear-gradient(90deg, rgba(16, 185, 129, 0.8) 0%, rgba(5, 150, 105, 0.6) 100%);
}

.min-vh-75 {
    min-height: 75vh;
}

/* Đảm bảo carousel controls hiển thị đẹp */
.carousel-control-prev,
.carousel-control-next {
    width: 5%;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    padding: 15px;
}

.carousel-control-prev:hover .carousel-control-prev-icon,
.carousel-control-next:hover .carousel-control-next-icon {
    background-color: rgba(255, 255, 255, 0.5);
}
</style>
@endpush

@push('scripts')
<script>
   
    // Auto-advance full width banner carousel
    const fullWidthCarousel = document.getElementById('fullWidthBannerCarousel');
    if (fullWidthCarousel) {
        const carouselInstance = new bootstrap.Carousel(fullWidthCarousel, {
            interval: 5000,
            wrap: true
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards
    document.querySelectorAll('.card, .stats-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
</script>
@endpush
