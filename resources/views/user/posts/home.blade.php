@extends('layouts.user')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Bài viết mới nhất</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($posts as $post)
            <div class="bg-white shadow rounded overflow-hidden">
                @if ($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2">
                        <a href="{{ route('user.posts.show', $post->slug) }}" class="text-blue-600 hover:underline">
                            {{ $post->title }}
                        </a>
                    </h2>
                    <p class="text-sm text-gray-500 mb-2">Tác giả: {{ $post->author }} - {{ $post->published_at }}</p>
                    <p class="text-gray-700">{{ Str::limit($post->excerpt, 100) }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
@endsection
