<?php

namespace App\Support;

class RoleRedirector
{
    /**
     * Lấy tên route tương ứng với trang chủ của từng role.
     */
    public static function homeRoute(?string $role): string
    {
        return match ($role) {
            'admin' => 'admin.home',
            'staff' => 'staff.dashboard',
            'student' => 'student.dashboard',
            default => 'home',
        };
    }

    /**
     * Lấy URL đầy đủ của trang chủ dựa trên role.
     */
    public static function homeUrl(?string $role): string
    {
        return route(self::homeRoute($role));
    }
}

