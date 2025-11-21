@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý phân quyền')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-start mb-3">
        <div>
            <h1 class="text-2xl font-semibold mb-1">Quản lý phân quyền</h1>
            <p class="text-gray-500">Quản lý quyền hạn của người dùng trong hệ thống</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-admin">
                <i class="fas fa-key mr-2"></i>Quản lý Permissions
            </a>
            <button class="btn btn-admin" onclick="document.getElementById('createAdminModal').classList.remove('hidden')">
                <i class="fas fa-user-shield mr-2"></i>Tạo tài khoản mới
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3">
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded-full bg-blue-100 text-blue-600 flex items-center justify-center" style="width:40px;height:40px;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Tổng người dùng</div>
                    <div class="text-2xl font-semibold mb-0">{{ $users->total() }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded-full bg-red-100 text-red-600 flex items-center justify-center" style="width:40px;height:40px;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Admin</div>
                    <div class="text-2xl font-semibold mb-0">{{ $adminCount ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded-full bg-purple-100 text-purple-600 flex items-center justify-center" style="width:40px;height:40px;">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Quản lý</div>
                    <div class="text-2xl font-semibold mb-0">{{ $managerCount ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded-full bg-orange-100 text-orange-600 flex items-center justify-center" style="width:40px;height:40px;">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Nhân viên</div>
                    <div class="text-2xl font-semibold mb-0">{{ $staffCount ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded-full bg-green-100 text-green-600 flex items-center justify-center" style="width:40px;height:40px;">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Sinh viên</div>
                    <div class="text-2xl font-semibold mb-0">{{ $studentCount ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b bg-white px-4 py-3">
            <h5 class="mb-0 font-semibold">Danh sách người dùng</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse table-auto">
                <thead>
                    <tr class="border-b">
                        <th class="px-4 py-3 text-left font-semibold">Người dùng</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-left font-semibold">Số điện thoại</th>
                        <th class="px-4 py-3 text-left font-semibold">Vai trò</th>
                        <th class="px-4 py-3 text-left font-semibold">Ngày tạo</th>
                        <th class="px-4 py-3 text-left font-semibold">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="rounded-full bg-gray-100 flex items-center justify-center" style="width:40px;height:40px;">
                                    <span class="text-sm font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="font-medium">{{ $user->name }}</div>
                                    @if($user->email === 'admin@ecowaste.com')
                                        <span class="px-2 py-1 rounded text-xs font-medium bg-red-500 text-white">Super Admin</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                        <td class="px-4 py-3">
                            @if($user->role === 'admin')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-red-500 text-white">Admin</span>
                            @elseif($user->role === 'manager')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-purple-500 text-white">Quản lý</span>
                            @elseif($user->role === 'staff')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-orange-500 text-white">Nhân viên</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs font-medium bg-green-500 text-white">Sinh viên</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if($user->id !== auth()->id() && $user->email !== 'admin@ecowaste.com')
                                    <form action="{{ route('admin.roles.update', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" class="px-2 py-1 text-sm border border-gray-300 rounded">
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Quản lý</option>
                                            <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Nhân viên</option>
                                            <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Sinh viên</option>
                                        </select>
                                    </form>
                                    @if(in_array($user->role, ['student', 'staff']))
                                        <form action="{{ route('admin.roles.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-sm border border-red-500 text-red-500 rounded hover:bg-red-500 hover:text-white">Xóa</button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-gray-500 text-sm">Không thể thay đổi</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">Không có người dùng nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t pt-4 bg-white px-4 pb-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Create Admin Modal -->
<div id="createAdminModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
    <div class="flex justify-between items-center mb-4">
        <h5 class="text-lg font-semibold">Tạo tài khoản admin mới</h5>
        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('createAdminModal').classList.add('hidden')">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
    <form action="{{ route('admin.roles.create') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Họ và tên</label>
            <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Số điện thoại</label>
            <input type="tel" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Mật khẩu</label>
            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100" onclick="document.getElementById('createAdminModal').classList.add('hidden')">Hủy</button>
            <button type="submit" class="btn btn-admin">Tạo admin</button>
        </div>
    </form>
  </div>
</div>
@endsection
