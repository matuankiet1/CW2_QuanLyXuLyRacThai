<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi RoleSeeder trước tiên để tạo các vai trò
        $this->call([
            RoleSeeder::class,
        ]);

        // Bây giờ bạn có thể tạo User và Event mẫu
        \App\Models\User::factory(5)->create(); // Tạo 5 user mẫu
        \App\Models\Event::factory(10)->create(); // Tạo 10 sự kiện mẫu
    }
}