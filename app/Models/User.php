<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Http;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assigmentNumber',
        'name',
        'adusername',
        'email',
        'positionid',
        'positiontext',
        'personnelarea',
        'personnelareatext',
        'personnelsubarea',
        'personnelsubareatext',
        'departemenid',
        'departemenname1',
        'positionidsuperior',
        'companycode',
        'companyname',
        'password',
        'role',
        'prl_plan',

        // 'name',
        // 'position',
        // 'status',
        // 'category',
        // 'email',
        // 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    // public function getUser()
    // {
    //     // fetching data from the api
    //     $token = '2|Scp8YUCHITn98nY7xTZkYMejeoHsVCSUFyNHeTDH9853bcad';

    //     $data = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->get('http://10.11.3.118:89/api/datapekerja');

    //     $users = $data->json();

    //     return response()->json([
    //         $users
    //     ]);
    // }
}
