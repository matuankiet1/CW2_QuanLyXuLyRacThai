<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';
    
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'reply',
        'reply_at',
    ];

    protected $casts = [
        'reply_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')->withDefault([
            'name' => 'Người dùng ẩn danh',
            'email' => 'unknown@example.com',
        ]);
    }

    public function isReplied()
    {
        return !is_null($this->reply);
    }
}