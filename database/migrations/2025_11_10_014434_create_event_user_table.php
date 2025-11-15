<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tạo bảng event_user (pivot table)
 * 
 * Mục đích:
 * - Lưu thông tin sinh viên đăng ký tham gia sự kiện
 * - Trạng thái: pending (chờ xác nhận), confirmed (đã xác nhận), 
 *   canceled (đã hủy), attended (đã tham gia)
 * - Lưu thời điểm đăng ký, xác nhận, điểm danh
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_user', function (Blueprint $table) {
            $table->id();
            
            // Foreign key đến bảng users (sinh viên)
            $table->foreignId('user_id')
                  ->constrained('users', 'user_id')
                  ->onDelete('cascade');
            
            // Foreign key đến bảng events (sự kiện)
            $table->foreignId('event_id')
                  ->constrained('events')
                  ->onDelete('cascade');
            
            // Trạng thái tham gia: pending, confirmed, canceled, attended
            $table->enum('status', ['pending', 'confirmed', 'canceled', 'attended'])
                  ->default('pending');
            
            // Thời điểm đăng ký
            $table->datetime('registered_at')->nullable();
            
            // Thời điểm xác nhận (bởi admin)
            $table->datetime('confirmed_at')->nullable();
            
            // Thời điểm điểm danh (bởi admin)
            $table->datetime('attended_at')->nullable();
            
            $table->timestamps();
            
            // Đảm bảo mỗi sinh viên chỉ đăng ký một lần cho mỗi sự kiện
            $table->unique(['user_id', 'event_id']);
            
            // Index để tăng hiệu suất truy vấn
            $table->index('status');
            $table->index('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_user');
    }
};
