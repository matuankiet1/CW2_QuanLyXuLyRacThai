@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-semibold mb-4">
        {{ $article->exists ? 'Chỉnh sửa bài viết' : 'Thêm bài viết mới' }}
    </h2>

    <form method="POST" action="{{ $article->exists ? route('articles.update', $article) : route('articles.store') }}">
        @csrf
        @if($article->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-medium">Tiêu đề *</label>
                <input name="title" value="{{ old('title', $article->title) }}" required class="w-full border p-2 rounded" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Tác giả *</label>
                <input name="author" value="{{ old('author', $article->author) }}" required class="w-full border p-2 rounded" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block mb-1 font-medium">Danh mục *</label>
                <select name="category" required class="w-full border p-2 rounded">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach (['Tin tức', 'Sự kiện', 'Hướng dẫn', 'Kiến thức'] as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $article->category) == $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Trạng thái *</label>
                <select name="status" required class="w-full border p-2 rounded">
                    <option value="draft" @selected(old('status', $article->status) == 'draft')>Nháp</option>
                    <option value="published" @selected(old('status', $article->status) == 'published')>Đã xuất bản</option>
                    <option value="archived" @selected(old('status', $article->status) == 'archived')>Lưu trữ</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <label class="block mb-1 font-medium">Ngày xuất bản *</label>
            <input type="date" name="publish_date" value="{{ old('publish_date', optional($article->publish_date)->format('Y-m-d')) }}" class="w-full border p-2 rounded" />
        </div>

        <div class="mt-4">
            <label class="block mb-1 font-medium">Mô tả ngắn *</label>
            <textarea name="excerpt" required rows="3" class="w-full border p-2 rounded">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>

        <div class="mt-4">
            <label class="block mb-1 font-medium">Nội dung *</label>
            <textarea name="content" required rows="6" class="w-full border p-2 rounded">{{ old('content', $article->content) }}</textarea>
        </div>

        <div class="mt-4">
            <label class="block mb-1 font-medium">Ảnh đại diện (URL)</label>
            <input name="image_url" value="{{ old('image_url', $article->image_url) }}" class="w-full border p-2 rounded" />
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('articles.index') }}" class="bg-gray-200 px-4 py-2 rounded">Hủy</a>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                {{ $article->exists ? 'Cập nhật' : 'Thêm mới' }}
            </button>
        </div>
    </form>
</div>
@endsection
