@extends('layouts.dashboard')

@section('main-content')
<div class="space-y-6">
    {{-- Thống kê --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl">📅</div>
                <div>
                    <p class="text-sm text-gray-500">Tổng sự kiện</p>
                    <h3 class="text-2xl">{{ \App\Models\Event::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-xl">👥</div>
                <div>
                    <p class="text-sm text-gray-500">Tổng người tham gia</p>
                    <h3 class="text-2xl">{{ \App\Models\Event::sum('participants') }}</h3>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl">🗑️</div>
            </div>
        </div>
    </div>

    {{-- Bộ lọc & thêm sự kiện --}}
    <div class="bg-white p-6 rounded-lg shadow">
        <form method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Tìm kiếm sự kiện..."
                    class="border p-2 rounded w-64" />
                <button class="bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded">Tìm</button>
                <select name="status" class="border p-2 rounded">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="completed">Đã kết thúc</option>
                        <option value="upcoming">Sắp diễn ra</option>
                    </select>
                    <button class="bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded">Lọc</button>
            </div>
            
            {{-- Nút tạo mới --}}
            <a href="{{ route('admin.events.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded">
                + Tạo sự kiện mới
            </a>
        </form>

        {{-- Bảng dữ liệu --}}
        <table class="min-w-full border-collapse border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border text-left">STT</th>
                    <th class="p-3 border text-left">Tên sự kiện</th>
                    <th class="p-3 border text-left">Ngày bắt đầu đăng ký</th>
                    <th class="p-3 border text-left">Ngày kết thúc đăng ký</th>
                    <th class="p-3 border text-left">Ngày bắt đầu sự kiện</th>
                    <th class="p-3 border text-left">Ngày kết thúc sự kiện</th>
                    <th class="p-3 border text-left">Địa điểm</th>
                    <th class="p-3 border text-left">Người tham gia</th>
                    <th class="p-3 border text-left">Trạng thái</th>
                    <th class="p-3 border text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $index => $event)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 text-center">{{ $events->firstItem() + $index }}</td>
                        <td class="p-3">{{ $event->title }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($event->register_date)->format('d/m/Y') }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($event->register_end_date)->format('d/m/Y') }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($event->event_start_date)->format('d/m/Y') }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($event->event_start_date)->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $event->location }}</td>
                        <td class="p-3">{{ $event->participants }} người</td>
                        <td class="p-3">
                            @if ($event->status === 'completed')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Đã kết thúc</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Sắp diễn ra</span>
                            @endif
                        </td>
                        <td class="p-3 text-right flex justify-end gap-2">
                            <a href="{{ route('admin.events.edit', $event) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">✏️</a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                onsubmit="return confirm('Xóa sự kiện này?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500">Không có sự kiện nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="mt-4">{{ $events->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
