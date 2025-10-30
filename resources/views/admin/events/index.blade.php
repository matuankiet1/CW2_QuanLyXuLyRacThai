@extends('layouts.admin-with-sidebar')

@section('content')
<div class="container-fluid">
    <div class="row g-4 mb-1">
        <div class="col-12 col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px;">📅</div>
                    <div>
                        <div class="text-muted small">Tổng sự kiện</div>
                        <div class="h4 mb-0">{{ \App\Models\Event::count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;">👥</div>
                    <div>
                        <div class="text-muted small">Tổng người tham gia</div>
                        <div class="h4 mb-0">{{ \App\Models\Event::sum('participants') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:48px;height:48px;">🗑️</div>
                    <div>
                        <div class="text-muted small">Sự kiện môi trường</div>
                        <div class="h6 mb-0">Theo dõi trong tháng</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-soft mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Tìm sự kiện...">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="completed">Đã kết thúc</option>
                        <option value="upcoming">Sắp diễn ra</option>
                    </select>
                </div>
                <div class="col-12 col-md-5 d-grid d-md-flex justify-content-md-end">
                    <button class="btn btn-outline-secondary me-md-2 mb-2 mb-md-0">Lọc</button>
                    <a href="{{ route('admin.events.create') }}" class="btn btn-admin">+ Tạo sự kiện mới</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-soft">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width:80px">STT</th>
                        <th>Tên sự kiện</th>
                        <th>Ngày bắt đầu đăng ký</th>
                        <th>Ngày kết thúc đăng ký</th>
                        <th>Ngày bắt đầu sự kiện</th>
                        <th>Ngày kết thúc sự kiện</th>
                        <th>Địa điểm</th>
                        <th>Người tham gia</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $index => $event)
                        <tr>
                            <td class="text-center">{{ $events->firstItem() + $index }}</td>
                            <td>{{ $event->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->register_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->register_end_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->event_start_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->event_start_date)->format('d/m/Y') }}</td>
                            <td>{{ $event->location }}</td>
                            <td>{{ $event->participants }} người</td>
                            <td>
                                @if ($event->status === 'completed')
                                    <span class="badge text-bg-success">Đã kết thúc</span>
                                @else
                                    <span class="badge text-bg-secondary">Sắp diễn ra</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning">Sửa</a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Xóa sự kiện này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Không có sự kiện nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $events->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
