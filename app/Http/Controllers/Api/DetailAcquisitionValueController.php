<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailAcquisitionValueController extends Controller
{
    public function index($id)
    {
        $acquisition_values = DB::table('acquisition_values')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->select(
                'companies.company_name',
                'contracts.contract_number',
                'contracts.contract_start_date',
                'contracts.contract_end_date',
                'contracts.item_name',
                'acquisition_values.*'
            )
            ->where('acquisition_values.contract_id', $id)
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Acquisition Value List",
            "data" => $acquisition_values
        ]);
    }
}
