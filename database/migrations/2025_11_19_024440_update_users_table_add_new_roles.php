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
        // Thay đổi enum role từ ['admin', 'user'] thành ['admin', 'manager', 'staff', 'student']
        // MySQL không hỗ trợ ALTER ENUM trực tiếp, nên cần drop và add lại
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'staff', 'student') DEFAULT 'student'");
        
        // Cập nhật tất cả user có role 'user' thành 'student'
        DB::table('users')->where('role', 'user')->update(['role' => 'student']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cập nhật tất cả student, staff, manager về 'user' trước
        DB::table('users')->whereIn('role', ['student', 'staff', 'manager'])->update(['role' => 'user']);
        
        // Khôi phục lại enum cũ
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') DEFAULT 'user'");
    }
};
