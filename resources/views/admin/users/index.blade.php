@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý người dùng - Admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="h3 mb-1">Quản lý người dùng</h1>
                <p class="text-muted">Quản lý tài khoản người dùng trong hệ thống</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-admin">
                <i class="fas fa-plus me-2"></i>Thêm người dùng
            </a>
        </div>

        <div class="card shadow-soft rounded-xl">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Loại tài khoản</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="text-nowrap">{{ $user->user_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                            <span class="fw-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="text-nowrap">{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'text-bg-primary' : 'text-bg-secondary' }}">{{ $user->role === 'admin' ? 'Quản trị viên' : 'Người dùng' }}</span>
                                </td>
                                <td>
                                    @if ($user->isLocal())
                                        <span class="badge text-bg-light">Local</span>
                                    @elseif($user->isGoogle())
                                        <span class="badge text-bg-danger">Google</span>
                                    @else
                                        <span class="badge text-bg-info">Facebook</span>
                                    @endif
                                </td>
                                <td class="text-muted text-nowrap">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if ($user->user_id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Chưa có người dùng nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="card-footer bg-white">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

