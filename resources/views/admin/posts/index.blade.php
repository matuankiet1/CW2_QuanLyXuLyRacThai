@extends('dashboard.admin')

@section('dashboard-content')
<div class="space-y-6">

    {{-- Thống kê --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-card title="Tổng bài viết" icon="file-text" color="blue" :value="$totalPosts" sub="+3 trong tháng này" />
        <x-card title="Đã xuất bản" icon="check-circle" color="green" :value="$publishedPosts" sub="{{ number_format(($publishedPosts / max($totalPosts,1)) * 100, 0) }}% tổng số bài" />
        <x-card title="Bài nháp" icon="clock" color="yellow" :value="$draftPosts" sub="Chờ xuất bản" />
    </div>

    {{-- Bộ lọc --}}
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Tìm kiếm theo tiêu đề, tác giả..." class="border p-2 rounded w-64" />
                <select name="category" class="border p-2 rounded">
                    <option>Tất cả danh mục</option>
                    <option>Hướng dẫn</option>
                    <option>Tin tức</option>
                    <option>Sự kiện</option>
                    <option>Kiến thức</option>
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
                <a href="#" class="bg-gray-100 px-3 py-2 rounded">Xuất Excel</a>
                <a href="{{ route('admin.posts.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded">+ Thêm bài viết</a>
            </div>
        </form>

        {{-- Bảng --}}
        <table class="min-w-full border-collapse border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left border">Tiêu đề</th>
                    <th class="p-3 border">Tác giả</th>
                    <th class="p-3 border">Danh mục</th>
                    <th class="p-3 border">Ngày xuất bản</th>
                    <th class="p-3 border">Trạng thái</th>
                    <th class="p-3 border">Lượt xem</th>
                    <th class="p-3 border">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">
                            <div class="font-medium">{{ $post->title }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $post->excerpt }}</div>
                        </td>
                        <td class="p-3">{{ $post->author }}</td>
                        <td class="p-3"><span class="px-2 py-1 border rounded">{{ $post->category }}</span></td>
                        <td class="p-3">{{ $post->publish_date->format('d/m/Y') }}</td>
                        <td class="p-3">
                            @if ($post->status === 'published')
                                <span class="text-green-600 font-medium">Đã xuất bản</span>
                            @elseif ($post->status === 'draft')
                                <span class="text-gray-500">Nháp</span>
                            @else
                                <span class="text-yellow-600">Lưu trữ</span>
                            @endif
                        </td>
                        <td class="p-3 text-right">{{ number_format($post->views) }}</td>
                        <td class="p-3 text-right">
                            <a href="{{ route('posts.edit', $post) }}" class="text-blue-600 hover:underline">Sửa</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="mt-4">
            {{ $posts->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
