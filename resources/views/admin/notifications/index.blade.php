@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý thông báo - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Quản lý thông báo</h1>
            <p class="text-muted">Gửi và quản lý thông báo đến người dùng</p>
        </div>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-admin">
            <i class="fas fa-plus me-2"></i>Gửi thông báo mới
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Tổng thông báo</div>
                        <div class="h4 mb-0">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Đã gửi</div>
                        <div class="h4 mb-0">{{ $stats['sent'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Đã hẹn giờ</div>
                        <div class="h4 mb-0">{{ $stats['scheduled'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-soft">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Người gửi</th>
                        <th>Loại</th>
                        <th>Người nhận</th>
                        <th>Đã đọc</th>
                        <th>Trạng thái</th>
                        <th>Thời gian</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ Str::limit($notification->title, 50) }}</div>
                                @if($notification->attachment)
                                    <small class="text-muted"><i class="fas fa-paperclip"></i> Có đính kèm</small>
                                @endif
                            </td>
                            <td>{{ $notification->sender->name }}</td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'announcement' => 'text-bg-primary',
                                        'academic' => 'text-bg-info',
                                        'event' => 'text-bg-success',
                                        'urgent' => 'text-bg-danger'
                                    ][$notification->type] ?? 'text-bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($notification->type) }}</span>
                            </td>
                            <td class="text-center">{{ $notification->total_recipients }}</td>
                            <td class="text-center">
                                <span class="text-success">{{ $notification->read_count }}</span>
                                <span class="text-muted">/</span>
                                <span class="text-muted">{{ $notification->total_recipients }}</span>
                            </td>
                            <td>
                                @if($notification->status === 'sent')
                                    <span class="badge text-bg-success">Đã gửi</span>
                                @elseif($notification->status === 'scheduled')
                                    <span class="badge text-bg-warning">Đã hẹn</span>
                                @else
                                    <span class="badge text-bg-secondary">Nháp</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.notifications.show', $notification->notification_id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($notification->status !== 'sent')
                                    <form action="{{ route('admin.notifications.destroy', $notification->notification_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa thông báo này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Chưa có thông báo nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection

