@extends('layouts.staff')

@section('content')
    <div class="container py-5">
        <h1 class="fw-bold mb-5 text-center">📰 Bài viết mới nhất</h1>
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('user.posts.home') }}" class="flex flex-col md:flex-row gap-4">
                {{-- Tìm kiếm --}}
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Tìm kiếm bài viết..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                {{-- Nút tìm kiếm --}}
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
            </form>
        </div>
        <div class="row g-4">
            @foreach ($posts as $post)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if ($post->image)
                            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="card-img-top"
                                style="height: 200px; object-fit: cover;">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">
                                <a href="{{ route('user.posts.show', $post->slug) }}"
                                    class="text-decoration-none text-success fw-semibold">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-2">
                                ✍️ {{ $post->author }} · {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') }}
                            </p>
                            <p class="card-text text-secondary flex-grow-1">
                                {{ Str::limit($post->excerpt, 100) }}
                            </p>
                            <a href="{{ route('user.posts.show', $post->slug) }}" class="btn btn-outline-success mt-3 w-100">
                                Đọc thêm <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Phân trang --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection