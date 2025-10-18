@extends('dashboard.homelayouts')

@section('content')
<br>
<div class="container mt-5">
    <h1 class="mb-3">{{ $post->title }}</h1>
    <p class="text-secondary mb-3">👤 {{ $post->user->full_name ?? 'Không rõ' }} | 📅 {{ $blposstog->created_at->format('d/m/Y') }}</p>
    @if($post->image)
        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="img-fluid mb-4" style="max-height: 400px; object-fit: cover;">
    @endif
    <div>{!! $post->content !!}</div>
    <a href="{{ route('posts.home') }}" class="btn btn-outline-secondary mt-4">Quay lại danh sách</a>
</div>
@endsection
