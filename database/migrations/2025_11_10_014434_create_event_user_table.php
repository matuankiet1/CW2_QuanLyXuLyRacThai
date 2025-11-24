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

            // FK tới users.user_id (custom primary key)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // FK tới events.id
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');

            // Thông tin sinh viên
            $table->string('name');
            $table->string('student_id');
            $table->string('student_class');
            $table->string('email');

            // Trạng thái tham gia
            $table->enum('status', ['pending', 'confirmed', 'canceled', 'attended'])
                ->default('pending');

            $table->datetime('registered_at')->nullable();
            $table->datetime('confirmed_at')->nullable();
            $table->datetime('attended_at')->nullable();

            $table->timestamps();

            // Một user chỉ đăng ký 1 lần / 1 event
            $table->unique(['user_id', 'event_id']);

            // Index
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
