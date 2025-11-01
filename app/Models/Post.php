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
        'post_categories',
        'image',
        'author',
        'status',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Relationship với User model
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

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
