<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('collection_schedules')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            try {
                DB::statement('
                    CREATE TABLE collection_schedules_new (
                        schedule_id INTEGER PRIMARY KEY AUTOINCREMENT,
                        staff_id INTEGER NOT NULL,
                        scheduled_date DATETIME NOT NULL,
                        status TEXT DEFAULT \'Chưa thực hiện\',
                        completed_at DATETIME,
                        created_at DATETIME,
                        updated_at DATETIME,
                        FOREIGN KEY (staff_id) REFERENCES users(user_id) ON DELETE CASCADE
                    )
                ');

                DB::statement('
                    INSERT INTO collection_schedules_new (
                        schedule_id, staff_id, scheduled_date, status,
                        completed_at, created_at, updated_at
                    )
                    SELECT
                        schedule_id, staff_id, scheduled_date, status,
                        completed_at, created_at, updated_at
                    FROM collection_schedules
                ');

                DB::statement('DROP TABLE collection_schedules');
                DB::statement('ALTER TABLE collection_schedules_new RENAME TO collection_schedules');
                DB::statement('CREATE INDEX collection_schedules_staff_id_index ON collection_schedules(staff_id)');
            } finally {
                DB::statement('PRAGMA foreign_keys = ON');
            }

            return;
        }

        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->foreign('staff_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('collection_schedules')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            try {
                DB::statement('
                    CREATE TABLE collection_schedules_old (
                        schedule_id INTEGER PRIMARY KEY AUTOINCREMENT,
                        staff_id INTEGER NOT NULL,
                        scheduled_date DATETIME NOT NULL,
                        status TEXT DEFAULT \'Chưa thực hiện\',
                        completed_at DATETIME,
                        created_at DATETIME,
                        updated_at DATETIME,
                        FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE CASCADE
                    )
                ');

                DB::statement('
                    INSERT INTO collection_schedules_old (
                        schedule_id, staff_id, scheduled_date, status,
                        completed_at, created_at, updated_at
                    )
                    SELECT
                        schedule_id, staff_id, scheduled_date, status,
                        completed_at, created_at, updated_at
                    FROM collection_schedules
                ');

                DB::statement('DROP TABLE collection_schedules');
                DB::statement('ALTER TABLE collection_schedules_old RENAME TO collection_schedules');
                DB::statement('CREATE INDEX collection_schedules_staff_id_index ON collection_schedules(staff_id)');
            } finally {
                DB::statement('PRAGMA foreign_keys = ON');
            }

            return;
        }

        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->foreign('staff_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};

