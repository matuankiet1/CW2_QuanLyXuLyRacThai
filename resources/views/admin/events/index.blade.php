@extends('layouts.dashboard')

@section('main-content')
<div class="space-y-6">
    {{-- Thống kê --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl">
                    📅
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng sự kiện</p>
                    <h3 class="text-2xl">{{ \App\Models\Event::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-xl">
                    👥
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng người tham gia</p>
                    <h3 class="text-2xl">{{ \App\Models\Event::sum('participants') }}</h3>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl">
                    🗑️
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rác thu gom</p>
                    <h3 class="text-2xl">{{ \App\Models\Event::sum('waste') }} kg</h3>
                </div>
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
            </div>

            {{-- Nút tạo mới --}}
            <button type="button" onclick="document.getElementById('createModal').showModal()"
                class="bg-blue-600 text-white px-3 py-2 rounded">
                + Tạo sự kiện mới
            </button>
        </form>

        {{-- Bảng dữ liệu --}}
        <table class="min-w-full border-collapse border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border text-left">Tên sự kiện</th>
                    <th class="p-3 border text-left">Ngày</th>
                    <th class="p-3 border text-left">Địa điểm</th>
                    <th class="p-3 border text-left">Người tham gia</th>
                    <th class="p-3 border text-left">Rác thu gom</th>
                    <th class="p-3 border text-left">Trạng thái</th>
                    <th class="p-3 border text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $event->title }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $event->location }}</td>
                        <td class="p-3">{{ $event->participants }} người</td>
                        <td class="p-3">{{ $event->waste > 0 ? $event->waste . ' kg' : '-' }}</td>
                        <td class="p-3">
                            @if ($event->status === 'completed')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Đã hoàn thành</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Sắp diễn ra</span>
                            @endif
                        </td>
                        <td class="p-3 text-right flex justify-end gap-2">
                            {{-- Sửa --}}
                            <button onclick="editEvent({{ $event }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">✏️</button>
                            {{-- Xóa --}}
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
                        <td colspan="7" class="text-center py-6 text-gray-500">Không có sự kiện nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Phân trang --}}
        <div class="mt-4">{{ $events->withQueryString()->links() }}</div>
    </div>
</div>

{{-- Modal thêm sự kiện --}}
<dialog id="createModal" class="rounded-md p-6 w-96">
    <form method="POST" action="{{ route('admin.events.store') }}">
        @csrf
        <h2 class="text-lg font-semibold mb-4">Tạo sự kiện mới</h2>

        <div class="mb-3">
            <label class="block mb-1 font-medium">Tên sự kiện</label>
            <input type="text" name="title" required class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-medium">Ngày tổ chức</label>
            <input type="date" name="date" required class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-medium">Địa điểm</label>
            <input type="text" name="location" required class="w-full border p-2 rounded">
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-medium">Trạng thái</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="upcoming">Sắp diễn ra</option>
                <option value="completed">Đã hoàn thành</option>
            </select>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" onclick="document.getElementById('createModal').close()" class="bg-gray-200 px-3 py-2 rounded">Hủy</button>
            <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded">Tạo</button>
        </div>
    </form>
</dialog>
@endsection
