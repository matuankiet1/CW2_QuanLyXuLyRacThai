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
        // Cập nhật tất cả user có role 'user' thành 'student'
        DB::table('users')->where('role', 'user')->update(['role' => 'student']);
        
        // Thay đổi enum role từ ['admin', 'user'] thành ['admin', 'manager', 'staff', 'student']
        // Chỉ thực hiện với MySQL, SQLite không hỗ trợ MODIFY COLUMN và ENUM
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'staff', 'student') DEFAULT 'student'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cập nhật tất cả student, staff, manager về 'user' trước
        DB::table('users')->whereIn('role', ['student', 'staff', 'manager'])->update(['role' => 'user']);
        
        // Khôi phục lại enum cũ (chỉ với MySQL)
        if (DB::getDriverName() !== 'sqlite') {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') DEFAULT 'user'");
        }
    }
};
