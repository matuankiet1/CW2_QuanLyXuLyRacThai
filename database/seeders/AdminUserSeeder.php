<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo admin user mặc định
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@school.edu',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'auth_provider' => 'local',
            'email_verified_at' => now(),
        ]);
    }
}
