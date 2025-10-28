<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản admin mặc định nếu chưa tồn tại
        $adminExists = User::where('email', 'admin@ecowaste.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@ecowaste.com',
                'password' => Hash::make('Admin123!@#'),
                'role' => 'admin',
                'auth_provider' => 'local',
                'email_verified_at' => now(),
                'phone' => '0123456789',
            ]);
            
            $this->command->info('✅ Tài khoản Super Admin đã được tạo thành công!');
            $this->command->info('📧 Email: admin@ecowaste.com');
            $this->command->info('🔑 Mật khẩu: Admin123!@#');
            $this->command->info('⚠️  Vui lòng đổi mật khẩu sau khi đăng nhập lần đầu!');
        } else {
            $this->command->info('ℹ️  Tài khoản Super Admin đã tồn tại!');
        }
    }
}
