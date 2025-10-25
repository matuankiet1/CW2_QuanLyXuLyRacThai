<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'image_url',
        'author',
        'status',
        'category',
        'published_at',
        'publish_date',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'publish_date' => 'date',
    ];

    // Tự động tạo slug khi tạo mới
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }
}
