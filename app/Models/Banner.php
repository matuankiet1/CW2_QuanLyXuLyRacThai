<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'position',
        'status',
        'link',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the banner's position name
     */
    public function getPositionNameAttribute()
    {
        $positions = [
            'top' => 'Trang chủ - Top',
            'sidebar' => 'Sidebar',
            'footer' => 'Footer',
        ];

        return $positions[$this->position] ?? 'Unknown';
    }
}