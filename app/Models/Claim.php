<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'claim_location',
        'facility_type',
        'claim_imei',
        'claim_date',
        'claim_value',
        'claim_document',
        'claim_status',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
