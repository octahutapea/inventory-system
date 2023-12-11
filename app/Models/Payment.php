<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'period',
        'payment_note',
        'period_start',
        'period_end',
        'details',
        'total',
        'invoice',
        'payment_status'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
