<?php

/**
 * Script để sửa CHECK constraint cho cột role trong bảng users (SQLite)
 * Chạy script này: php fix_sqlite_role_constraint.php [đường-dẫn-database.sqlite]
 */

// Cho phép chỉ định đường dẫn database từ command line
if (isset($argv[1]) && file_exists($argv[1])) {
    $dbPath = $argv[1];
} else {
    // Đọc đường dẫn database từ .env hoặc dùng mặc định
    $envPath = __DIR__ . '/.env';
    $dbPath = null;

    if (file_exists($envPath)) {
        $envContent = file_get_contents($envPath);
        // Tìm DB_DATABASE hoặc DB_CONNECTION
        if (preg_match('/DB_CONNECTION=(.+)/', $envContent, $matches)) {
            $connection = trim($matches[1]);
            if ($connection === 'sqlite') {
                if (preg_match('/DB_DATABASE=(.+)/', $envContent, $dbMatches)) {
                    $dbPath = trim($dbMatches[1]);
                    // Nếu là đường dẫn tương đối, chuyển thành tuyệt đối
                    if (!file_exists($dbPath) && !str_starts_with($dbPath, '/') && !preg_match('/^[A-Z]:/', $dbPath)) {
                        $dbPath = __DIR__ . '/' . $dbPath;
                    }
                } else {
                    $dbPath = __DIR__ . '/database/database.sqlite';
                }
            }
        }
    }

    // Nếu không tìm thấy, thử các đường dẫn mặc định
    if (!$dbPath || !file_exists($dbPath)) {
        $possiblePaths = [
            __DIR__ . '/database/database.sqlite',
            __DIR__ . '/database.sqlite',
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $dbPath = $path;
                break;
            }
        }
    }
}

// Kiểm tra file database có tồn tại không
if (!$dbPath || !file_exists($dbPath)) {
    echo "❌ Không tìm thấy file database SQLite.\n";
    echo "Vui lòng chỉ định đường dẫn database:\n";
    echo "php fix_sqlite_role_constraint.php /path/to/database.sqlite\n";
    exit(1);
}

echo "📁 Sử dụng database: $dbPath\n\n";

try {
    // Kết nối đến SQLite database
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Đã kết nối đến database\n";
    
    // Bắt đầu transaction
    $pdo->beginTransaction();
    
    // 1. Tạo bảng tạm với CHECK constraint mới
    echo "📝 Đang tạo bảng tạm với CHECK constraint mới...\n";
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
    echo "📋 Đang copy dữ liệu...\n";
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
    echo "🗑️  Đang xóa bảng cũ...\n";
    $pdo->exec("DROP TABLE users");
    
    // 4. Đổi tên bảng mới thành users
    echo "🔄 Đang đổi tên bảng...\n";
    $pdo->exec("ALTER TABLE users_new RENAME TO users");
    
    // 5. Tạo lại indexes
    echo "📌 Đang tạo lại indexes...\n";
    $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS users_email_unique ON users(email)");
    $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS users_authprovider_providerid_unique ON users(auth_provider, provider_id)");
    
    // Commit transaction
    $pdo->commit();
    
    echo "\n✅ Hoàn thành! CHECK constraint đã được cập nhật thành công.\n";
    echo "✅ Bây giờ bạn có thể đăng ký user mới với role = 'student'\n";
    
} catch (PDOException $e) {
    // Rollback nếu có lỗi
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}

