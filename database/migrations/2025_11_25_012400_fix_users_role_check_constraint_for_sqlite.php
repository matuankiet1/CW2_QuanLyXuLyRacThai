<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra xem đang dùng SQLite hay không
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            try {
                // Xóa bảng tạm nếu còn sót lại từ lần chạy trước
                DB::statement('DROP TABLE IF EXISTS users_new');
                // SQLite: Cần drop và recreate table với CHECK constraint mới
                // Vì SQLite không hỗ trợ ALTER TABLE để sửa CHECK constraint
                
                // 1. Tạo bảng tạm với CHECK constraint mới
                DB::statement("
                CREATE TABLE users_new (
                    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL,
                    email_verified_at DATETIME,
                    password TEXT NOT NULL,
                    auth_provider TEXT NOT NULL DEFAULT 'local' CHECK(auth_provider IN ('local', 'google', 'facebook')),
                    provider_id TEXT,
                    remember_token TEXT,
                    created_at DATETIME,
                    updated_at DATETIME,
                    phone TEXT,
                    role TEXT NOT NULL DEFAULT 'student' CHECK(role IN ('admin', 'manager', 'staff', 'student'))
                )
            ");
            
            // 2. Copy dữ liệu từ bảng cũ sang bảng mới
            // Cập nhật role 'user' thành 'student' nếu có
            DB::statement("
                INSERT INTO users_new (user_id, name, email, email_verified_at, password, auth_provider, provider_id, remember_token, created_at, updated_at, phone, role)
                SELECT 
                    user_id,
                    name,
                    email,
                    email_verified_at,
                    password,
                    auth_provider,
                    provider_id,
                    remember_token,
                    created_at,
                    updated_at,
                    phone,
                    CASE 
                        WHEN role = 'user' THEN 'student'
                        WHEN role IN ('admin', 'manager', 'staff', 'student') THEN role
                        ELSE 'student'
                    END as role
                FROM users
            ");
            
            // 3. Drop bảng cũ
            DB::statement("DROP TABLE users");
            
            // 4. Đổi tên bảng mới thành users
            DB::statement("ALTER TABLE users_new RENAME TO users");
            
                // 5. Tạo lại indexes và constraints
                DB::statement("CREATE UNIQUE INDEX users_email_unique ON users(email)");
                // Tạo unique constraint cho (auth_provider, provider_id) nếu provider_id không null
                DB::statement("CREATE UNIQUE INDEX users_authprovider_providerid_unique ON users(auth_provider, provider_id)");
            } finally {
                DB::statement('PRAGMA foreign_keys = ON');
            }
        } else {
            // MySQL/MariaDB: Sử dụng cú pháp MySQL
            // Migration này chỉ cần chạy nếu migration trước đó chưa chạy thành công
            try {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'staff', 'student') DEFAULT 'student'");
                DB::table('users')->where('role', 'user')->update(['role' => 'student']);
            } catch (\Exception $e) {
                // Nếu đã có ENUM đúng rồi thì bỏ qua
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            try {
                DB::statement('DROP TABLE IF EXISTS users_old');
                // SQLite: Khôi phục lại CHECK constraint cũ
                DB::statement("
                CREATE TABLE users_old (
                    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    email_verified_at DATETIME,
                    password TEXT NOT NULL,
                    auth_provider TEXT NOT NULL DEFAULT 'local' CHECK(auth_provider IN ('local', 'google', 'facebook')),
                    provider_id TEXT,
                    remember_token TEXT,
                    created_at DATETIME,
                    updated_at DATETIME,
                    phone TEXT,
                    role TEXT NOT NULL DEFAULT 'user' CHECK(role IN ('admin', 'user')),
                    UNIQUE(auth_provider, provider_id)
                )
            ");
            
            DB::statement("
                INSERT INTO users_old (user_id, name, email, email_verified_at, password, auth_provider, provider_id, remember_token, created_at, updated_at, phone, role)
                SELECT 
                    user_id,
                    name,
                    email,
                    email_verified_at,
                    password,
                    auth_provider,
                    provider_id,
                    remember_token,
                    created_at,
                    updated_at,
                    phone,
                    CASE 
                        WHEN role IN ('student', 'staff', 'manager') THEN 'user'
                        WHEN role = 'admin' THEN 'admin'
                        ELSE 'user'
                    END as role
                FROM users
            ");
            
                DB::statement("DROP TABLE users");
                DB::statement("ALTER TABLE users_old RENAME TO users");
                DB::statement("CREATE UNIQUE INDEX users_email_unique ON users(email)");
                DB::statement("CREATE UNIQUE INDEX users_authprovider_providerid_unique ON users(auth_provider, provider_id)");
            } finally {
                DB::statement('PRAGMA foreign_keys = ON');
            }
        } else {
            // MySQL: Khôi phục lại ENUM cũ
            try {
                DB::table('users')->whereIn('role', ['student', 'staff', 'manager'])->update(['role' => 'user']);
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') DEFAULT 'user'");
            } catch (\Exception $e) {
                // Bỏ qua nếu có lỗi
            }
        }
    }
};

