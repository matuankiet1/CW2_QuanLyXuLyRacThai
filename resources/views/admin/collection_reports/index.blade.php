@extends('layouts.admin-with-sidebar')

@section('title', 'Báo cáo thu gom - Quản lý')

@section('content')
<div class="p-6 space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Báo cáo thu gom của nhân viên</h1>
                <p class="text-gray-500 text-sm">Xem và xác nhận các báo cáo đã gửi</p>
            </div>
            <form method="GET" class="flex gap-2">
                <select name="status" class="border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
                </select>
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg">Lọc</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="text-left text-sm text-gray-500 border-b">
                        <th class="px-4 py-3">Lịch</th>
                        <th class="px-4 py-3">Nhân viên</th>
                        <th class="px-4 py-3">Khối lượng (kg)</th>
                        <th class="px-4 py-3">Ghi chú</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y">
                    @forelse($reports as $report)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium">#{{ $report->schedule?->schedule_id }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $report->schedule?->scheduled_date?->format('d/m/Y') ?? '-' }}
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $report->schedule?->staff?->name }}</p>
                                <p class="text-xs text-gray-500">{{ $report->schedule?->staff?->email }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-700 space-y-1">
                                    <p><strong>Tổng:</strong> {{ number_format($report->total_weight, 2) }}</p>
                                    <p class="text-xs text-gray-500">
                                        Hữu cơ: {{ $report->organic_weight ?? 0 }},
                                        Tái chế: {{ $report->recyclable_weight ?? 0 }},
                                        Nguy hại: {{ $report->hazardous_weight ?? 0 }},
                                        Khác: {{ $report->other_weight ?? 0 }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($report->notes)
                                    <p class="text-gray-700">{{ $report->notes }}</p>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                                @if($report->photo_path)
                                    <a href="{{ asset('storage/' . $report->photo_path) }}" target="_blank" class="block text-xs text-green-600 mt-2 hover:underline">
                                        Xem ảnh
                                    </a>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if($report->status === 'approved') bg-green-100 text-green-700
                                @elseif($report->status === 'rejected') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ ucfirst($report->status) }}
                                </span>
                                @if($report->approved_at && $report->approvedBy)
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $report->approvedBy->name }} &middot; {{ $report->approved_at->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($report->status === 'pending')
                                    <form action="{{ route('manager.collection-reports.approve', $report->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit"
                                            class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                                            Duyệt
                                        </button>
                                    </form>
                                    <form action="{{ route('manager.collection-reports.approve', $report->id) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit"
                                            class="px-3 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                                            Từ chối
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-500">Không có hành động</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Chưa có báo cáo nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $reports->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

