@extends('layouts.admin-with-sidebar')

@section('title', 'Chi tiết thông báo - Admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-start mb-4">
        <div>
            <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition mb-2">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
            <h1 class="text-2xl font-semibold mb-1">{{ $notification->title }}</h1>
            <p class="text-gray-500">
                Gửi bởi <strong>{{ $notification->sender->name }}</strong> • {{ $notification->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
        <div>
            @if($notification->attachment)
                <a href="{{ route('admin.notifications.download', $notification->notification_id) }}" class="px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition">
                    <i class="fas fa-download mr-2"></i>Tải file đính kèm
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6">
                    <div class="mb-4 flex gap-2">
                        @php
                            $badgeClass = [
                                'announcement' => 'bg-blue-500 text-white',
                                'academic' => 'bg-blue-400 text-white',
                                'event' => 'bg-green-500 text-white',
                                'urgent' => 'bg-red-500 text-white'
                            ][$notification->type] ?? 'bg-gray-500 text-white';
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($notification->type) }}</span>
                        <span class="badge {{ $notification->status === 'sent' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                            {{ $notification->status === 'sent' ? 'Đã gửi' : 'Đã hẹn' }}
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="text-lg font-semibold mb-2">Nội dung:</h5>
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 whitespace-pre-wrap">{{ $notification->content }}</div>
                    </div>

                    @if($notification->scheduled_at)
                        <div class="alert alert-info">
                            <i class="fas fa-clock mr-2"></i>Thông báo đã được hẹn gửi vào: <strong>{{ $notification->scheduled_at->format('d/m/Y H:i') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6">
                    <h5 class="text-lg font-semibold mb-4">Thống kê</h5>
                    <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
                        <span class="text-gray-500">Tổng người nhận:</span>
                        <strong class="text-gray-900">{{ $stats['total_recipients'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
                        <span class="text-green-600">Đã đọc:</span>
                        <strong class="text-gray-900">{{ $stats['read_count'] }}</strong>
                    </div>
                    <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
                        <span class="text-gray-500">Chưa đọc:</span>
                        <strong class="text-gray-900">{{ $stats['unread_count'] }}</strong>
                    </div>
                    <div class="mb-2">
                        <div class="flex justify-between mb-1">
                            <small class="text-gray-500">Tỷ lệ đọc</small>
                            <small class="font-semibold text-gray-900">{{ $stats['read_percentage'] }}%</small>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['read_percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md mt-4">
                <div class="p-6">
                    <h5 class="text-lg font-semibold mb-4">Thông tin thông báo</h5>
                    <div class="text-sm">
                        <div class="mb-3">
                            <span class="text-gray-500 block mb-1">Loại gửi:</span>
                            <strong class="text-gray-900">
                                @if($notification->send_to_type === 'all')
                                    Tất cả sinh viên
                                @elseif($notification->send_to_type === 'role')
                                    Theo vai trò: {{ $notification->target_role }}
                                @else
                                    Sinh viên cụ thể
                                @endif
                            </strong>
                        </div>
                        <div class="mb-3">
                            <span class="text-gray-500 block mb-1">Thời gian tạo:</span>
                            <strong class="text-gray-900">{{ $notification->created_at->format('d/m/Y H:i:s') }}</strong>
                        </div>
                        @if($notification->sent_at)
                            <div class="mb-3">
                                <span class="text-gray-500 block mb-1">Thời gian gửi:</span>
                                <strong class="text-gray-900">{{ $notification->sent_at->format('d/m/Y H:i:s') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($notification->recipients->count() > 0)
        <div class="bg-white rounded-lg shadow-md mt-4">
            <div class="p-6 border-b border-gray-200">
                <h5 class="text-lg font-semibold mb-0">Danh sách người nhận ({{ $notification->recipients->count() }})</h5>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="table w-full border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Tên</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Email</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Trạng thái</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Thời gian đọc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notification->recipients as $recipient)
                                <tr class="hover:bg-gray-50 border-b border-gray-200">
                                    <td class="px-4 py-3 text-gray-900">{{ $recipient->name }}</td>
                                    <td class="px-4 py-3 text-gray-900">{{ $recipient->email }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($recipient->pivot->read_at)
                                            <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Đã đọc</span>
                                        @else
                                            <span class="badge badge-info"><i class="fas fa-clock mr-1"></i>Chưa đọc</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-500 text-sm">
                                        @if($recipient->pivot->read_at)
                                            {{ $recipient->pivot->read_at->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
