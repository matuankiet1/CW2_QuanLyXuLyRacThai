<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Kiểm tra xem bảng đã tồn tại chưa
        if (!Schema::hasTable('collection_reports')) {
        Schema::create('collection_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('collection_schedules')->onDelete('cascade');
            $table->decimal('total_weight', 10, 2)->default(0);
            $table->decimal('organic_weight', 10, 2)->default(0);
            $table->decimal('recyclable_weight', 10, 2)->default(0);
            $table->decimal('hazardous_weight', 10, 2)->default(0);
            $table->decimal('other_weight', 10, 2)->default(0);
            $table->text('notes')->nullable();
                $table->string('photo_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
        } else {
            // Nếu bảng đã tồn tại, chỉ cập nhật các cột nếu cần
            // Cập nhật precision của các cột weight từ 8,2 sang 10,2
            if (DB::getDriverName() !== 'sqlite') {
                Schema::table('collection_reports', function (Blueprint $table) {
                    $table->decimal('total_weight', 10, 2)->default(0)->change();
                    $table->decimal('organic_weight', 10, 2)->nullable()->change();
                    $table->decimal('recyclable_weight', 10, 2)->nullable()->change();
                    $table->decimal('hazardous_weight', 10, 2)->nullable()->change();
                    $table->decimal('other_weight', 10, 2)->nullable()->change();
                });
            }
        }
    }

    public function down(): void
    {
        // Không làm gì vì migration này chỉ để đảm bảo bảng tồn tại
    }
};
