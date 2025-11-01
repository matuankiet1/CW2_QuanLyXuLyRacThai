@extends('layouts.user')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-6">
        <h1 class="text-4xl font-bold mb-4 text-gray-800">{{ $post->title }}</h1>

        <p class="text-gray-500 mb-4">
            ✍️ {{ $post->author }} · {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') }}
        </p>

        @if ($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full rounded-lg mb-6">
        @endif

        <div class="prose max-w-none mb-10 text-gray-800">
            {!! nl2br(e($post->content)) !!}
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('user.posts.home') }}"
               class="inline-block px-6 py-2 bg-blue-500 text-black font-semibold rounded-lg shadow hover:bg-blue-600 active:bg-blue-700 transition-all duration-200">
                ← Quay lại trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
