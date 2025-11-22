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
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_user', function (Blueprint $table) {
            $table->id();

            // FK tới users.id
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // FK tới events.id
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');

            // Thông tin sinh viên (nếu cần)
            $table->string('name');
            $table->string('mssv');
            $table->string('class');
            $table->string('email');

            // Trạng thái tham gia
            $table->enum('status', ['pending', 'confirmed', 'canceled', 'attended'])
                ->default('pending');

            $table->datetime('registered_at')->nullable();
            $table->datetime('confirmed_at')->nullable();
            $table->datetime('attended_at')->nullable();

            $table->timestamps();

            // Một user chỉ đăng ký một lần / event
            $table->unique(['user_id', 'event_id']);

            // Indexes
            $table->index('status');
            $table->index('event_id');
            $table->index(['event_id', 'status']);
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
