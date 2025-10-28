@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar mr-2"></i>
                        Báo cáo chi tiết lịch thu gom
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Bộ lọc -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.reports.schedules') }}" class="form-inline">
                                <div class="form-group mr-2">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ $search }}">
                                </div>
                                <div class="form-group mr-2">
                                    <select name="status" class="form-control">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="scheduled" {{ $status == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                                </div>
                                <div class="form-group mr-2">
                                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('admin.reports.schedules') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-refresh"></i> Làm mới
                                </a>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Bảng dữ liệu -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Địa điểm</th>
                                    <th>Mô tả</th>
                                    <th>Ngày thu gom</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                <tr>
                                    <td>{{ $loop->iteration + ($schedules->currentPage() - 1) * $schedules->perPage() }}</td>
                                    <td>
                                        <strong>{{ $schedule->location }}</strong>
                                    </td>
                                    <td>{{ Str::limit($schedule->description, 50) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->collection_date)->format('d/m/Y') }}</td>
                                    <td>{{ $schedule->collection_time ?? 'Chưa xác định' }}</td>
                                    <td>
                                        @switch($schedule->status)
                                            @case('scheduled')
                                                <span class="badge badge-primary">Đã lên lịch</span>
                                                @break
                                            @case('completed')
                                                <span class="badge badge-success">Đã hoàn thành</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge badge-danger">Đã hủy</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ $schedule->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $schedule->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center">
                        {{ $schedules->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
