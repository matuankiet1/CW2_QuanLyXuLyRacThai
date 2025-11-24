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
        Schema::create('collection_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('staff_id');
            $table->decimal('total_weight', 8, 2)->default(0);
            $table->decimal('organic_weight', 8, 2)->nullable();
            $table->decimal('recyclable_weight', 8, 2)->nullable();
            $table->decimal('hazardous_weight', 8, 2)->nullable();
            $table->decimal('other_weight', 8, 2)->nullable();
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('schedule_id')
                ->references('schedule_id')
                ->on('collection_schedules')
                ->onDelete('cascade');
            $table->foreign('staff_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('approved_by')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_reports');
    }
};

