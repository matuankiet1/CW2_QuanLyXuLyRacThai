<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'register_date',
        'register_end_date',
        'event_start_date',
        'event_end_date',
        'location',
        'participants',
        'status',
        'description',
        'image',
    ];
}
