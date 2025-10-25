@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
    <h2 class="text-xl font-semibold mb-4">Chỉnh sửa bài viết</h2>

    <form method="POST" action="{{ route('admin.posts.update', $post->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-medium">Tiêu đề *</label>
                <input 
                    name="title" 
                    value="{{ old('title', $post->title) }}" 
                    required 
                    class="w-full border p-2 rounded"
                />
            </div>
            <div>
                <label class="block mb-1 font-medium">Tác giả *</label>
                <input 
                    name="author" 
                    value="{{ old('author', $post->author) }}" 
                    required 
                    class="w-full border p-2 rounded"
                />
            </div>
        </div>

        <div class="mt-4">
            <label class="block mb-1 font-medium">Danh mục *</label>
            <input 
                name="category" 
                value="{{ old('category', $post->category) }}" 
                required 
                class="w-full border p-2 rounded"
            />
        </div>

        <div class="mt-4">
            <label class="block mb-1 font-medium">Mô tả ngắn *</label>
            <textarea 
                name="excerpt" 
                required 
                rows="3" 
                class="w-full border p-2 rounded"
            >{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        <div class="mt-4">
            <label class="block mb-1 font-medium">Nội dung *</label>
            <textarea 
                name="content" 
                required 
                rows="6" 
                class="w-full border p-2 rounded"
            >{{ old('content', $post->content) }}</textarea>
        </div>

        <div class="mt-4">
            <label class="block mb-1 font-medium">Ảnh đại diện (URL)</label>
            <input 
                name="image_url" 
                value="{{ old('image_url', $post->image_url) }}" 
                class="w-full border p-2 rounded"
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block mb-1 font-medium">Trạng thái *</label>
                <select 
                    name="status" 
                    required 
                    class="w-full border p-2 rounded"
                >
                    <option value="draft" @selected(old('status', $post->status) == 'draft')>Nháp</option>
                    <option value="published" @selected(old('status', $post->status) == 'published')>Đã xuất bản</option>
                    <option value="archived" @selected(old('status', $post->status) == 'archived')>Lưu trữ</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">Ngày xuất bản *</label>
                <input 
                    type="date" 
                    name="publish_date" 
                    value="{{ old('publish_date', optional($post->publish_date)->format('Y-m-d')) }}" 
                    required 
                    class="w-full border p-2 rounded"
                />
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.posts.index') }}" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
                Hủy
            </a>
            <button 
                type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Cập nhật bài viết
            </button>
        </div>
    </form>
</div>
@endsection
