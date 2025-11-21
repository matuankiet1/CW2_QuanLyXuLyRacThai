<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WasteLog extends Model
{
    protected $fillable = ['schedule_id', 'waste_type_id', 'waste_weight', 'waste_image'];

    protected static function booted()
    {
        static::deleting(function ($log) {
            if ($log->waste_image) {
                Storage::disk('public')->delete($log->waste_image);
            }
        });
    }

    public function collectionSchedule()
    {
        return $this->belongsTo(CollectionSchedule::class, 'schedule_id', 'schedule_id');
    }

    public function wasteType()
    {
        return $this->belongsTo(WasteType::class, 'waste_type_id', 'id');
    }
}
