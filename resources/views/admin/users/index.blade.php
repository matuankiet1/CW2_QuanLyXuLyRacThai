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
            {{-- Form tìm kiếm và lọc --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                    {{-- Giữ lại tham số sắp xếp khi submit form --}}
                    @if(($sortBy ?? 'created_at') !== 'created_at' || ($sortOrder ?? 'desc') !== 'desc')
                        <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'created_at' }}">
                        <input type="hidden" name="sort_order" value="{{ $sortOrder ?? 'desc' }}">
                    @endif
                    <div class="flex items-center gap-4 flex-wrap">
                        {{-- Ô nhập từ khóa tìm kiếm --}}
                        <div class="flex-1 min-w-[250px]">
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
                        
                        {{-- Dropdown lọc theo role --}}
                        <div class="min-w-[150px]">
                            <select name="role" class="form-control py-2 w-full">
                                <option value="all" {{ ($roleFilter ?? 'all') === 'all' ? 'selected' : '' }}>Tất cả vai trò</option>
                                <option value="admin" {{ ($roleFilter ?? 'all') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ ($roleFilter ?? 'all') === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        
                        {{-- Nút tìm kiếm --}}
                        <button type="submit" class="btn-primary px-6 py-2 flex items-center whitespace-nowrap">
                            <i class="fas fa-search mr-2"></i>
                            <span>Tìm kiếm</span>
                        </button>
                        
                        {{-- Nút xóa bộ lọc (hiển thị khi có filter) --}}
                        @if(($keyword ?? false) || (($roleFilter ?? 'all') !== 'all'))
                            <a href="{{ route('admin.users.index') }}" class="btn-secondary px-4 py-2 flex items-center whitespace-nowrap">
                                <i class="fas fa-times mr-2"></i>
                                <span>Xóa bộ lọc</span>
                            </a>
                        @endif
                    </div>
                    
                    {{-- Hiển thị thông tin kết quả tìm kiếm --}}
                    @if(($keyword ?? false) || (($roleFilter ?? 'all') !== 'all'))
                        <div class="text-sm text-gray-600 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            <span>
                                @if($keyword && ($roleFilter ?? 'all') !== 'all')
                                    Đang tìm kiếm "{{ $keyword }}" với vai trò {{ $roleFilter === 'admin' ? 'Admin' : 'User' }}
                                @elseif($keyword)
                                    Đang tìm kiếm "{{ $keyword }}"
                                @elseif(($roleFilter ?? 'all') !== 'all')
                                    Đang lọc theo vai trò: {{ $roleFilter === 'admin' ? 'Admin' : 'User' }}
                                @endif
                            </span>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Bảng danh sách người dùng --}}
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            {{-- Cột ID với sắp xếp --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_order']), [
                                    'sort_by' => 'user_id',
                                    'sort_order' => ($sortBy ?? 'created_at') === 'user_id' && ($sortOrder ?? 'desc') === 'asc' ? 'desc' : 'asc'
                                ])) }}" class="flex items-center gap-2 hover:text-gray-900 transition-colors">
                                    <span>ID</span>
                                    @if(($sortBy ?? 'created_at') === 'user_id')
                                        @if(($sortOrder ?? 'desc') === 'asc')
                                            <i class="fas fa-sort-up text-blue-600"></i>
                                        @else
                                            <i class="fas fa-sort-down text-blue-600"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            {{-- Cột Tên với sắp xếp --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_order']), [
                                    'sort_by' => 'name',
                                    'sort_order' => ($sortBy ?? 'created_at') === 'name' && ($sortOrder ?? 'desc') === 'asc' ? 'desc' : 'asc'
                                ])) }}" class="flex items-center gap-2 hover:text-gray-900 transition-colors">
                                    <span>Tên</span>
                                    @if(($sortBy ?? 'created_at') === 'name')
                                        @if(($sortOrder ?? 'desc') === 'asc')
                                            <i class="fas fa-sort-up text-blue-600"></i>
                                        @else
                                            <i class="fas fa-sort-down text-blue-600"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            {{-- Cột Email với sắp xếp --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_order']), [
                                    'sort_by' => 'email',
                                    'sort_order' => ($sortBy ?? 'created_at') === 'email' && ($sortOrder ?? 'desc') === 'asc' ? 'desc' : 'asc'
                                ])) }}" class="flex items-center gap-2 hover:text-gray-900 transition-colors">
                                    <span>Email</span>
                                    @if(($sortBy ?? 'created_at') === 'email')
                                        @if(($sortOrder ?? 'desc') === 'asc')
                                            <i class="fas fa-sort-up text-blue-600"></i>
                                        @else
                                            <i class="fas fa-sort-down text-blue-600"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            {{-- Cột Vai trò với sắp xếp --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_order']), [
                                    'sort_by' => 'role',
                                    'sort_order' => ($sortBy ?? 'created_at') === 'role' && ($sortOrder ?? 'desc') === 'asc' ? 'desc' : 'asc'
                                ])) }}" class="flex items-center gap-2 hover:text-gray-900 transition-colors">
                                    <span>Vai trò</span>
                                    @if(($sortBy ?? 'created_at') === 'role')
                                        @if(($sortOrder ?? 'desc') === 'asc')
                                            <i class="fas fa-sort-up text-blue-600"></i>
                                        @else
                                            <i class="fas fa-sort-down text-blue-600"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
                            </th>
                            {{-- Cột Ngày tạo với sắp xếp --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_order']), [
                                    'sort_by' => 'created_at',
                                    'sort_order' => ($sortBy ?? 'created_at') === 'created_at' && ($sortOrder ?? 'desc') === 'asc' ? 'desc' : 'asc'
                                ])) }}" class="flex items-center gap-2 hover:text-gray-900 transition-colors">
                                    <span>Ngày tạo</span>
                                    @if(($sortBy ?? 'created_at') === 'created_at')
                                        @if(($sortOrder ?? 'desc') === 'asc')
                                            <i class="fas fa-sort-up text-blue-600"></i>
                                        @else
                                            <i class="fas fa-sort-down text-blue-600"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-gray-400"></i>
                                    @endif
                                </a>
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
                                
                                {{-- Hiển thị vai trò với badge màu sắc --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-shield-alt mr-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-user mr-1"></i>User
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- Hiển thị ngày tạo (định dạng: dd/mm/yyyy) --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            {{-- Hiển thị thông báo khi không có người dùng --}}
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
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
