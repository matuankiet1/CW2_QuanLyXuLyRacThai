@extends('layouts.user')

@section('title', 'Thông báo của tôi')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">🔔 Thông báo của tôi</h1>
            <p class="text-muted mb-0">Quản lý và xem tất cả thông báo của bạn</p>
        </div>
        @if($unreadCount > 0)
            <form action="{{ route('user.simple-notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-success" onclick="return confirm('Bạn có chắc chắn muốn đánh dấu tất cả thông báo là đã đọc?');">
                    <i class="fas fa-check-double me-2"></i>Đánh dấu tất cả đã đọc
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Tổng thông báo</div>
                            <div class="h4 mb-0">{{ $stats['total'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Chưa đọc</div>
                            <div class="h4 mb-0">{{ $stats['unread'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Đã đọc</div>
                            <div class="h4 mb-0">{{ $stats['read'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('user.simple-notifications.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm theo tiêu đề hoặc nội dung...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Đã đọc</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sắp xếp</label>
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="title" {{ request('sort_by') === 'title' ? 'selected' : '' }}>Tiêu đề</option>
                        <option value="is_read" {{ request('sort_by') === 'is_read' ? 'selected' : '' }}>Trạng thái</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Lọc
                        </button>
                        <a href="{{ route('user.simple-notifications.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card shadow-sm border-0">
        @forelse($notifications as $notification)
            <div class="border-bottom {{ !$notification->is_read ? 'bg-light' : '' }} notification-item" data-notification-id="{{ $notification->id }}">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle {{ $notification->is_read ? 'bg-secondary' : 'bg-primary' }} text-white d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 {{ !$notification->is_read ? 'fw-bold' : '' }}">
                                        <a href="{{ route('user.simple-notifications.show', $notification->id) }}" class="text-decoration-none text-dark">
                                            {{ $notification->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-clock me-1"></i>{{ $notification->created_at->format('d/m/Y H:i') }}
                                        <span class="ms-2">({{ $notification->created_at->diffForHumans() }})</span>
                                    </p>
                                    <p class="mb-0 text-muted">{{ Str::limit($notification->message, 200) }}</p>
                                </div>
                                <div class="text-end ms-3">
                                    @if(!$notification->is_read)
                                        <span class="badge bg-danger mb-2 d-block">Mới</span>
                                    @endif
                                    @if(!$notification->is_read)
                                        <form action="{{ route('user.simple-notifications.mark-read', $notification->id) }}" method="POST" class="d-inline mark-read-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary mark-read-btn" data-notification-id="{{ $notification->id }}">
                                                <i class="fas fa-check me-1"></i>Đánh dấu đã đọc
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Đã đọc
                                        </span>
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
                <h4 class="text-muted mb-2">Không tìm thấy thông báo nào</h4>
                <p class="text-muted">
                    @if(request()->has('search') || request()->has('status'))
                        Không có thông báo nào khớp với bộ lọc của bạn.
                        <a href="{{ route('user.simple-notifications.index') }}" class="text-primary">Xem tất cả thông báo</a>
                    @else
                        Bạn sẽ nhận được thông báo ở đây khi có sự kiện mới.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // AJAX mark as read
    const markReadForms = document.querySelectorAll('.mark-read-form');
    
    markReadForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const btn = form.querySelector('.mark-read-btn');
            const notificationId = btn.dataset.notificationId;
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            
            // Disable button
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';
            
            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    if (notificationItem) {
                        notificationItem.classList.remove('bg-light');
                        notificationItem.querySelector('.bg-primary')?.classList.replace('bg-primary', 'bg-secondary');
                        notificationItem.querySelector('.fw-bold')?.classList.remove('fw-bold');
                        notificationItem.querySelector('.bg-danger')?.remove();
                        
                        // Replace button with badge
                        const btnContainer = notificationItem.querySelector('.text-end');
                        if (btnContainer) {
                            btnContainer.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Đã đọc</span>';
                        }
                    }
                    
                    // Show success message
                    showToast('success', data.message || 'Đã đánh dấu thông báo là đã đọc.');
                } else {
                    showToast('error', data.message || 'Có lỗi xảy ra.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check me-1"></i>Đánh dấu đã đọc';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Có lỗi xảy ra khi đánh dấu thông báo.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Đánh dấu đã đọc';
            });
        });
    });
});

function showToast(type, message) {
    // Simple toast notification (you can enhance this)
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alert.style.zIndex = '9999';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
</script>
@endpush
@endsection
