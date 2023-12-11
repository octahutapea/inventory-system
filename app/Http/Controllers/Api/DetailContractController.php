<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;

class DetailContractController extends Controller
{
    public function index($id)
    {
        // $contracts = Contract::all();
        $contracts = DB::table('contracts')
            ->leftJoin('companies', 'companies.id', '=', 'contracts.company_id')
            ->select('companies.*', 'contracts.*')
            ->where('contracts.company_id', $id)
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Contract List",
            "data" => $contracts
        ]);
    }
}
