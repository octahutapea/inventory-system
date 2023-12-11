<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function index()
    {
        // fetching data from the api
        // $token = '2|Scp8YUCHITn98nY7xTZkYMejeoHsVCSUFyNHeTDH9853bcad';

        // $data = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $token,
        // ])->get('http://10.11.3.118:89/api/datapekerja');

        // $users = $data->json();

        // return response()->json([
        //     "success" => true,
        //     "message" => "User List",
        //     "data" => $users
        // ]);

        // $user = new User();
        // $data = $user->getUser();

        // return $data;

        $users = User::all();

        return response()->json([
            "success" => true,
            "message" => "User List",
            "data" => $users
        ]);
    }
}
