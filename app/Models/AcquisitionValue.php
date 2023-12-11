<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcquisitionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'item_merk',
        'item_type',
        'item_total',
        'item_value',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function item()
    {
        return $this->hasMany(Item::class);
    }
}
