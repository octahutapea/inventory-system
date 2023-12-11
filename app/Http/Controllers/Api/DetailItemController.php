<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailItemController extends Controller
{
    public function index($id)
    {
        // $acquisition_values = AcquisitionValue::all();
        $items = DB::table('items')
            ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('acquisition_values.item_merk', 'acquisition_values.item_type', 'contracts.item_name', 'companies.company_name', 'items.*', 'users.name', 'users.positiontext')
            ->where('items.acquisition_value_id', $id)
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Item List",
            "data" => $items
        ]);
    }
}
