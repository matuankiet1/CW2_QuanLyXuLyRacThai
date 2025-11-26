@extends('layouts.admin-with-sidebar')

@section('title', 'Trang chủ Admin - Hệ thống quản lý xử lý rác thải')

@section('content')
<div class="container mx-auto px-4">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-8 mb-6 text-white">
        <div class="flex flex-col lg:flex-row items-center justify-between">
            <div class="lg:w-2/3 mb-6 lg:mb-0">
                <h1 class="text-4xl font-bold mb-4">Chào mừng Admin đến với EcoWaste</h1>
                <p class="text-lg mb-6 opacity-90">
                    Quản lý hệ thống xử lý rác thải thông minh, góp phần bảo vệ môi trường và xây dựng tương lai bền vững.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('dashboard.admin') }}" class="px-6 py-3 bg-white text-green-600 font-semibold rounded-lg hover:bg-gray-100 transition shadow-md">
                        <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-green-700 text-white font-semibold rounded-lg hover:bg-green-800 transition border border-white border-opacity-30">
                        <i class="fas fa-users mr-2"></i>Quản lý người dùng
                    </a>
                </div>
            </div>
            <div class="lg:w-1/3 text-center">
                <i class="fas fa-user-shield text-8xl opacity-30"></i>
            </div>
        </div>
    </div>

    <!-- Top Banner Carousel
    @if($topBanners->count() > 0)
    <div class="mb-6">
        <div id="bannerCarousel" class="relative rounded-lg overflow-hidden shadow-lg">
            @if($topBanners->count() > 1)
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-10 flex gap-2">
                @foreach($topBanners as $key => $banner)
                <button type="button" 
                        data-carousel-target="{{ $key }}"
                        class="w-2 h-2 rounded-full {{ $key === 0 ? 'bg-white' : 'bg-white bg-opacity-50' }} transition"
                        aria-label="Slide {{ $key + 1 }}"></button>
                @endforeach
            </div>
            @endif

            <div class="relative h-64 overflow-hidden">
                @foreach($topBanners as $key => $banner)
                <div class="carousel-item {{ $key === 0 ? 'active' : 'hidden' }} absolute inset-0 transition-opacity duration-500">
                    @if($banner->link)
                        <a href="{{ $banner->link }}" target="_blank" class="block h-full">
                            <img src="{{ route('banner.image', basename($banner->image)) }}" 
                                 alt="{{ $banner->title }}" 
                                 class="w-full h-full object-cover">
                            @if($banner->title || $banner->description)
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6 text-white">
                                @if($banner->title)
                                    <h3 class="text-xl font-semibold mb-2">{{ $banner->title }}</h3>
                                @endif
                                @if($banner->description)
                                    <p class="text-sm opacity-90">{{ Str::limit($banner->description, 80) }}</p>
                                @endif
                            </div>
                            @endif
                        </a>
                    @else
                        <img src="{{ route('banner.image', basename($banner->image)) }}" 
                             alt="{{ $banner->title }}" 
                             class="w-full h-full object-cover">
                        @if($banner->title || $banner->description)
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6 text-white">
                            @if($banner->title)
                                <h3 class="text-xl font-semibold mb-2">{{ $banner->title }}</h3>
                            @endif
                            @if($banner->description)
                                <p class="text-sm opacity-90">{{ Str::limit($banner->description, 80) }}</p>
                            @endif
                        </div>
                        @endif
                    @endif
                </div>
                @endforeach
            </div>

            @if($topBanners->count() > 1)
            <button type="button" 
                    id="prevBtn"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-3 rounded-full transition z-10">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button type="button" 
                    id="nextBtn"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-3 rounded-full transition z-10">
                <i class="fas fa-chevron-right"></i>
            </button>
            @endif
        </div>
    </div>
    @endif -->

    <!-- Stats Section -->
    <div class="mb-6">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Thống kê hệ thống</h2>
            <p class="text-gray-500">Tổng quan về hoạt động của hệ thống</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_users'] }}</div>
                        <div class="text-sm font-semibold text-gray-700">Tổng người dùng</div>
                        <div class="text-xs text-gray-500 mt-1">Tất cả tài khoản trong hệ thống</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-shield text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['admin_users'] }}</div>
                        <div class="text-sm font-semibold text-gray-700">Quản trị viên</div>
                        <div class="text-xs text-gray-500 mt-1">Tài khoản có quyền quản lý</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-newspaper text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_posts'] }}</div>
                        <div class="text-sm font-semibold text-gray-700">Bài viết</div>
                        <div class="text-xs text-gray-500 mt-1">Nội dung đã được xuất bản</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-alt text-yellow-600 text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_schedules'] }}</div>
                        <div class="text-sm font-semibold text-gray-700">Lịch thu gom</div>
                        <div class="text-xs text-gray-500 mt-1">Lịch trình đã được lên kế hoạch</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="mb-6">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Thao tác nhanh</h2>
            <p class="text-gray-500">Các chức năng quản lý chính</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition h-full flex flex-col">
                <div class="w-16 h-16 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <h5 class="text-lg font-semibold text-gray-900 mb-2">Quản lý người dùng</h5>
                <p class="text-sm text-gray-500 mb-4 flex-grow">Xem, chỉnh sửa và quản lý tài khoản người dùng trong hệ thống.</p>
                <a href="{{ route('admin.users.index') }}" class="btn-admin inline-block text-center">Quản lý</a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition h-full flex flex-col">
                <div class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-shield text-white text-2xl"></i>
                </div>
                <h5 class="text-lg font-semibold text-gray-900 mb-2">Phân quyền</h5>
                <p class="text-sm text-gray-500 mb-4 flex-grow">Cấp quyền admin cho người dùng và quản lý vai trò trong hệ thống.</p>
                <a href="{{ route('admin.roles.index') }}" class="btn-admin inline-block text-center">Phân quyền</a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition h-full flex flex-col">
                <div class="w-16 h-16 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-newspaper text-white text-2xl"></i>
                </div>
                <h5 class="text-lg font-semibold text-gray-900 mb-2">Quản lý bài viết</h5>
                <p class="text-sm text-gray-500 mb-4 flex-grow">Tạo, chỉnh sửa và quản lý nội dung bài viết trên trang web.</p>
                <a href="{{ route('admin.posts.index') }}" class="btn-admin inline-block text-center">Quản lý</a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition h-full flex flex-col">
                <div class="w-16 h-16 bg-orange-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <h5 class="text-lg font-semibold text-gray-900 mb-2">Báo cáo</h5>
                <p class="text-sm text-gray-500 mb-4 flex-grow">Xem báo cáo chi tiết về hoạt động của người dùng và hệ thống.</p>
                <a href="{{ route('admin.reports.user-reports') }}" class="btn-admin inline-block text-center">Xem báo cáo</a>
            </div>
        </div>
    </div>

    <!-- Sidebar Banners Section
    @if($sidebarBanners->count() > 0)
    <div class="mb-6">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Thông tin quan trọng</h2>
            <p class="text-gray-500">Các thông báo và sự kiện đặc biệt</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($sidebarBanners as $banner)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition h-full">
                @if($banner->image)
                    @if($banner->link)
                        <a href="{{ $banner->link }}" target="_blank" class="block">
                            <img src="{{ route('banner.image', basename($banner->image)) }}" 
                                 alt="{{ $banner->title }}" 
                                 class="w-full h-48 object-cover">
                        </a>
                    @else
                        <img src="{{ route('banner.image', basename($banner->image)) }}" 
                             alt="{{ $banner->title }}" 
                             class="w-full h-48 object-cover">
                    @endif
                @endif
                <div class="p-4 text-center">
                    <h5 class="font-semibold text-gray-900 mb-2">{{ $banner->title }}</h5>
                    @if($banner->description)
                        <p class="text-sm text-gray-500">{{ Str::limit($banner->description, 100) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif -->

    <!-- Latest Posts Section -->
    @if($latestPosts->count() > 0)
    <div class="mb-6">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Bài viết mới nhất</h2>
            <p class="text-gray-500">Những nội dung mới được cập nhật</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($latestPosts as $post)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" 
                     class="w-full h-48 object-cover" 
                     alt="{{ $post->title }}">
                @else
                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                </div>
                @endif
                <div class="p-4">
                    <h5 class="font-semibold text-gray-900 mb-2">{{ Str::limit($post->title, 50) }}</h5>
                    <p class="text-sm text-gray-500 mb-4">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                    <div class="flex justify-between items-center">
                        <small class="text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                        </small>
                        <a href="{{ route('user.posts.show', $post->id) }}" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">Đọc thêm</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('user.posts.home') }}" class="btn-admin">Xem tất cả bài viết</a>
        </div>
    </div>
    @endif

    <!-- Upcoming Schedules Section -->
    @if($upcomingSchedules->count() > 0)
    <div class="mb-6 bg-gray-50 rounded-lg p-6">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Lịch thu gom sắp tới</h2>
            <p class="text-gray-500">Các hoạt động thu gom rác thải được lên kế hoạch</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcomingSchedules as $schedule)
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500 hover:shadow-lg transition">
                <h5 class="font-semibold text-gray-900 mb-3">
                    <i class="fas fa-calendar-alt text-green-500 mr-2"></i>
                    {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d/m/Y') }}
                </h5>
                <div class="space-y-2 text-sm">
                    <p><strong class="text-gray-700">Khu vực:</strong> <span class="text-gray-600">{{ $schedule->area }}</span></p>
                    <p><strong class="text-gray-700">Thời gian:</strong> <span class="text-gray-600">{{ $schedule->time_slot }}</span></p>
                    <p><strong class="text-gray-700">Loại rác:</strong> <span class="text-gray-600">{{ $schedule->waste_type }}</span></p>
                </div>
                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                    <span class="badge badge-primary">{{ $schedule->status }}</span>
                    <small class="text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        {{ \Carbon\Carbon::parse($schedule->scheduled_date)->diffForHumans() }}
                    </small>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('admin.collection-schedules.index') }}" class="btn-admin">Quản lý lịch thu gom</a>
        </div>
    </div>
    @endif

    <!-- Footer Banners Section
    @if($footerBanners->count() > 0)
    <div class="mb-6 bg-gray-50 rounded-lg p-6">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Đối tác & Hỗ trợ</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($footerBanners as $banner)
            <div class="bg-white rounded-lg p-4 text-center hover:shadow-md transition">
                @if($banner->link)
                    <a href="{{ $banner->link }}" target="_blank" class="block">
                        <img src="{{ route('banner.image', basename($banner->image)) }}" 
                             alt="{{ $banner->title }}" 
                             class="w-full h-20 object-contain mx-auto">
                    </a>
                @else
                    <img src="{{ route('banner.image', basename($banner->image)) }}" 
                         alt="{{ $banner->title }}" 
                         class="w-full h-20 object-contain mx-auto">
                @endif
                @if($banner->title)
                    <p class="mt-2 text-xs text-gray-500">{{ $banner->title }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif -->

    <!-- Call to Action Section -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-8 text-white mb-6">
        <div class="flex flex-col lg:flex-row items-center justify-between">
            <div class="lg:w-2/3 mb-4 lg:mb-0">
                <h3 class="text-2xl font-bold mb-2">Sẵn sàng quản lý hệ thống?</h3>
                <p class="opacity-90">Truy cập dashboard để bắt đầu quản lý và theo dõi hoạt động của hệ thống.</p>
            </div>
            <div class="lg:w-1/3 text-center lg:text-right">
                <a href="{{ route('dashboard.admin') }}" class="px-6 py-3 bg-white text-green-600 font-semibold rounded-lg hover:bg-gray-100 transition shadow-md inline-block">
                    <i class="fas fa-tachometer-alt mr-2"></i>Truy cập Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('bannerCarousel');
    if (!carousel) return;

    let currentIndex = 0;
    const items = carousel.querySelectorAll('.carousel-item');
    const indicators = carousel.querySelectorAll('[data-carousel-target]');
    const totalItems = items.length;

    function showSlide(index) {
        items.forEach((item, i) => {
            if (i === index) {
                item.classList.remove('hidden');
                item.classList.add('active');
            } else {
                item.classList.add('hidden');
                item.classList.remove('active');
            }
        });

        indicators.forEach((indicator, i) => {
            if (i === index) {
                indicator.classList.remove('bg-opacity-50');
                indicator.classList.add('bg-white');
            } else {
                indicator.classList.add('bg-opacity-50');
                indicator.classList.remove('bg-white');
            }
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalItems;
        showSlide(currentIndex);
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        showSlide(currentIndex);
    }

    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentIndex = index;
            showSlide(currentIndex);
        });
    });

    // Auto slide every 5 seconds
    if (totalItems > 1) {
        setInterval(nextSlide, 5000);
    }
});
</script>
@endpush
@endsection
