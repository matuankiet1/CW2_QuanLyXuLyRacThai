<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Chỉ định tên khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'user_id'; // <-- Dòng quan trọng nhất để sửa lỗi

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
<<<<<<< Updated upstream
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
=======
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Lấy mật khẩu cho người dùng (chỉ cho Laravel biết cột mật khẩu đúng).
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
>>>>>>> Stashed changes
    }
}
