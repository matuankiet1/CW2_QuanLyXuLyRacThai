<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteLog extends Model
{
    protected $fillable = ['schedule_id','waste_type_id', 'waste_weight', 'waste_image'];
}
