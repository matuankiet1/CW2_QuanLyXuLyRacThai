@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý banners - Admin')

@section('content')
<!-- ✅ Thông báo thành công / lỗi -->
@if (session('success') || session('error'))
    <div 
        id="alert-message"
        class="mb-4 px-4 py-3 rounded-lg shadow-md flex items-center justify-between 
        {{ session('success') ? 'bg-green-50 text-green-800 border border-green-300' : 'bg-red-50 text-red-800 border border-red-300' }}">
        <div class="flex items-center gap-2">
            @if (session('success'))
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
            @else
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            @endif
            <span class="font-medium">
                {{ session('success') ?? session('error') }}
            </span>
        </div>
        <button onclick="document.getElementById('alert-message').remove()" class="text-sm hover:opacity-75">
            ✕
        </button>
    </div>

    <script>
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            const alert = document.getElementById('alert-message');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
@endif

    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
    <div>
        
        <p class="text-gray-600 mt-1">Quản lý các banners quảng cáo và hiển thị trên website</p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Sort Dropdown -->
        <form action="{{ route('admin.banners.index') }}" method="GET" class="flex items-center gap-2">
            <!-- 🔍 Ô tìm kiếm -->
    <input type="text" name="search" placeholder="Tìm theo tiêu đề..."
        value="{{ $search ?? '' }}"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">

    <select name="sort"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Mới nhất</option>
        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
        <option value="active" {{ $sort === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
        <option value="inactive" {{ $sort === 'inactive' ? 'selected' : '' }}>Đã ẩn</option>
        <option value="position" {{ $sort === 'position' ? 'selected' : '' }}>Theo vị trí hiển thị</option>
    </select>

    <button type="submit"
        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2l-7 8v6l-4-2v-4L3 6V4z" />
        </svg>
        Lọc
    </button>
</form>


        <!-- Add Button -->
        <a href="{{ route('admin.banners.create') }}"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Thêm Banner
        </a>
    </div>
</div>


        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiêu đề</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vị trí</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($banners as $banner)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $banner->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-16 w-40 bg-gray-200 rounded flex items-center justify-center overflow-hidden">
                                    @if($banner->image)
                                        @php
                // Chỉ lấy tên file từ đường dẫn trong database
                $filename = basename($banner->image);
                $storagePath = storage_path('app/public/banners/' . $filename);
                $fileExists = file_exists($storagePath);
            @endphp
            
            @if($fileExists)
                <img src="{{ route('banner.image', $filename) }}" 
                     alt="{{ $banner->title }}" 
                     class="h-full w-full object-cover">
            @else
                <div class="text-center p-2">
                    <span class="text-red-500 text-xs block">❌ Ảnh lỗi</span>
                    <span class="text-gray-400 text-xs">{{ $filename }}</span>
                </div>
            @endif
                                    @else
                                        <span class="text-gray-500 text-xs">Chưa có ảnh</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $banner->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $banner->position === 'top' ? 'bg-blue-100 text-blue-800' : 
                                       ($banner->position === 'sidebar' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    @if($banner->position === 'top')
                                        Trang chủ - Top
                                    @elseif($banner->position === 'sidebar')
                                        Sidebar
                                    @else
                                        Footer
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $banner->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $banner->status ? 'Đang hoạt động' : 'Đã ẩn' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $banner->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <!-- Nút Sửa -->
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                        class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded hover:bg-blue-600 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Sửa
                                    </a>
                                    
                                    <!-- Nút Xóa -->
                                    <a href="{{ route('admin.banners.confirm-delete', $banner->id) }}"
   class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded hover:bg-red-600 transition">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
    </svg>
    Xóa
</a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Chưa có banner nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if ($banners->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $banners->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

