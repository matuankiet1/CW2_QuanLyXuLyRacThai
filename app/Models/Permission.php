<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'category',
    ];

    /**
     * Lấy tất cả roles có permission này
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            RolePermission::class,
            'role_permissions',
            'permission_id',
            'role',
            'id',
            'role'
        );
    }

    /**
     * Kiểm tra role có permission này không
     */
    public function hasRole(string $role): bool
    {
        return RolePermission::where('role', $role)
            ->where('permission_id', $this->id)
            ->exists();
    }
}
