@extends('layouts.user')

@section('title', 'Thông báo của tôi')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">🔔 Thông báo của tôi</h1>
        @if($unreadCount > 0)
            <form action="{{ route('user.notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-success">
                    <i class="fas fa-check-double me-2"></i>Đánh dấu tất cả đã đọc
                </button>
            </form>
        @endif
    </div>

    @if($unreadCount > 0)
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>Bạn có <strong>{{ $unreadCount }}</strong> thông báo chưa đọc
        </div>
    @endif

    <div class="card shadow-sm border-0">
        @forelse($notifications as $notification)
            <div class="border-bottom">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            @php
                                $iconClass = [
                                    'announcement' => 'text-primary',
                                    'academic' => 'text-info',
                                    'event' => 'text-success',
                                    'urgent' => 'text-danger'
                                ][$notification->type] ?? 'text-secondary';
                            @endphp
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                <i class="fas fa-bell {{ $iconClass }}"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="mb-1 {{ !$notification->pivot->read_at ? 'fw-bold' : '' }}">
                                        <a href="{{ route('user.notifications.show', $notification->notification_id) }}" class="text-decoration-none text-dark">
                                            {{ $notification->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        Gửi bởi <strong>{{ $notification->sender->name }}</strong> • {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $iconClass }}">
                                        @if($notification->type === 'announcement')
                                            📢 Chung
                                        @elseif($notification->type === 'academic')
                                            📚 Học vụ
                                        @elseif($notification->type === 'event')
                                            🎉 Sự kiện
                                        @elseif($notification->type === 'urgent')
                                            ⚠️ Khẩn
                                        @endif
                                    </span>
                                    @if(!$notification->pivot->read_at)
                                        <div class="mt-1">
                                            <span class="badge bg-danger">Mới</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <p class="text-secondary mb-2">{{ Str::limit($notification->content, 120) }}</p>
                            @if($notification->attachment)
                                <div class="mb-2">
                                    <i class="fas fa-paperclip text-muted me-1"></i>
                                    <small class="text-muted">Có file đính kèm</small>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('user.notifications.show', $notification->notification_id) }}" class="btn btn-sm btn-outline-success">
                                    Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-body text-center py-5">
                <i class="fas fa-bell-slash text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">Chưa có thông báo nào</h5>
                <p class="text-muted">Bạn sẽ thấy thông báo ở đây khi có cập nhật mới.</p>
            </div>
        @endforelse
    </div>

    {{-- Phân trang --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

