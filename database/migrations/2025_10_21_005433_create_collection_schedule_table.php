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
        Schema::create('collection_schedule', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->foreignId('staff_id')->constrained('users');
            $table->dateTime('scheduled_date');
            $table->string('status', 50)->default('Chưa thực hiện');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_schedule');
    }
};
