<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('waste_types')) {
            return;
        }

        Schema::table('waste_types', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('waste_types')) {
            return;
        }

        Schema::table('waste_types', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};

