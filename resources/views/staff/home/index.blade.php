@extends('layouts.staff')

@section('title', 'Trang chủ - Hệ thống quản lý xử lý rác thải')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
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
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <i class="fas fa-recycle" style="font-size: 15rem; color: rgba(255,255,255,0.3);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
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
                    <h6 class="text-muted">Sắp tới</h6>
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
<section class="py-5 bg-white">
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
<section class="py-5">
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
<section class="py-5 bg-light">
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
@if($banners->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Thông báo quan trọng</h2>
            <p class="section-subtitle">Các thông báo và sự kiện mới nhất</p>
        </div>
        
        <div class="row g-4">
            @foreach($banners as $banner)
            <div class="col-lg-4 col-md-6">
                <div class="banner-slide" style="background-image: url('{{ asset($banner->image) }}')">
                    <div class="banner-overlay">
                        <div>
                            <h5 class="mb-3">{{ $banner->title }}</h5>
                            @if($banner->description)
                                <p class="mb-3">{{ Str::limit($banner->description, 100) }}</p>
                            @endif
                            @if($banner->link)
                                <a href="{{ $banner->link }}" class="btn btn-light btn-sm">
                                    Tìm hiểu thêm
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

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

@push('scripts')
<script>
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
