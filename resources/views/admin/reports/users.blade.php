@extends('layouts.admin-with-sidebar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>
                        Báo cáo chi tiết người dùng
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Bộ lọc -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.reports.users') }}" class="form-inline">
                                <div class="form-group mr-2">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ $search }}">
                                </div>
                                <div class="form-group mr-2">
                                    <select name="role" class="form-control">
                                        <option value="">Tất cả vai trò</option>
                                        <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ $role == 'user' ? 'selected' : '' }}>User</option>
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
                                <a href="{{ route('admin.reports.users') }}" class="btn btn-secondary ml-2">
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
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>Số bài viết</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge badge-danger">Admin</span>
                                        @else
                                            <span class="badge badge-primary">User</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $user->posts_count }}</span>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge badge-success">Đã xác thực</span>
                                        @else
                                            <span class="badge badge-warning">Chưa xác thực</span>
                                        @endif
                                    </td>
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
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
