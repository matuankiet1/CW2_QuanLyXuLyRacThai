<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionSchedule extends Model
{
    protected $primaryKey = 'schedule_id';

    protected $fillable = ['staff_id', 'scheduled_date', 'status', 'completed_at'];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
