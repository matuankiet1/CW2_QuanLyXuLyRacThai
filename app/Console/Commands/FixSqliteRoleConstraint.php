<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class FixSqliteRoleConstraint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fix-sqlite-role-constraint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sửa CHECK constraint cho cột role trong bảng users (SQLite)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Kiểm tra xem có đang dùng SQLite không
        if (DB::getDriverName() !== 'sqlite') {
            $this->error('Lệnh này chỉ dành cho SQLite database.');
            $this->info('Database hiện tại: ' . DB::getDriverName());
            return 1;
        }

        $this->info('🔧 Bắt đầu sửa CHECK constraint cho cột role...');

        try {
            $pdo = DB::connection()->getPdo();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->info('✅ Đã kết nối đến database');

            // Bắt đầu transaction
            $pdo->beginTransaction();

            // 1. Tạo bảng tạm với CHECK constraint mới
            $this->info('📝 Đang tạo bảng tạm với CHECK constraint mới...');
            $pdo->exec("
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
            $this->info('📋 Đang copy dữ liệu...');
            $pdo->exec("
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
            $this->info('🗑️  Đang xóa bảng cũ...');
            $pdo->exec("DROP TABLE users");

            // 4. Đổi tên bảng mới thành users
            $this->info('🔄 Đang đổi tên bảng...');
            $pdo->exec("ALTER TABLE users_new RENAME TO users");

            // 5. Tạo lại indexes
            $this->info('📌 Đang tạo lại indexes...');
            $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS users_email_unique ON users(email)");
            $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS users_authprovider_providerid_unique ON users(auth_provider, provider_id)");

            // Commit transaction
            $pdo->commit();

            $this->info('');
            $this->info('✅ Hoàn thành! CHECK constraint đã được cập nhật thành công.');
            $this->info('✅ Bây giờ bạn có thể đăng ký user mới với role = "student"');

            return 0;
        } catch (PDOException $e) {
            // Rollback nếu có lỗi
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $this->error('❌ Lỗi: ' . $e->getMessage());
            return 1;
        }
    }
}

