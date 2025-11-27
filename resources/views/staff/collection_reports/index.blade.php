@extends('layouts.admin-with-sidebar')

@section('title', 'Báo cáo thu gom của tôi')

@section('content')
<div class="p-6 space-y-6">
    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Lịch thu gom được phân công</h1>
                <p class="text-gray-500 text-sm">Gửi báo cáo sau khi hoàn thành công việc</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="text-left text-sm text-gray-500 border-b">
                        <th class="px-4 py-2">Ngày thu gom</th>
                        <th class="px-4 py-2">Trạng thái lịch</th>
                        <th class="px-4 py-2">Trạng thái báo cáo</th>
                        <th class="px-4 py-2 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y">
                    @forelse($schedules as $schedule)
                        @php
                            $report = $schedule->report;
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium">
                                    {{ $schedule->scheduled_date?->format('d/m/Y') ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Mã lịch: #{{ $schedule->schedule_id }}
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $schedule->status === 'Đã hoàn thành' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $schedule->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if(!$report)
                                    <span class="text-gray-500 text-sm">Chưa gửi</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($report->status === 'approved') bg-green-100 text-green-700
                                        @elseif($report->status === 'rejected') bg-red-100 text-red-700
                                        @else bg-blue-100 text-blue-700 @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                    @if($report->approved_at)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Duyệt ngày {{ $report->approved_at->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('staff.collection-reports.create', $schedule->schedule_id) }}"
                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium
                                    {{ $report ? 'border border-gray-300 text-gray-700 hover:bg-gray-50' : 'bg-green-600 text-white hover:bg-green-700' }}">
                                    {{ $report ? 'Xem / Cập nhật' : 'Báo cáo' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                Hiện chưa có lịch thu gom nào được phân công.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $schedules->links() }}
        </div>
    </div>
</div>
@endsection

