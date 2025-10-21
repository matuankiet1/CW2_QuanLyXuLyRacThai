<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'author',
        'category',
        'publish_date',
        'status',
        'views',
        'excerpt',
        'content',
        'image_url'
    ];

    protected $dates = ['publish_date'];
}
