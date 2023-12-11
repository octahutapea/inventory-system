<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'contract_number',
        'item_name',
        'contract_start_date',
        'contract_end_date',
        'contract_value',
        'item_type',
        'contract_type',
        'contract_note',
        'contract_status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function acquisitionValue()
    {
        return $this->hasMany(AcquisitionValue::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
}
