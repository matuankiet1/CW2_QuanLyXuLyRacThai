@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="mx-auto" style="max-width: 800px;">
        <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
        <p class="text-muted mb-4">
            ✍️ {{ $post->author }} · {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') }}
        </p>

        @if ($post->image)
            <img src="{{ asset( $post->image) }}" alt="{{ $post->title }}" class="img-fluid rounded mb-4">
        @endif

        <div class="mb-5" style="white-space: pre-line;">
            {!! nl2br(e($post->content)) !!}
        </div>

        {{-- Nút quay lại trang chủ --}}
        <div class="text-center mt-4">
            <a href="{{ route('user.posts.home') }}" class="btn btn-success btn-lg px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Quay lại trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
