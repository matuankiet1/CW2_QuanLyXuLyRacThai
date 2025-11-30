<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng trash_requests để lưu trữ yêu cầu thu gom rác
     * State machine: pending → assigned → staff_done → waiting_admin → admin_approved / admin_rejected
     */
    public function up(): void
    {
        Schema::create('trash_requests', function (Blueprint $table) {
            $table->id('request_id');
            
            // Thông tin người gửi yêu cầu (Student)
            $table->foreignId('student_id')->constrained('users', 'user_id')->onDelete('cascade');
            
            // Thông tin yêu cầu thu gom
            $table->string('location'); // Địa điểm thu gom
            $table->string('type'); // Loại rác (có thể tham chiếu đến waste_types)
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->string('image')->nullable(); // Ảnh minh chứng từ student
            
            // Trạng thái yêu cầu (state machine)
            $table->enum('status', [
                'pending',        // Đang chờ gán
                'assigned',       // Đã gán cho staff
                'staff_done',     // Staff đã hoàn thành
                'waiting_admin',  // Đang chờ admin duyệt
                'admin_approved', // Admin đã duyệt
                'admin_rejected'  // Admin từ chối
            ])->default('pending');
            
            // Thông tin staff được gán
            $table->foreignId('assigned_staff_id')->nullable()->constrained('users', 'user_id')->onDelete('set null');
            
            // Thông tin từ staff sau khi hoàn thành
            $table->string('proof_image')->nullable(); // Ảnh minh chứng từ staff
            $table->text('staff_notes')->nullable(); // Ghi chú từ staff
            
            // Thông tin từ admin
            $table->text('admin_notes')->nullable(); // Ghi chú từ admin khi duyệt/từ chối
            
            // Timestamps
            $table->timestamp('assigned_at')->nullable(); // Thời điểm gán cho staff
            $table->timestamp('staff_completed_at')->nullable(); // Thời điểm staff hoàn thành
            $table->timestamp('admin_reviewed_at')->nullable(); // Thời điểm admin duyệt/từ chối
            $table->timestamps();
            
            // Indexes để tối ưu truy vấn
            $table->index('status');
            $table->index('student_id');
            $table->index('assigned_staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trash_requests');
    }
};
