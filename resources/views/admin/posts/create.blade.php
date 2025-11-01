@extends('layouts.admin-with-sidebar')

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
                <label class="block mb-1 font-medium">Danh mục *</label>
                <select name="post_categories" required class="w-full border p-2 rounded">
                    <option value="">-- Chọn danh mục --</option>
                    <option value="Tin tức" {{ old('post_categories') == 'Tin tức' ? 'selected' : '' }}>Tin tức</option>
                    <option value="Kiến thức" {{ old('post_categories') == 'Kiến thức' ? 'selected' : '' }}>Kiến thức
                    </option>
                    <option value="Tuyên truyền" {{ old('post_categories') == 'Tuyên truyền' ? 'selected' : '' }}>Tuyên truyền
                    </option>
                </select>
            </div>

            <div class="mt-4">
                <label class="block mb-1 font-medium">Ảnh đại diện *</label>

                {{-- Dropdown chọn ảnh --}}
                <select name="image" id="imageSelect" class="w-full border p-2 rounded">
                    <option value="">-- Chọn ảnh có sẵn --</option>
                    @foreach ($images as $img)
                        <option value="{{ $img }}" {{ old('image') == $img ? 'selected' : '' }}>
                            {{ basename($img) }}
                        </option>
                    @endforeach
                </select>

                {{-- Hiển thị ảnh preview --}}
                <div id="imagePreview" class="mt-3">
                    @if (old('image'))
                        <img src="{{ asset(old('image')) }}" class="w-40 rounded shadow">
                    @endif
                </div>
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