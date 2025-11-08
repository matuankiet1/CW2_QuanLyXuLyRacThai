@extends('layouts.admin-with-sidebar')

@section('content')
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md mb-4">
            <div class="p-4">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium mb-1">Tìm kiếm</label>
                        <input type="text" name="search" value="{{ $search }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Tiêu đề, tác giả...">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium mb-1">Danh mục</label>
                        <select name="post_categories" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option>Tất cả danh mục</option>
                            <option>Tin tức</option>
                            <option>Kiến thức</option>
                            <option>Tuyên truyền</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium mb-1">Trạng thái</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="all">Tất cả trạng thái</option>
                            <option value="published">Đã xuất bản</option>
                            <option value="draft">Nháp</option>
                            <option value="archived">Lưu trữ</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 flex flex-col md:flex-row gap-2">
                        <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Lọc</button>
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-admin">+ Thêm bài viết</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse table-auto">
                    <thead>
                        <tr class="border-b">
                            <th class="px-4 py-3 text-center font-semibold" style="width: 70px">STT</th>
                            <th class="px-4 py-3 text-left font-semibold" style="width: 100px">Ảnh</th>
                            <th class="px-4 py-3 text-left font-semibold">Tiêu đề</th>
                            <th class="px-4 py-3 text-left font-semibold">Tác giả</th>
                            <th class="px-4 py-3 text-left font-semibold">Danh mục</th>
                            <th class="px-4 py-3 text-left font-semibold">Ngày xuất bản</th>
                            <th class="px-4 py-3 text-left font-semibold">Trạng thái</th>
                            <th class="px-4 py-3 text-right font-semibold">Lượt xem</th>
                            <th class="px-4 py-3 text-right font-semibold">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($posts->count() > 0)
                            @foreach ($posts as $index => $post)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center">{{ $posts->firstItem() + $index }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($post->image)
                                            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="border rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-gray-100 flex items-center justify-center text-gray-500 text-sm" style="width: 60px; height: 60px; border-radius: 4px;">Không có</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium">{{ Str::limit($post->title, 40) }}</div>
                                        <div class="text-gray-500 text-sm">{{ Str::limit($post->excerpt, 60) }}</div>
                                    </td>
                                    <td class="px-4 py-3">{{ $post->author }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $post->post_categories }}</span></td>
                                    <td class="px-4 py-3">{{ optional($post->published_at)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        @if ($post->status === 'published')
                                            <span class="px-2 py-1 rounded text-xs font-medium bg-green-500 text-white">Đã xuất bản</span>
                                        @elseif ($post->status === 'draft')
                                            <span class="px-2 py-1 rounded text-xs font-medium bg-gray-500 text-white">Nháp</span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs font-medium bg-yellow-500 text-white">Lưu trữ</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">{{ number_format($post->views) }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex gap-2 text-sm justify-end">
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Sửa</a>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-4">Không có bài viết nào.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="border-t pt-4 bg-white px-4 pb-4">
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
