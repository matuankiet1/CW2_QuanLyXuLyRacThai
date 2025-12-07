<?php
/**
 * Script tạm thời để sửa CHECK constraint SQLite
 * Truy cập: http://127.0.0.1:8000/fix-db-role.php
 * XÓA FILE NÀY SAU KHI ĐÃ SỬA XONG
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

echo '<h1>🔧 Script sửa CHECK constraint SQLite</h1>';

try {
    require __DIR__ . '/../vendor/autoload.php';
    echo '<p>✅ Đã load autoload</p>';
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo '<p>✅ Đã load app</p>';
    
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo '<p>✅ Đã bootstrap</p>';
    
    $driver = \Illuminate\Support\Facades\DB::getDriverName();
    echo '<p>📊 Database driver: ' . $driver . '</p>';
    
    if ($driver !== 'sqlite') {
        die('<h1>❌ Lỗi</h1><p>Chỉ dành cho SQLite. Database hiện tại: ' . $driver . '</p>');
    }
    
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();

    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();
    
    echo "<h2>📝 Đang tạo bảng mới...</h2>";
    $pdo->exec("CREATE TABLE users_new (
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
    )");
    
    echo "<h2>📋 Đang copy dữ liệu...</h2>";
    $pdo->exec("INSERT INTO users_new SELECT user_id, name, email, email_verified_at, password, auth_provider, provider_id, remember_token, created_at, updated_at, phone, CASE WHEN role = 'user' THEN 'student' WHEN role IN ('admin', 'manager', 'staff', 'student') THEN role ELSE 'student' END FROM users");
    
    echo "<h2>🗑️ Đang xóa bảng cũ...</h2>";
    $pdo->exec("DROP TABLE users");
    
    echo "<h2>🔄 Đang đổi tên bảng...</h2>";
    $pdo->exec("ALTER TABLE users_new RENAME TO users");
    
    echo "<h2>📌 Đang tạo lại indexes...</h2>";
    $pdo->exec("CREATE UNIQUE INDEX users_email_unique ON users(email)");
    $pdo->exec("CREATE UNIQUE INDEX users_authprovider_providerid_unique ON users(auth_provider, provider_id)");
    
    $pdo->commit();
    
    echo '<h1 style="color: green;">✅ Thành công!</h1>';
    echo '<p>CHECK constraint đã được cập nhật thành công.</p>';
    echo '<p>Bây giờ bạn có thể đăng ký với role = student</p>';
    echo '<p><a href="/register" style="background: green; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">← Quay về trang đăng ký</a></p>';
    echo '<p style="color: red; margin-top: 20px;"><strong>⚠️ LƯU Ý: Xóa file này sau khi đã sửa xong để bảo mật!</strong></p>';
    
} catch (\Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo '<h1 style="color: red;">❌ Lỗi:</h1>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '<p><strong>Lỗi xảy ra tại:</strong> ' . $e->getFile() . ':' . $e->getLine() . '</p>';
}

