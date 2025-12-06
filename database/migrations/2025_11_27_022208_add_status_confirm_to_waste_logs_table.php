<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('waste_logs', function (Blueprint $table) {
            // Thêm cột trạng thái, mặc định "Chưa xác nhận"
            $table->string('status')->default('Chưa xác nhận')->after('waste_image');

            // Thêm cột xác nhận bởi user (nullable)
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete()->after('status');

            // Thêm cột thời gian xác nhận
            $table->timestamp('confirmed_at')->nullable()->after('confirmed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_logs', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['status', 'confirmed_by', 'confirmed_at']);
        });
    }
};
