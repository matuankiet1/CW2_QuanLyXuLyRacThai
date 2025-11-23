@extends('layouts.user')

@section('title', 'Bảng điều khiển Sinh viên')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body bg-success text-white rounded-3">
                    <h1 class="h3 mb-2">Chào mừng bạn trở lại!</h1>
                    <p class="mb-0 text-white-50">Xem sự kiện sắp diễn ra và theo dõi lịch sử tham gia của bạn.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Sự kiện sắp diễn ra</h2>
                    <a href="{{ route('user.events.index') }}" class="btn btn-outline-success btn-sm">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @forelse($upcomingEvents as $event)
                        <div class="border rounded-3 p-3 mb-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between">
                                <div>
                                    <small class="text-muted">{{ optional($event->event_start_date)->format('d/m/Y H:i') }}</small>
                                    <h3 class="h6 mt-1 mb-1">{{ $event->title }}</h3>
                                    <p class="mb-2 text-muted"><i class="fas fa-map-marker-alt me-2"></i>{{ $event->location }}</p>
                                </div>
                                <div class="d-flex flex-column align-items-md-end gap-2 mt-2 mt-md-0">
                                    <a href="{{ route('user.events.show', $event->id) }}" class="btn btn-sm btn-outline-secondary">
                                        Chi tiết sự kiện
                                    </a>
                                    <a href="{{ route('user.events.show', $event->id) }}" class="btn btn-sm btn-success">
                                        Đăng ký ngay
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted mb-0">Chưa có sự kiện nào mở đăng ký.</p>
                    @endforelse
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h5">Hướng dẫn nhanh</h3>
                    <ul class="mt-3 text-muted small">
                        <li>Đăng ký sự kiện và chờ quản lý xác nhận.</li>
                        <li>Đến địa điểm, xuất trình QR để check-in.</li>
                        <li>Theo dõi điểm và số giờ hoạt động sau khi sự kiện hoàn tất.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

