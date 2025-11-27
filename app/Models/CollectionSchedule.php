<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CollectionSchedule extends Model
{
    use HasFactory;
    protected $primaryKey = 'schedule_id';

    protected $fillable = ['staff_id', 'scheduled_date', 'status', 'completed_at', 'confirmed_by', 'confirmed_at'];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function collectionSchedules()
    {
        return $this->hasMany(CollectionSchedule::class, 'staff_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'user_id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by', 'user_id');
    }

    public function wasteLogs()
    {
        return $this->hasMany(WasteLog::class, 'schedule_id', 'schedule_id');
    }

    public function report()
    {
        return $this->hasOne(CollectionReport::class, 'schedule_id', 'schedule_id');
    }
}
