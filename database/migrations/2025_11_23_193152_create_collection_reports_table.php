<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('collection_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('collection_schedules')->onDelete('cascade');
            $table->decimal('total_weight', 10, 2)->default(0);
            $table->decimal('organic_weight', 10, 2)->default(0);
            $table->decimal('recyclable_weight', 10, 2)->default(0);
            $table->decimal('hazardous_weight', 10, 2)->default(0);
            $table->decimal('other_weight', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable(); // hoặc JSON nếu nhiều ảnh
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_reports');
    }
};
