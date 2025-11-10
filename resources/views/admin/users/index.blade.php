{{-- 
    View: Danh sách người dùng với phân trang và tìm kiếm
    Route: GET /admin/users
    Controller: UserController@index
    Layout: layouts/admin-with-sidebar
    
    Chức năng:
    - Hiển thị danh sách người dùng trong bảng
    - Hỗ trợ tìm kiếm theo tên và email
    - Phân trang 10 người dùng mỗi trang
    - Giữ lại từ khóa tìm kiếm khi chuyển trang
--}}
@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý người dùng - Admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header với tiêu đề và nút thêm người dùng --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Quản lý người dùng</h1>
                <p class="text-gray-600 mt-1">Danh sách người dùng trong hệ thống</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-admin flex items-center">
                <i class="fas fa-plus mr-2"></i>
                <span>Thêm người dùng</span>
            </a>
        </div>

        {{-- Card chứa bảng danh sách --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            {{-- Form tìm kiếm --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-4">
                    {{-- Ô nhập từ khóa tìm kiếm --}}
                    <div class="flex-1">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="keyword" 
                                value="{{ $keyword ?? '' }}" 
                                placeholder="Tìm kiếm theo tên hoặc email..." 
                                class="form-control pl-10 pr-4 py-2 w-full"
                            >
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    {{-- Nút tìm kiếm --}}
                    <button type="submit" class="btn-primary px-6 py-2 flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        <span>Tìm kiếm</span>
                    </button>
                    
                    {{-- Nút xóa bộ lọc (hiển thị khi có từ khóa) --}}
                    @if($keyword ?? false)
                        <a href="{{ route('admin.users.index') }}" class="btn-secondary px-4 py-2 flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            <span>Xóa</span>
                        </a>
                    @endif
                </form>
            </div>

            {{-- Bảng danh sách người dùng --}}
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            {{-- Cột ID --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                ID
                            </th>
                            {{-- Cột Tên --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Tên
                            </th>
                            {{-- Cột Email --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Email
                            </th>
                            {{-- Cột Ngày tạo --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                Ngày tạo
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Kiểm tra nếu có người dùng --}}
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Hiển thị ID người dùng --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $user->user_id }}
                                </td>
                                
                                {{-- Hiển thị tên người dùng với avatar --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center text-white font-semibold mr-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Hiển thị email người dùng --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $user->email }}
                                </td>
                                
                                {{-- Hiển thị ngày tạo (định dạng: dd/mm/yyyy) --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            {{-- Hiển thị thông báo khi không có người dùng --}}
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                                        <p class="text-gray-500 text-lg font-medium">
                                            @if($keyword ?? false)
                                                Không tìm thấy người dùng nào với từ khóa "{{ $keyword }}"
                                            @else
                                                Chưa có người dùng nào trong hệ thống
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang với Tailwind CSS --}}
            @if ($users->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{-- Links phân trang - Sử dụng custom Tailwind pagination view (tự động hiển thị thông tin số lượng) --}}
                    {{ $users->links('pagination.tailwind') }}
                </div>
            @else
                {{-- Hiển thị thông tin khi chỉ có 1 trang --}}
                @if($users->total() > 0)
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="text-sm text-gray-700 text-center">
                            Hiển thị tất cả 
                            <span class="font-medium">{{ $users->total() }}</span>
                            người dùng
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
