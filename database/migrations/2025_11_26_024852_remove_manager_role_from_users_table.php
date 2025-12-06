<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Xóa role 'manager' khỏi hệ thống, chỉ giữ lại: admin, staff, student
     */
    public function up(): void
    {
        // Cập nhật tất cả user có role 'manager' thành 'staff' hoặc 'admin' (tùy logic nghiệp vụ)
        // Ở đây ta sẽ chuyển manager thành staff
        \Illuminate\Support\Facades\DB::table('users')
            ->where('role', 'manager')
            ->update(['role' => 'staff']);
        
        // Cập nhật constraint trong database
        // Với SQLite
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
            // SQLite không hỗ trợ ALTER COLUMN trực tiếp, cần tạo bảng mới
            \Illuminate\Support\Facades\DB::statement("
                CREATE TABLE users_new (
                    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    email_verified_at DATETIME NULL,
                    password TEXT NOT NULL,
                    auth_provider TEXT NOT NULL DEFAULT 'local' CHECK(auth_provider IN ('local', 'google', 'facebook')),
                    provider_id TEXT NULL,
                    remember_token TEXT NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL,
                    phone TEXT NULL,
                    role TEXT NOT NULL DEFAULT 'student' CHECK(role IN ('admin', 'staff', 'student')),
                    UNIQUE(auth_provider, provider_id)
                )
            ");
            
            \Illuminate\Support\Facades\DB::statement("
                INSERT INTO users_new 
                SELECT user_id, name, email, email_verified_at, password, auth_provider, provider_id, 
                       remember_token, created_at, updated_at, phone, 
                       CASE 
                           WHEN role IN ('admin', 'staff', 'student') THEN role 
                           ELSE 'student' 
                       END as role
                FROM users
            ");
            
            \Illuminate\Support\Facades\DB::statement("DROP TABLE users");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE users_new RENAME TO users");
        } else {
            // MySQL/MariaDB
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'student') DEFAULT 'student'");
        }
        
        // Cập nhật role_permissions table nếu có
        \Illuminate\Support\Facades\DB::table('role_permissions')
            ->where('role', 'manager')
            ->delete();
        
        // Cập nhật enum trong role_permissions table
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
            // SQLite: Tạo bảng mới và copy dữ liệu
            \Illuminate\Support\Facades\DB::statement("
                CREATE TABLE role_permissions_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    role TEXT NOT NULL CHECK(role IN ('admin', 'staff', 'student')),
                    permission_id INTEGER NOT NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL,
                    UNIQUE(role, permission_id),
                    FOREIGN KEY(permission_id) REFERENCES permissions(id) ON DELETE CASCADE
                )
            ");
            
            \Illuminate\Support\Facades\DB::statement("
                INSERT INTO role_permissions_new 
                SELECT id, role, permission_id, created_at, updated_at
                FROM role_permissions
                WHERE role IN ('admin', 'staff', 'student')
            ");
            
            \Illuminate\Support\Facades\DB::statement("DROP TABLE role_permissions");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE role_permissions_new RENAME TO role_permissions");
            \Illuminate\Support\Facades\DB::statement("CREATE INDEX role_permissions_role_index ON role_permissions(role)");
        } else {
            // MySQL/MariaDB: Sửa enum trực tiếp
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE role_permissions MODIFY COLUMN role ENUM('admin', 'staff', 'student')");
        }
    }

    /**
     * Reverse the migrations.
     * Khôi phục lại role manager (nếu cần rollback)
     */
    public function down(): void
    {
        // Với SQLite
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
            \Illuminate\Support\Facades\DB::statement("
                CREATE TABLE users_new (
                    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    email_verified_at DATETIME NULL,
                    password TEXT NOT NULL,
                    auth_provider TEXT NOT NULL DEFAULT 'local' CHECK(auth_provider IN ('local', 'google', 'facebook')),
                    provider_id TEXT NULL,
                    remember_token TEXT NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL,
                    phone TEXT NULL,
                    role TEXT NOT NULL DEFAULT 'student' CHECK(role IN ('admin', 'manager', 'staff', 'student')),
                    UNIQUE(auth_provider, provider_id)
                )
            ");
            
            \Illuminate\Support\Facades\DB::statement("
                INSERT INTO users_new 
                SELECT user_id, name, email, email_verified_at, password, auth_provider, provider_id, 
                       remember_token, created_at, updated_at, phone, role
                FROM users
            ");
            
            \Illuminate\Support\Facades\DB::statement("DROP TABLE users");
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE users_new RENAME TO users");
        } else {
            // MySQL/MariaDB
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'staff', 'student') DEFAULT 'student'");
        }
    }
};
