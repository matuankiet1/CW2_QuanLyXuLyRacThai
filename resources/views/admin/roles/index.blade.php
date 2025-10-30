@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý phân quyền')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="h3 mb-1">Quản lý phân quyền</h1>
            <p class="text-muted">Quản lý quyền hạn của người dùng trong hệ thống</p>
        </div>
        <button class="btn btn-admin" data-bs-toggle="modal" data-bs-target="#createAdminModal">
            <i class="fas fa-user-shield me-2"></i>Tạo tài khoản admin mới
        </button>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Tổng người dùng</div>
                        <div class="h4 mb-0">{{ $users->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Quản trị viên</div>
                        <div class="h4 mb-0">{{ $adminCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-soft h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Người dùng</div>
                        <div class="h4 mb-0">{{ $userCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-soft">
        <div class="card-header bg-white">
            <h5 class="mb-0">Danh sách người dùng</h5>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                    <span class="small fw-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div class="ms-3">
                                    <div class="fw-medium">{{ $user->name }}</div>
                                    @if($user->email === 'admin@ecowaste.com')
                                        <span class="badge text-bg-danger">Super Admin</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge text-bg-danger">Admin</span>
                            @else
                                <span class="badge text-bg-success">User</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->id !== auth()->id() && $user->email !== 'admin@ecowaste.com')
                                    <form action="{{ route('admin.roles.update', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" class="form-select form-select-sm w-auto {{ $user->role === 'admin' ? 'is-invalid' : 'is-valid' }}">
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                    @if($user->role === 'user')
                                        <form action="{{ route('admin.roles.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-muted small">Không thể thay đổi</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Không có người dùng nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Create Admin Modal -->
<div class="modal fade" id="createAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tạo tài khoản admin mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.roles.create') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="tel" name="phone" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-admin">Tạo admin</button>
        </div>
      </form>
    </div>
  </div>
  </div>
@endsection
