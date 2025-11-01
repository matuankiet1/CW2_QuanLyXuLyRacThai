@extends('layouts.admin-with-sidebar')

@section('content')
    <div class="space-y-6">
        {{-- Bộ lọc --}}
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tìm kiếm theo tiêu đề, tác giả..."
                        class="border p-2 rounded w-64" />
                    <select name="post_categories" class="border p-2 rounded">
                        <option>Tất cả danh mục</option>
                        <option>Tin tức</option>
                        <option>Kiến thức</option>
                        <option>Tuyên truyền</option>
                    </select>
                    <select name="status" class="border p-2 rounded">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="published">Đã xuất bản</option>
                        <option value="draft">Nháp</option>
                        <option value="archived">Lưu trữ</option>
                    </select>
                    <button class="bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded">Lọc</button>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.posts.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded">+ Thêm bài
                        viết</a>
                </div>
            </form>

            {{-- Bảng --}}
            <table class="min-w-full border-collapse border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-center border w-16">STT</th>
                        <th class="p-3 text-center border w-24">Ảnh</th>
                        <th class="p-3 text-left border">Tiêu đề</th>
                        <th class="p-3 border">Tác giả</th>
                        <th class="p-3 border">Danh mục</th>
                        <th class="p-3 border">Ngày xuất bản</th>
                        <th class="p-3 border">Trạng thái</th>
                        <th class="p-3 border">Lượt xem</th>
                        <th class="p-3 border text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($posts->count() > 0)
                        @foreach ($posts as $index => $post)
                            <tr class="hover:bg-gray-50">
                                {{-- STT --}}
                                <td class="p-3 text-center">
                                    {{ $posts->firstItem() + $index }}
                                </td>

                                {{-- Ảnh thumbnail --}}
                                <td class="p-3 text-center align-middle w-[60px]">
                                    @if ($post->image)
                                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}"
                                            class="h-10 w-10 object-cover rounded-md mx-auto shadow-sm border border-gray-200">
                                    @else
                                        <div
                                            class="w-10 h-10 bg-gray-100 flex items-center justify-center text-gray-400 text-[10px] italic mx-auto rounded-md">
                                            Không có
                                        </div>
                                    @endif
                                </td>


                                {{-- Tiêu đề & mô tả ngắn --}}
                                <td class="p-3">
                                    <div class="font-medium">{{ Str::limit($post->title, 40) }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($post->excerpt, 60) }}</div>
                                </td>

                                <td class="p-3">{{ $post->author }}</td>

                                <td class="p-3">
                                    <span class="px-2 py-1 border rounded text-sm">{{ $post->post_categories }}</span>
                                </td>

                                <td class="p-3 text-center">
                                    {{ optional($post->published_at)->format('d/m/Y') }}
                                </td>

                                <td class="p-3 text-center">
                                    @if ($post->status === 'published')
                                        <span class="text-green-600 font-medium">Đã xuất bản</span>
                                    @elseif ($post->status === 'draft')
                                        <span class="text-gray-500">Nháp</span>
                                    @else
                                        <span class="text-yellow-600">Lưu trữ</span>
                                    @endif
                                </td>

                                <td class="p-3 text-right">{{ number_format($post->views) }}</td>

                                <td class="p-3 text-right flex justify-end gap-2">
                                    {{-- Nút Sửa --}}
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition">
                                        ✏️ Sửa
                                    </a>

                                    {{-- Nút Xóa --}}
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-md transition">
                                            🗑️ Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-500">
                                Không có bài viết nào.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            {{-- Phân trang --}}
            <div class="mt-4">
                {{ $posts->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
<style>
    td img:hover {
        transform: scale(1.2);
        transition: transform 0.2s ease-in-out;
        z-index: 10;
    }
</style>