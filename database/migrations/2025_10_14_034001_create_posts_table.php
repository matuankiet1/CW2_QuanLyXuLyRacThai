<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề bài viết
            $table->string('slug')->unique(); // Đường dẫn thân thiện SEO
            $table->text('excerpt')->nullable(); // Mô tả ngắn
            $table->longText('content'); // Nội dung chính
            $table->string('image')->nullable(); // Ảnh đại diện
            $table->string('author')->default('Admin'); // Tác giả
            $table->enum('status', ['draft', 'published'])->default('published'); // Trạng thái
            $table->timestamp('published_at')->nullable(); // Ngày đăng
            $table->timestamps(); // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
