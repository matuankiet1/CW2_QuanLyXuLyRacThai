@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý người dùng - Admin')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-semibold mb-1">Quản lý người dùng</h1>
                <p class="text-gray-500">Quản lý tài khoản người dùng trong hệ thống</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-admin">
                <i class="fas fa-plus mr-2"></i>Thêm người dùng
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse table-auto">
                    <thead>
                        <tr class="border-b">
                            <th class="px-4 py-3 text-left font-semibold">ID</th>
                            <th class="px-4 py-3 text-left font-semibold">Tên</th>
                            <th class="px-4 py-3 text-left font-semibold">Email</th>
                            <th class="px-4 py-3 text-left font-semibold">Vai trò</th>
                            <th class="px-4 py-3 text-left font-semibold">Loại tài khoản</th>
                            <th class="px-4 py-3 text-left font-semibold">Ngày tạo</th>
                            <th class="px-4 py-3 text-left font-semibold">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">{{ $user->user_id }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="rounded-full bg-green-500 text-white flex items-center justify-center mr-3" style="width:40px;height:40px;">
                                            <span class="font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div class="font-medium">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium {{ $user->role === 'admin' ? 'bg-blue-500 text-white' : 'bg-gray-500 text-white' }}">{{ $user->role === 'admin' ? 'Quản trị viên' : 'Người dùng' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($user->isLocal())
                                        <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">Local</span>
                                    @elseif($user->isGoogle())
                                        <span class="px-2 py-1 rounded text-xs font-medium bg-red-500 text-white">Google</span>
                                    @else
                                        <span class="px-2 py-1 rounded text-xs font-medium bg-blue-400 text-white">Facebook</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2 text-sm">
                                        <a href="{{ route('admin.users.edit', $user->user_id) }}" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if ($user->user_id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-4">Chưa có người dùng nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t pt-4 bg-white px-4 pb-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
