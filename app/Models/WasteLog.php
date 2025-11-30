<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WasteLog extends Model
{
    protected $fillable = [
        'schedule_id',
        'waste_type_id',
        'waste_weight',
        'waste_image',
        'status',
        'confirmed_by',
        'confirmed_at',
    ];

    protected static function booted()
    {
        static::deleting(function ($log) {
            if ($log->waste_image) {
                Storage::disk('public')->delete($log->waste_image);
            }
        });
    }

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];


    public function collectionSchedule()
    {
        return $this->belongsTo(CollectionSchedule::class, 'schedule_id', 'schedule_id');
    }

    public function wasteType()
    {
        return $this->belongsTo(WasteType::class, 'waste_type_id', 'id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by'); // nếu có cột created_by
    }
}
