<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    protected $fillable = [
        'role',
        'permission_id',
    ];

    /**
     * Relationship với Permission
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Lấy tất cả permissions của một role
     */
    public static function getPermissionsForRole(string $role): array
    {
        return static::where('role', $role)
            ->with('permission')
            ->get()
            ->pluck('permission.name')
            ->toArray();
    }

    /**
     * Gán permission cho role
     */
    public static function assignPermission(string $role, int $permissionId): bool
    {
        return static::firstOrCreate([
            'role' => $role,
            'permission_id' => $permissionId,
        ]);
    }

    /**
     * Thu hồi permission từ role
     */
    public static function revokePermission(string $role, int $permissionId): bool
    {
        return static::where('role', $role)
            ->where('permission_id', $permissionId)
            ->delete();
    }
}
