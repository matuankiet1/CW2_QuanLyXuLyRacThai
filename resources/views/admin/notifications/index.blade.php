@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý thông báo - Admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-2xl font-semibold mb-1">Quản lý thông báo</h1>
            <p class="text-gray-500">Gửi và quản lý thông báo đến người dùng</p>
        </div>
        <a href="{{ route('admin.notifications.create') }}" class="btn-admin">
            <i class="fas fa-plus mr-2"></i>Gửi thông báo mới
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0" style="width:48px;height:48px;">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Tổng thông báo</div>
                    <div class="text-2xl font-semibold mb-0">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0" style="width:48px;height:48px;">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Đã gửi</div>
                    <div class="text-2xl font-semibold mb-0">{{ $stats['sent'] }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center flex-shrink-0" style="width:48px;height:48px;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Đã hẹn giờ</div>
                    <div class="text-2xl font-semibold mb-0">{{ $stats['scheduled'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Tiêu đề</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Người gửi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Loại</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Người nhận</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Đã đọc</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Thời gian</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr class="hover:bg-gray-50 border-b border-gray-200">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ Str::limit($notification->title, 50) }}</div>
                                @if($notification->attachment)
                                    <small class="text-gray-500"><i class="fas fa-paperclip mr-1"></i> Có đính kèm</small>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-900">{{ $notification->sender->name }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badgeClass = [
                                        'announcement' => 'bg-blue-500 text-white',
                                        'academic' => 'bg-blue-400 text-white',
                                        'event' => 'bg-green-500 text-white',
                                        'urgent' => 'bg-red-500 text-white'
                                    ][$notification->type] ?? 'bg-gray-500 text-white';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($notification->type) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-900">{{ $notification->total_recipients }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-green-600 font-medium">{{ $notification->read_count }}</span>
                                <span class="text-gray-400">/</span>
                                <span class="text-gray-500">{{ $notification->total_recipients }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($notification->status === 'sent')
                                    <span class="badge badge-success">Đã gửi</span>
                                @elseif($notification->status === 'scheduled')
                                    <span class="badge badge-warning">Đã hẹn</span>
                                @else
                                    <span class="badge badge-info">Nháp</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-sm">{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.notifications.show', $notification->notification_id) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($notification->status !== 'sent')
                                        <form action="{{ route('admin.notifications.destroy', $notification->notification_id) }}" method="POST" class="inline" onsubmit="return confirm('Xóa thông báo này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">Chưa có thông báo nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
