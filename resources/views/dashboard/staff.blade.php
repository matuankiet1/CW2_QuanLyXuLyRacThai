@extends('layouts.admin-with-sidebar')

@section('title', 'Bảng điều khiển Nhân viên')

@section('content')
<div class="p-6 space-y-6">
    <div class="bg-linear-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6">
        <h1 class="text-3xl font-semibold mb-2">Xin chào Nhân viên</h1>
        <p class="text-blue-100">Theo dõi nhiệm vụ được giao, thực hiện check-in và cập nhật tiến độ thu gom.</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Sự kiện được phân công</h2>
            <a href="{{ route('staff.collection-reports.index') }}" class="text-sm text-blue-600 hover:underline">Báo cáo thu gom</a>
        </div>
        <div class="p-6 space-y-4">
            @forelse($upcomingEvents as $event)
                <div class="border rounded-lg p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-sm text-gray-500">{{ optional($event->event_start_date)->format('d/m/Y H:i') }}</p>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                            <p class="text-gray-600 text-sm">{{ $event->location }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('user.events.index') }}" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium">Xem chi tiết</a>
                            <a href="{{ route('waste-logs.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium">Ghi nhận số liệu</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-6">Bạn chưa được phân công sự kiện nào.</p>
            @endforelse
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Tác vụ nhanh</h3>
            <ul class="space-y-3 text-sm text-gray-700">
                <li>• Check-in sự kiện được giao</li>
                <li>• Điểm danh sinh viên bằng QR</li>
                <li>• Cập nhật khối lượng rác và hình ảnh</li>
                <li>• Gửi báo cáo cuối sự kiện</li>
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Hỗ trợ</h3>
            <p class="text-gray-600 text-sm">Liên hệ Quản lý nếu bạn cần được phân công hoặc có sự cố trong quá trình thu gom.</p>
        </div>
    </div>
</div>
@endsection

