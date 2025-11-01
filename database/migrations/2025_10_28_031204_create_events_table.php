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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tên sự kiện
            $table->date('register_date'); // Ngày bắt đầu đăng ký tham gia sự kiện
            $table->date('register_end_date'); // Ngày kết thúc đăng ký tham gia sự kiện
            $table->date('event_start_date'); // Ngày bắt đầu sự kiện
            $table->date('event_end_date'); // Ngày kết thúc đăng ký tham gia sự kiện
            $table->string('location'); // Địa điểm
            $table->integer('participants')->default(0); // Số người tham gia
            $table->enum('status', ['upcoming', 'completed'])->default('upcoming'); // Trạng thái
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
