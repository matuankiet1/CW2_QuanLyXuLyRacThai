@extends('layouts.admin-with-sidebar')

@section('content')
<div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded bg-green-100 text-green-600 flex items-center justify-center" style="width:48px;height:48px;">📅</div>
                <div>
                    <div class="text-gray-500 text-sm">Tổng sự kiện</div>
                    <div class="text-2xl font-semibold mb-0">{{ \App\Models\Event::count() }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded bg-blue-100 text-blue-600 flex items-center justify-center" style="width:48px;height:48px;">👥</div>
                <div>
                    <div class="text-gray-500 text-sm">Tổng người tham gia</div>
                    <div class="text-2xl font-semibold mb-0">{{ \App\Models\Event::sum('participants') }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded bg-blue-100 text-blue-600 flex items-center justify-center" style="width:48px;height:48px;">🗑️</div>
                <div>
                    <div class="text-gray-500 text-sm">Sự kiện môi trường</div>
                    <div class="text-lg font-semibold mb-0">Theo dõi trong tháng</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md mb-4">
        <div class="p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium mb-1">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ $search }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Tìm sự kiện...">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1">Trạng thái</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="completed">Đã kết thúc</option>
                        <option value="upcoming">Sắp diễn ra</option>
                    </select>
                </div>
                <div class="md:col-span-5 flex flex-col md:flex-row gap-2 justify-end">
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Lọc</button>
                    <a href="{{ route('admin.events.create') }}" class="btn btn-admin">+ Tạo sự kiện mới</a>
                </div>
            </form>
        </div>
            </div>

    <div class="bg-white rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse table-auto">
                <thead>
                    <tr class="border-b">
                        <th class="px-4 py-3 text-left font-semibold" style="width:80px">STT</th>
                        <th class="px-4 py-3 text-left font-semibold">Tên sự kiện</th>
                        <th class="px-4 py-3 text-left font-semibold">Ngày bắt đầu đăng ký</th>
                        <th class="px-4 py-3 text-left font-semibold">Ngày kết thúc đăng ký</th>
                        <th class="px-4 py-3 text-left font-semibold">Ngày bắt đầu sự kiện</th>
                        <th class="px-4 py-3 text-left font-semibold">Ngày kết thúc sự kiện</th>
                        <th class="px-4 py-3 text-left font-semibold">Địa điểm</th>
                        <th class="px-4 py-3 text-left font-semibold">Người tham gia</th>
                        <th class="px-4 py-3 text-left font-semibold">Trạng thái</th>
                        <th class="px-4 py-3 text-right font-semibold">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $index => $event)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-center">{{ $events->firstItem() + $index }}</td>
                            <td class="px-4 py-3">{{ $event->title }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($event->register_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($event->register_end_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($event->event_start_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($event->event_start_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $event->location }}</td>
                            <td class="px-4 py-3">{{ $event->participants }} người</td>
                            <td class="px-4 py-3">
                            @if ($event->status === 'completed')
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-green-500 text-white">Đã kết thúc</span>
                            @else
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-gray-500 text-white">Sắp diễn ra</span>
                            @endif
                        </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex gap-2 text-sm justify-end">
                                    <a href="{{ route('admin.events.rewards.index', $event->id) }}" class="px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600" title="Xem điểm thưởng">
                                        <i class="fas fa-trophy mr-1"></i>Điểm thưởng
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}" class="px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Sửa</a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Xóa sự kiện này?');" class="inline">
                                @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">Xóa</button>
                            </form>
                                </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                            <td colspan="10" class="text-center text-gray-500 py-4">Không có sự kiện nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="border-t pt-4 bg-white px-4 pb-4">{{ $events->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
