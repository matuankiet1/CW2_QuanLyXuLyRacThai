<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteType extends Model
{
    protected $fillable = ['name'];

    public function wasteLogs()
    {
        return $this->hasMany(WasteLog::class, 'waste_type_id', 'id');
    }
}
