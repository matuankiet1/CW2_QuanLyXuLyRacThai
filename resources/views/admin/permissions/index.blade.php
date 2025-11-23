@extends('layouts.admin-with-sidebar')

@section('title', 'Quản lý Permissions')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold mb-2">Quản lý Permissions</h1>
            <p class="text-gray-600">Phân quyền chi tiết cho từng role trong hệ thống</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
            <button onclick="document.getElementById('createPermissionModal').classList.remove('hidden')" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tạo Permission mới
            </button>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-4 p-4 rounded-lg {{ session('status')['type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ session('status')['message'] }}
        </div>
    @endif

    <!-- Role Permissions Management -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Phân quyền theo Role</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($roles as $role)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold mb-3 capitalize">
                        @if($role === 'admin')
                            <span class="text-red-600"><i class="fas fa-user-shield mr-2"></i>Admin</span>
                        @elseif($role === 'manager')
                            <span class="text-purple-600"><i class="fas fa-user-tie mr-2"></i>Quản lý</span>
                        @elseif($role === 'staff')
                            <span class="text-orange-600"><i class="fas fa-user-cog mr-2"></i>Nhân viên</span>
                        @else
                            <span class="text-green-600"><i class="fas fa-user-graduate mr-2"></i>Sinh viên</span>
                        @endif
                    </h3>
                    <form action="{{ route('admin.permissions.update-role-permissions') }}" method="POST" class="space-y-2 max-h-96 overflow-y-auto">
                        @csrf
                        <input type="hidden" name="role" value="{{ $role }}">
                        
                        @foreach($permissions as $category => $categoryPermissions)
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2 capitalize">{{ $category }}</h4>
                                @foreach($categoryPermissions as $permission)
                                    <label class="flex items-center mb-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}"
                                               {{ in_array($permission->id, $rolePermissions[$role] ?? []) ? 'checked' : '' }}
                                               class="mr-2 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</div>
                                            @if($permission->description)
                                                <div class="text-xs text-gray-500">{{ $permission->description }}</div>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                        
                        <button type="submit" class="w-full mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>Lưu thay đổi
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Permissions List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Danh sách Permissions</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hiển thị</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($permissions->flatten() as $permission)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $permission->name }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $permission->display_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $permission->description ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full capitalize">
                                {{ $permission->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editPermission({{ $permission->id }}, '{{ $permission->display_name }}', '{{ $permission->description }}', '{{ $permission->category }}')" 
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa permission này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Permission Modal -->
<div id="createPermissionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-semibold">Tạo Permission mới</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('createPermissionModal').classList.add('hidden')">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Tên (name) *</label>
                <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required placeholder="view_dashboard">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Tên hiển thị *</label>
                <input type="text" name="display_name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required placeholder="Xem Dashboard">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Mô tả</label>
                <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Danh mục *</label>
                <input type="text" name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md" required placeholder="dashboard">
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100" onclick="document.getElementById('createPermissionModal').classList.add('hidden')">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Tạo</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Permission Modal -->
<div id="editPermissionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-semibold">Chỉnh sửa Permission</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('editPermissionModal').classList.add('hidden')">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
        <form id="editPermissionForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Tên hiển thị *</label>
                <input type="text" name="display_name" id="edit_display_name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Mô tả</label>
                <textarea name="description" id="edit_description" class="w-full px-3 py-2 border border-gray-300 rounded-md" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Danh mục *</label>
                <input type="text" name="category" id="edit_category" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100" onclick="document.getElementById('editPermissionModal').classList.add('hidden')">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
function editPermission(id, displayName, description, category) {
    document.getElementById('editPermissionForm').action = `/admin/permissions/${id}`;
    document.getElementById('edit_display_name').value = displayName;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_category').value = category;
    document.getElementById('editPermissionModal').classList.remove('hidden');
}
</script>
@endsection

