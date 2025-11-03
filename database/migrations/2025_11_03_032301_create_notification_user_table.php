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
        Schema::create('notification_user', function (Blueprint $table) {
            $table->id('notification_user_id');
            $table->foreignId('notification_id')->constrained('notifications', 'notification_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->timestamp('read_at')->nullable(); // Thời gian đọc
            $table->timestamps();

            // Index để tăng tốc query
            $table->index(['user_id', 'read_at']);
            $table->index(['notification_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_user');
    }
};
