<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'display_name' => 'Xem Dashboard', 'description' => 'Xem trang tổng quan hệ thống', 'category' => 'dashboard'],
            
            // Users Management
            ['name' => 'view_users', 'display_name' => 'Xem danh sách người dùng', 'description' => 'Xem danh sách tất cả người dùng', 'category' => 'users'],
            ['name' => 'create_users', 'display_name' => 'Tạo người dùng', 'description' => 'Tạo tài khoản người dùng mới', 'category' => 'users'],
            ['name' => 'edit_users', 'display_name' => 'Chỉnh sửa người dùng', 'description' => 'Chỉnh sửa thông tin người dùng', 'category' => 'users'],
            ['name' => 'delete_users', 'display_name' => 'Xóa người dùng', 'description' => 'Xóa tài khoản người dùng', 'category' => 'users'],
            ['name' => 'manage_roles', 'display_name' => 'Quản lý phân quyền', 'description' => 'Thay đổi role và permissions của người dùng', 'category' => 'users'],
            
            // Events Management
            ['name' => 'view_events', 'display_name' => 'Xem sự kiện', 'description' => 'Xem danh sách sự kiện', 'category' => 'events'],
            ['name' => 'create_events', 'display_name' => 'Tạo sự kiện', 'description' => 'Tạo sự kiện mới', 'category' => 'events'],
            ['name' => 'edit_events', 'display_name' => 'Chỉnh sửa sự kiện', 'description' => 'Chỉnh sửa thông tin sự kiện', 'category' => 'events'],
            ['name' => 'delete_events', 'display_name' => 'Xóa sự kiện', 'description' => 'Xóa sự kiện', 'category' => 'events'],
            ['name' => 'manage_event_participants', 'display_name' => 'Quản lý người tham gia', 'description' => 'Xác nhận và quản lý người tham gia sự kiện', 'category' => 'events'],
            ['name' => 'manage_event_rewards', 'display_name' => 'Quản lý điểm thưởng', 'description' => 'Cấp và quản lý điểm thưởng cho người tham gia', 'category' => 'events'],
            
            // Posts Management
            ['name' => 'view_posts', 'display_name' => 'Xem bài viết', 'description' => 'Xem danh sách bài viết', 'category' => 'posts'],
            ['name' => 'create_posts', 'display_name' => 'Tạo bài viết', 'description' => 'Tạo bài viết mới', 'category' => 'posts'],
            ['name' => 'edit_posts', 'display_name' => 'Chỉnh sửa bài viết', 'description' => 'Chỉnh sửa bài viết', 'category' => 'posts'],
            ['name' => 'delete_posts', 'display_name' => 'Xóa bài viết', 'description' => 'Xóa bài viết', 'category' => 'posts'],
            ['name' => 'publish_posts', 'display_name' => 'Xuất bản bài viết', 'description' => 'Phê duyệt và xuất bản bài viết', 'category' => 'posts'],
            
            // Reports Management
            ['name' => 'view_reports', 'display_name' => 'Xem báo cáo', 'description' => 'Xem danh sách báo cáo từ người dùng', 'category' => 'reports'],
            ['name' => 'manage_reports', 'display_name' => 'Quản lý báo cáo', 'description' => 'Xử lý và cập nhật trạng thái báo cáo', 'category' => 'reports'],
            ['name' => 'delete_reports', 'display_name' => 'Xóa báo cáo', 'description' => 'Xóa báo cáo', 'category' => 'reports'],
            ['name' => 'export_reports', 'display_name' => 'Xuất báo cáo', 'description' => 'Xuất dữ liệu báo cáo ra file', 'category' => 'reports'],
            
            // Collection Schedules
            ['name' => 'view_schedules', 'display_name' => 'Xem lịch thu gom', 'description' => 'Xem lịch thu gom rác', 'category' => 'schedules'],
            ['name' => 'create_schedules', 'display_name' => 'Tạo lịch thu gom', 'description' => 'Tạo lịch thu gom mới', 'category' => 'schedules'],
            ['name' => 'edit_schedules', 'display_name' => 'Chỉnh sửa lịch thu gom', 'description' => 'Chỉnh sửa lịch thu gom', 'category' => 'schedules'],
            ['name' => 'delete_schedules', 'display_name' => 'Xóa lịch thu gom', 'description' => 'Xóa lịch thu gom', 'category' => 'schedules'],
            
            // Banners Management
            ['name' => 'view_banners', 'display_name' => 'Xem banner', 'description' => 'Xem danh sách banner', 'category' => 'banners'],
            ['name' => 'create_banners', 'display_name' => 'Tạo banner', 'description' => 'Tạo banner mới', 'category' => 'banners'],
            ['name' => 'edit_banners', 'display_name' => 'Chỉnh sửa banner', 'description' => 'Chỉnh sửa banner', 'category' => 'banners'],
            ['name' => 'delete_banners', 'display_name' => 'Xóa banner', 'description' => 'Xóa banner', 'category' => 'banners'],
            
            // Notifications
            ['name' => 'view_notifications', 'display_name' => 'Xem thông báo', 'description' => 'Xem danh sách thông báo', 'category' => 'notifications'],
            ['name' => 'create_notifications', 'display_name' => 'Tạo thông báo', 'description' => 'Tạo thông báo mới', 'category' => 'notifications'],
            ['name' => 'send_notifications', 'display_name' => 'Gửi thông báo', 'description' => 'Gửi thông báo cho người dùng', 'category' => 'notifications'],
            
            // Statistics
            ['name' => 'view_statistics', 'display_name' => 'Xem thống kê', 'description' => 'Xem thống kê hệ thống', 'category' => 'statistics'],
            ['name' => 'export_statistics', 'display_name' => 'Xuất thống kê', 'description' => 'Xuất dữ liệu thống kê', 'category' => 'statistics'],
            
            // Settings
            ['name' => 'view_settings', 'display_name' => 'Xem cài đặt', 'description' => 'Xem cài đặt hệ thống', 'category' => 'settings'],
            ['name' => 'manage_settings', 'display_name' => 'Quản lý cài đặt', 'description' => 'Thay đổi cài đặt hệ thống', 'category' => 'settings'],
            
            // Student specific
            ['name' => 'register_events', 'display_name' => 'Đăng ký sự kiện', 'description' => 'Đăng ký tham gia sự kiện', 'category' => 'student'],
            ['name' => 'create_reports', 'display_name' => 'Tạo báo cáo', 'description' => 'Gửi báo cáo đến admin', 'category' => 'student'],
            ['name' => 'view_personal_stats', 'display_name' => 'Xem thống kê cá nhân', 'description' => 'Xem thống kê cá nhân', 'category' => 'student'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
