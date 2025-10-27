<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CollectionSchedule extends Model
{
    use HasFactory;
    protected $primaryKey = 'schedule_id';

    protected $fillable = ['staff_id', 'scheduled_date', 'status', 'completed_at'];

    protected $casts = [
    'scheduled_date' => 'datetime',
    'completed_at' => 'datetime',
];

    public function collectionSchedules()
    {
        return $this->hasMany(\App\Models\CollectionSchedule::class, 'staff_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
