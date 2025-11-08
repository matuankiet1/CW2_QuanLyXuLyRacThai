@extends('layouts.admin-with-sidebar')

@section('title', 'Chi tiết thông báo - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <h1 class="h3 mb-1">{{ $notification->title }}</h1>
            <p class="text-muted">
                Gửi bởi <strong>{{ $notification->sender->name }}</strong> • {{ $notification->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
        <div>
            @if($notification->attachment)
                <a href="{{ route('admin.notifications.download', $notification->notification_id) }}" class="btn btn-info">
                    <i class="fas fa-download me-2"></i>Tải file đính kèm
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-soft">
                <div class="card-body">
                    <div class="mb-3">
                        @php
                            $badgeClass = [
                                'announcement' => 'text-bg-primary',
                                'academic' => 'text-bg-info',
                                'event' => 'text-bg-success',
                                'urgent' => 'text-bg-danger'
                            ][$notification->type] ?? 'text-bg-secondary';
                        @endphp
                        <span class="badge {{ $badgeClass }} mb-2">{{ ucfirst($notification->type) }}</span>
                        <span class="badge {{ $notification->status === 'sent' ? 'text-bg-success' : 'text-bg-warning' }}">
                            {{ $notification->status === 'sent' ? 'Đã gửi' : 'Đã hẹn' }}
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Nội dung:</h5>
                        <div class="border rounded p-3 bg-light" style="white-space: pre-wrap;">{{ $notification->content }}</div>
                    </div>

                    @if($notification->scheduled_at)
                        <div class="alert alert-info">
                            <i class="fas fa-clock me-2"></i>Thông báo đã được hẹn gửi vào: <strong>{{ $notification->scheduled_at->format('d/m/Y H:i') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-soft">
                <div class="card-body">
                    <h5 class="mb-3">Thống kê</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted">Tổng người nhận:</span>
                        <strong>{{ $stats['total_recipients'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-success">Đã đọc:</span>
                        <strong>{{ $stats['read_count'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted">Chưa đọc:</span>
                        <strong>{{ $stats['unread_count'] }}</strong>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Tỷ lệ đọc</small>
                            <small class="fw-bold">{{ $stats['read_percentage'] }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['read_percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-soft mt-3">
                <div class="card-body">
                    <h5 class="mb-3">Thông tin thông báo</h5>
                    <div class="small">
                        <div class="mb-2">
                            <span class="text-muted">Loại gửi:</span><br>
                            <strong>
                                @if($notification->send_to_type === 'all')
                                    Tất cả sinh viên
                                @elseif($notification->send_to_type === 'role')
                                    Theo vai trò: {{ $notification->target_role }}
                                @else
                                    Sinh viên cụ thể
                                @endif
                            </strong>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Thời gian tạo:</span><br>
                            <strong>{{ $notification->created_at->format('d/m/Y H:i:s') }}</strong>
                        </div>
                        @if($notification->sent_at)
                            <div class="mb-2">
                                <span class="text-muted">Thời gian gửi:</span><br>
                                <strong>{{ $notification->sent_at->format('d/m/Y H:i:s') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($notification->recipients->count() > 0)
        <div class="card shadow-soft mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Danh sách người nhận ({{ $notification->recipients->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Email</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thời gian đọc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notification->recipients as $recipient)
                                <tr>
                                    <td>{{ $recipient->name }}</td>
                                    <td>{{ $recipient->email }}</td>
                                    <td class="text-center">
                                        @if($recipient->pivot->read_at)
                                            <span class="badge text-bg-success"><i class="fas fa-check me-1"></i>Đã đọc</span>
                                        @else
                                            <span class="badge text-bg-secondary"><i class="fas fa-clock me-1"></i>Chưa đọc</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($recipient->pivot->read_at)
                                            <small class="text-muted">{{ $recipient->pivot->read_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
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

