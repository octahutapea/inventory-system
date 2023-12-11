<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserInventoryController extends Controller
{
    public function getInventoryById($id)
    {
        $items = DB::table('items')
            ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->select('acquisition_values.*', 'contracts.*', 'items.*')
            ->where('user_id', $id)
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Item List",
            "data" => $items
        ]);
    }
}
