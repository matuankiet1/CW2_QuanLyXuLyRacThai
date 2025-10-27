<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Kiểm tra xem admin đã tồn tại chưa
        $adminExists = User::where('email', 'admin@admin.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'auth_provider' => 'local',
            ]);
            
            $this->command->info('Tài khoản admin đã được tạo!');
            $this->command->info('Email: admin@admin.com');
            $this->command->info('Mật khẩu: password123');
        } else {
            $this->command->info('Tài khoản admin đã tồn tại!');
        }
    }
}
