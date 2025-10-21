@extends('dashboard.admin')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Thêm bài viết mới</h1>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Tiêu đề:</label>
                <input type="text" id="title" name="title" placeholder="Nhập tiêu đề bài viết" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div>
                <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Nội dung:</label>
                <textarea id="content" name="content" placeholder="Nhập nội dung bài viết" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>

            <div>
                <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Hình ảnh:</label>
                <input type="file" id="image" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Lưu bài viết
            </button>
        </form>
    </div>
@endsection
