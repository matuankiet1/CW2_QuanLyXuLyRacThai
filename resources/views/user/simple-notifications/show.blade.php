@extends('layouts.user')

@section('title', $notification->title)

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('user.simple-notifications.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
        </a>
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

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3 mb-4">
                <div class="flex-shrink-0">
                    <div class="rounded-circle {{ $notification->is_read ? 'bg-secondary' : 'bg-primary' }} text-white d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                        <i class="fas fa-bell fa-lg"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h2 class="mb-2">{{ $notification->title }}</h2>
                            <div class="d-flex align-items-center gap-3 text-muted">
                                <span>
                                    <i class="fas fa-clock me-1"></i>{{ $notification->created_at->format('d/m/Y H:i:s') }}
                                </span>
                                <span>
                                    <i class="fas fa-info-circle me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div>
                            @if($notification->is_read)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check me-1"></i>Đã đọc
                                </span>
                            @else
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-circle me-1"></i>Chưa đọc
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="mb-4">
                <h5 class="mb-3">
                    <i class="fas fa-file-text me-2"></i>Nội dung:
                </h5>
                <div class="bg-light p-4 rounded border-start border-4 border-primary">
                    <p class="mb-0" style="white-space: pre-wrap; line-height: 1.8;">{{ $notification->message }}</p>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <div>
                    <a href="{{ route('user.simple-notifications.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
                <div>
                    @if(!$notification->is_read)
                        <form action="{{ route('user.simple-notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Đánh dấu đã đọc
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
