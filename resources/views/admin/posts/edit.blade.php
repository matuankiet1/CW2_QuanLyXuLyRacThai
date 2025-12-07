@extends('layouts.admin-with-sidebar')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">✏️ Chỉnh sửa bài viết</h2>

        {{-- Hiển thị lỗi xác thực --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.posts.update', $post->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Tiêu đề --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Tiêu đề *</label>
                <input name="title" value="{{ old('title', $post->title) }}" required
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring focus:ring-blue-200">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tác giả --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Tác giả *</label>
                <input name="author" value="{{ old('author', $post->author) }}" required
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring focus:ring-blue-200">
                @error('author')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mô tả ngắn --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Mô tả ngắn *</label>
                <textarea name="excerpt" rows="3" required
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring focus:ring-blue-200">{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nội dung --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Nội dung *</label>
                <textarea name="content" rows="6" required
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring focus:ring-blue-200">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Danh mục --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Danh mục *</label>
                <select name="post_categories"
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring focus:ring-blue-200">
                    <option value="">-- Chọn danh mục --</option>
                    <option value="Tin tức" {{ old('post_categories', $post->post_categories) == 'Tin tức' ? 'selected' : '' }}>Tin tức</option>
                    <option value="Kiến thức" {{ old('post_categories', $post->post_categories) == 'Kiến thức' ? 'selected' : '' }}>Kiến thức</option>
                    <option value="Tuyên truyền" {{ old('post_categories', $post->post_categories) == 'Tuyên truyền' ? 'selected' : '' }}>Tuyên truyền</option>
                </select>
                @error('post_categories')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Trạng thái --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Trạng thái *</label>
                <select name="status"
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring focus:ring-blue-200">
                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Nháp</option>
                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Đã xuất bản
                    </option>
                    <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Lưu trữ
                    </option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ngày xuất bản --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Ngày xuất bản</label>
                <input type="date" name="published_at"
                    value="{{ old('published_at', $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('Y-m-d') : '') }}"
                    class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring focus:ring-blue-200">
                @error('published_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ảnh bài viết --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Ảnh đại diện</label>
                <input type="file" name="image" id="imageInput" accept="image/*"
                    class="block w-full text-sm text-gray-700 border border-gray-300 rounded p-2 cursor-pointer">

                {{-- Ảnh hiện tại --}}
                @if ($post->image)
                    <div class="mt-3">
                        <p class="text-sm text-gray-600 mb-1">Ảnh hiện tại:</p>
                        <img src="{{ asset($post->image) }}" alt="Ảnh bài viết" class="w-48 h-48 object-cover rounded border">
                    </div>
                @endif

                {{-- Ảnh preview khi chọn mới --}}
                <div class="mt-3">
                    <img id="imagePreview" src="#" alt="Xem trước ảnh mới"
                        class="hidden w-48 h-48 object-cover rounded border">
                </div>

                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nút hành động --}}
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.posts.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Hủy</a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Cập nhật bài
                    viết</button>
            </div>
            <input type="hidden" name="updated_at" value="{{ $post->updated_at }}">
        </form>
    </div>

    {{-- Script Preview Ảnh --}}
    <script>
        document.getElementById('imageInput').addEventListener('change', function (event) {
            const [file] = event.target.files;
            const preview = document.getElementById('imagePreview');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
            }
        });
    </script>
@endsection