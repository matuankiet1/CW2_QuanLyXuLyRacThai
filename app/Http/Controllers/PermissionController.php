<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Hiển thị trang quản lý permissions
     */
    public function index()
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        $roles = ['admin', 'manager', 'staff', 'student'];
        
        // Lấy permissions của từng role
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role] = RolePermission::where('role', $role)
                ->pluck('permission_id')
                ->toArray();
        }
        
        return view('admin.permissions.index', compact('permissions', 'roles', 'rolePermissions'));
    }

    /**
     * Lưu permissions cho role
     */
    public function updateRolePermissions(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:admin,manager,staff,student'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = $request->role;
        $permissionIds = $request->permissions ?? [];

        DB::transaction(function () use ($role, $permissionIds) {
            // Xóa tất cả permissions cũ của role
            RolePermission::where('role', $role)->delete();

            // Thêm permissions mới
            foreach ($permissionIds as $permissionId) {
                RolePermission::assignPermission($role, $permissionId);
            }
        });

        return back()->with('status', [
            'type' => 'success',
            'message' => "Đã cập nhật quyền cho role {$role} thành công!"
        ]);
    }

    /**
     * Tạo permission mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:255'],
        ]);

        Permission::create($request->only(['name', 'display_name', 'description', 'category']));

        return back()->with('status', [
            'type' => 'success',
            'message' => 'Đã tạo permission mới thành công!'
        ]);
    }

    /**
     * Cập nhật permission
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:255'],
        ]);

        $permission->update($request->only(['display_name', 'description', 'category']));

        return back()->with('status', [
            'type' => 'success',
            'message' => 'Đã cập nhật permission thành công!'
        ]);
    }

    /**
     * Xóa permission
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return back()->with('status', [
            'type' => 'success',
            'message' => 'Đã xóa permission thành công!'
        ]);
    }
}
