<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản admin mặc định
        $this->call(DefaultAdminSeeder::class);
        
        // Tạo tài khoản test nếu cần
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'admin@school.edu',
        //     'password' => Hash::make('password'),
        // ]);
    }

    public function post(): void
    {
        $this->call(UserSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(CollectionScheduleSeeder::class);
    }

}
