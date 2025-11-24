@extends('layouts.admin-with-sidebar')

@section('title', 'Bảng điều khiển Quản lý')

@section('content')
<div class="p-6 space-y-6">
    <div class="bg-linear-to-r from-emerald-500 to-green-600 text-white rounded-xl shadow-lg p-6">
        <h1 class="text-3xl font-semibold mb-2">Chào mừng Quản lý</h1>
        <p class="text-emerald-100">Theo dõi sự kiện, phê duyệt đăng ký và nhận báo cáo từ nhân viên.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-emerald-500">
            <p class="text-sm text-gray-500 mb-1">Sự kiện sắp diễn ra</p>
            <h3 class="text-2xl font-semibold text-gray-900">{{ $upcomingEvents->count() }}</h3>
        </div>
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500 mb-1">Đăng ký cần duyệt</p>
            <h3 class="text-2xl font-semibold text-gray-900">—</h3>
            <p class="text-xs text-gray-400">Tính năng duyệt sẽ hiển thị tại đây.</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500 mb-1">Báo cáo chờ xác nhận</p>
            <h3 class="text-2xl font-semibold text-gray-900">—</h3>
            <p class="text-xs text-gray-400">Đang chờ dữ liệu từ nhân viên.</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Sự kiện nổi bật</h2>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-emerald-600 hover:underline">Quản lý sự kiện</a>
        </div>
        <div class="p-6">
            @forelse($upcomingEvents as $event)
                <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-gray-100 py-4 last:border-0">
                    <div>
                        <p class="text-sm text-gray-500">{{ optional($event->event_start_date)->format('d/m/Y H:i') }}</p>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                        <p class="text-gray-600 text-sm">{{ $event->location }}</p>
                    </div>
                    <div class="mt-3 md:mt-0 flex gap-2">
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium">Chỉnh sửa</a>
                        <a href="{{ route('admin.events.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium">Chi tiết</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-6">Hiện chưa có sự kiện sắp diễn ra.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

