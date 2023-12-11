<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'acquisition_value_id',
        'item_area',
        'item_location',
        'serial_number',
        'item_status',
        'item_pict'
    ];

    public function acquisitionValue()
    {
        return $this->belongsTo(AcquisitionValue::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
