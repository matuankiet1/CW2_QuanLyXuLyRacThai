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
            $table->date('date'); // Ngày tổ chức
            $table->string('location'); // Địa điểm
            $table->integer('participants')->default(0); // Số người tham gia
            $table->integer('waste')->default(0); // Lượng rác thu gom (kg)
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
