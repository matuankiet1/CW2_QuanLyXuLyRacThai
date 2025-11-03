<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->foreignId('sender_id')->constrained('users', 'user_id')->onDelete('cascade'); // Người gửi (admin/giảng viên)
            $table->string('title'); // Tiêu đề thông báo
            $table->text('content'); // Nội dung
            $table->enum('type', ['announcement', 'academic', 'event', 'urgent'])->default('announcement'); // Loại thông báo
            $table->string('attachment')->nullable(); // File đính kèm (đường dẫn)
            $table->enum('send_to_type', ['all', 'role', 'user'])->default('all'); // Gửi đến: tất cả, theo role, hoặc user cụ thể
            $table->string('target_role')->nullable(); // Nếu send_to_type = 'role', lưu role nào (admin/user)
            $table->enum('status', ['draft', 'sent', 'scheduled'])->default('sent'); // Trạng thái
            $table->timestamp('scheduled_at')->nullable(); // Thời gian hẹn gửi
            $table->timestamp('sent_at')->nullable(); // Thời gian đã gửi
            $table->integer('total_recipients')->default(0); // Tổng số người nhận
            $table->integer('read_count')->default(0); // Số người đã đọc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
