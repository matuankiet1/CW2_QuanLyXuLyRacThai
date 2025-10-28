@extends('layouts.user')

@section('title', $post->title . ' - EcoWaste')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3 bg-light">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('posts.home') }}">Bài viết</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
        </ol>
    </div>
</nav>

<!-- Post Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <article class="card">
                    @if($post->image)
                        <img src="{{ asset($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 400px; object-fit: cover;">
                    @endif
                    
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <span class="badge bg-primary mb-3">{{ $post->post_categories }}</span>
                            <h1 class="display-5 fw-bold mb-3">{{ $post->title }}</h1>
                            
                            <div class="d-flex align-items-center text-muted mb-4">
                                <div class="d-flex align-items-center me-4">
                                    <i class="fas fa-user me-2"></i>
                                    <span>{{ $post->author }}</span>
                                </div>
                                <div class="d-flex align-items-center me-4">
                                    <i class="fas fa-calendar me-2"></i>
                                    <span>{{ $post->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-eye me-2"></i>
                                    <span>{{ rand(100, 1000) }} lượt xem</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($post->excerpt)
                            <div class="alert alert-light border-start border-primary border-4 mb-4">
                                <p class="mb-0 fst-italic">{{ $post->excerpt }}</p>
                            </div>
                        @endif
                        
                        <div class="post-content">
                            {!! $post->content !!}
                        </div>
                        
                        <hr class="my-5">
                        
                        <!-- Tags -->
                        <div class="mb-4">
                            <h6 class="mb-3">Tags:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-dark border">#{{ $post->post_categories }}</span>
                                <span class="badge bg-light text-dark border">#môi trường</span>
                                <span class="badge bg-light text-dark border">#rác thải</span>
                                <span class="badge bg-light text-dark border">#bảo vệ môi trường</span>
                            </div>
                        </div>
                        
                        <!-- Share Buttons -->
                        <div class="d-flex align-items-center gap-3">
                            <span class="fw-semibold">Chia sẻ:</span>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f me-1"></i>Facebook
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter me-1"></i>Twitter
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                            </a>
                            <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard()">
                                <i class="fas fa-link me-1"></i>Copy Link
                            </button>
                        </div>
                    </div>
                </article>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Bài viết liên quan</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($relatedPosts as $relatedPost)
                        <div class="p-3 border-bottom">
                            <div class="d-flex">
                                @if($relatedPost->image)
                                    <img src="{{ asset($relatedPost->image) }}" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;" alt="{{ $relatedPost->title }}">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('posts.show', $relatedPost->id) }}" class="text-decoration-none">
                                            {{ Str::limit($relatedPost->title, 50) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $relatedPost->created_at->format('d/m/Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Categories -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Danh mục</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Tin tức môi trường
                                <span class="badge bg-primary rounded-pill">12</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Hướng dẫn xử lý rác
                                <span class="badge bg-primary rounded-pill">8</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Sự kiện môi trường
                                <span class="badge bg-primary rounded-pill">5</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Công nghệ xanh
                                <span class="badge bg-primary rounded-pill">3</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Newsletter -->
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Đăng ký nhận tin</h5>
                        <p class="card-text text-muted">
                            Nhận thông tin mới nhất về môi trường và xử lý rác thải
                        </p>
                        <form>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Nhập email của bạn">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-envelope me-2"></i>Đăng ký
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function copyToClipboard() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function() {
            // Show success message
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Đã copy!';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        });
    }
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
@endpush