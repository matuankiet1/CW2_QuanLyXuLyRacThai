@extends('layouts.user')

@section('title', 'Chi tiết thông báo')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <a href="{{ route('user.notifications.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h2 class="h4 mb-0">{{ $notification->title }}</h2>
                        @php
                            $badgeClass = [
                                'announcement' => 'text-bg-primary',
                                'academic' => 'text-bg-info',
                                'event' => 'text-bg-success',
                                'urgent' => 'text-bg-danger'
                            ][$notification->type] ?? 'text-bg-secondary';
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            @if($notification->type === 'announcement')
                                📢 Thông báo chung
                            @elseif($notification->type === 'academic')
                                📚 Học vụ
                            @elseif($notification->type === 'event')
                                🎉 Sự kiện
                            @elseif($notification->type === 'urgent')
                                ⚠️ Khẩn cấp
                            @endif
                        </span>
                    </div>

                    <div class="text-muted mb-4">
                        <i class="fas fa-user me-1"></i>Gửi bởi <strong>{{ $notification->sender->name }}</strong>
                        <span class="mx-2">•</span>
                        <i class="far fa-clock me-1"></i>{{ $notification->created_at->format('d/m/Y H:i') }}
                    </div>

                    <hr>

                    <div class="mb-4" style="white-space: pre-wrap; line-height: 1.8;">{{ $notification->content }}</div>

                    @if($notification->attachment)
                        <div class="alert alert-light border">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-paperclip text-primary"></i>
                                <strong>File đính kèm</strong>
                            </div>
                            <a href="{{ route('admin.notifications.download', $notification->notification_id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Tải xuống
                            </a>
                        </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('user.notifications.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>Danh sách thông báo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

