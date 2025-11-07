@extends('layouts.user')

@section('title', 'Thông báo của tôi')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">🔔 Thông báo của tôi</h1>
        @if($unreadCount > 0)
            <form action="{{ route('user.simple-notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-success">
                    <i class="fas fa-check-double me-2"></i>Đánh dấu tất cả đã đọc
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($unreadCount > 0)
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>Bạn có <strong>{{ $unreadCount }}</strong> thông báo chưa đọc
        </div>
    @endif

    <div class="card shadow-sm border-0">
        @forelse($notifications as $notification)
            <div class="border-bottom {{ !$notification->is_read ? 'bg-light' : '' }}">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="mb-1 {{ !$notification->is_read ? 'fw-bold' : '' }}">
                                        <a href="{{ route('user.simple-notifications.show', $notification->id) }}" class="text-decoration-none text-dark">
                                            {{ $notification->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                    <p class="mb-0">{{ Str::limit($notification->message, 150) }}</p>
                                </div>
                                <div class="text-end">
                                    @if(!$notification->is_read)
                                        <span class="badge bg-danger mb-2">Mới</span>
                                    @endif
                                    @if(!$notification->is_read)
                                        <form action="{{ route('user.simple-notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-check me-1"></i>Đánh dấu đã đọc
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-body text-center py-5">
                <i class="fas fa-bell-slash text-muted mb-3" style="font-size: 4rem;"></i>
                <h4 class="text-muted mb-2">Chưa có thông báo nào</h4>
                <p class="text-muted">Bạn sẽ nhận được thông báo ở đây khi có sự kiện mới.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

