<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_address',
        'company_telephone',
        'company_pic',
    ];

    public function contract()
    {
        return $this->hasMany(Contract::class);
    }
}
