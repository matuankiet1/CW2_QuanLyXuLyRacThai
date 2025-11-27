<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Thêm capacity và created_by vào bảng events
 * 
 * Mục đích:
 * - capacity: Số lượng tối đa sinh viên có thể tham gia sự kiện
 * - created_by: Người tạo sự kiện (admin)
 * - Cập nhật status enum để thêm 'ongoing', 'ended'
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Thêm cột capacity (số lượng tối đa)
            $table->integer('capacity')->nullable()->after('participants');
            
            // Thêm cột created_by (người tạo sự kiện)
            $table->foreignId('created_by')->nullable()->after('capacity')
                  ->constrained('users', 'user_id')->onDelete('set null');
            
            // Cập nhật status enum để thêm 'ongoing', 'ended'
            // Laravel không hỗ trợ thay đổi enum trực tiếp, cần làm thủ công
            // Tạm thời giữ nguyên enum hiện tại, sẽ xử lý trong Model
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Xóa foreign key trước
            $table->dropForeign(['created_by']);
            
            // Xóa cột
            $table->dropColumn(['capacity', 'created_by']);
        });
    }
};
