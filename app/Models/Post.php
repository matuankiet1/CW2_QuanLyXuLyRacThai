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
        'author',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $slug = Str::slug($post->title);
                $originalSlug = $slug;
                $count = 1;

                while (Post::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                $post->slug = $slug;
            }
        });
    }
}
