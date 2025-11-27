<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin có tất cả quyền (không cần gán, vì trong code đã xử lý)
        // Nhưng vẫn có thể gán để dễ quản lý
        
        // Manager role đã bị xóa, các quyền của manager đã được chuyển sang admin

        // Staff permissions
        $staffPermissions = [
            'view_dashboard',
            'view_events',
            'manage_event_participants',
            'view_posts',
            'create_posts',
            'view_reports',
            'manage_reports',
            'view_schedules',
            'view_banners',
            'view_notifications',
            'view_statistics',
        ];

        // Student permissions
        $studentPermissions = [
            'view_posts',
            'register_events',
            'create_reports',
            'view_personal_stats',
        ];

        // Gán permissions cho Staff
        foreach ($staffPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                RolePermission::assignPermission('staff', $permission->id);
            }
        }

        // Gán permissions cho Student
        foreach ($studentPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                RolePermission::assignPermission('student', $permission->id);
            }
        }
    }
}
