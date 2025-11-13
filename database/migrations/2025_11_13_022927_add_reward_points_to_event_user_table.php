<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm cột reward_points để lưu điểm thưởng cho sinh viên tham gia sự kiện
     */
    public function up(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            // Thêm cột điểm thưởng (mặc định 0, chỉ áp dụng cho sinh viên đã tham gia)
            $table->integer('reward_points')->default(0)->after('attended_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
            $table->dropColumn('reward_points');
        });
    }
};
