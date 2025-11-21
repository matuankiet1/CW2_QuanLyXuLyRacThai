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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Ví dụ: 'view_dashboard', 'manage_users'
            $table->string('display_name'); // Ví dụ: 'Xem Dashboard', 'Quản lý người dùng'
            $table->string('description')->nullable(); // Mô tả chi tiết về quyền
            $table->string('category')->default('general'); // Phân loại: dashboard, users, events, reports, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
