<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'description',
        'image',
    ];

      public function getStatusAttribute()
    {
        $today = Carbon::today();
        $register_end = Carbon::parse($this->register_end_date);
        $event_start = Carbon::parse($this->event_start_date);
        $event_end = Carbon::parse($this->event_end_date);

        if ($event_end->lt($today)) {
            return 'Kết thúc';
        } elseif ($event_start->lte($today) && $event_end->gte($today)) {
            return 'Đang diễn ra';
        } elseif ($register_end->gte($today) && $event_start->gt($today)) {
            return 'Đang đăng ký';
        } elseif ($register_end->lt($today) && $event_start->gt($today)) {
            return 'Hết đăng ký';
        } elseif ($event_start->gt($today)) {
            return 'Sắp diễn ra';
        } else {
            return 'Đang xử lý';
        }
    }
}
