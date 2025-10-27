@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
        <h2 class="text-xl font-semibold mb-4">Thêm bài viết mới</h2>

        <form method="POST" action="{{ route('admin.posts.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium">Tiêu đề *</label>
                    <input name="title" value="{{ old('title') }}" required class="w-full border p-2 rounded" />
                </div>
                <div>
                    <label class="block mb-1 font-medium">Tác giả *</label>
                    <input name="author" value="{{ old('author') }}" required class="w-full border p-2 rounded" />
                </div>
            </div>

            <div class="mt-4">
                <label class="block mb-1 font-medium">Mô tả ngắn *</label>
                <textarea name="excerpt" required rows="3" class="w-full border p-2 rounded">{{ old('excerpt') }}</textarea>
            </div>

            <div class="mt-4">
                <label class="block mb-1 font-medium">Nội dung *</label>
                <textarea name="content" required rows="6" class="w-full border p-2 rounded">{{ old('content') }}</textarea>
            </div>

            <div class="mt-4">
                <label class="block mb-1 font-medium">Ảnh đại diện (URL)</label>
                <input name="image" value="{{ old('image') }}" class="w-full border p-2 rounded" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block mb-1 font-medium">Trạng thái *</label>
                    <select name="status" required class="w-full border p-2 rounded">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                    </select>
                </div>


                <div>
                    <label class="block mb-1 font-medium">Ngày xuất bản</label>
                    <input type="date" name="published_at" value="{{ old('published_at') }}"
                        class="w-full border p-2 rounded" />
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-500">Trạng thái hiện tại:
                <strong id="debug-status"></strong>
            </p>

            <script>
                document.addEventListener('change', function (e) {
                    if (e.target.name === 'status') {
                        document.getElementById('debug-status').textContent = e.target.value;
                    }
                });
            </script>
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.posts.index') }}" class="bg-gray-200 px-4 py-2 rounded">Hủy</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Thêm bài viết
                </button>
            </div>
        </form>
    </div>
@endsection