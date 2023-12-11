<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Exports\ContractExport;
use App\Imports\ContractImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function index()
    {
        // $contracts = Contract::all();
        $contracts = DB::table('contracts')
            ->leftJoin('companies', 'companies.id', '=', 'contracts.company_id')
            ->select('companies.*', 'contracts.*')
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Contract List",
            "data" => $contracts
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'company_id' => 'required',
            'contract_number' => 'required',
            'item_name' => 'required',
            'contract_start_date' => 'required',
            'contract_end_date' => 'required',
            'contract_value' => 'required',
            'contract_type' => 'required',
            'item_type' => 'required',
            'contract_note' => 'required',
            'contract_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Contract doesn't created.",
                "data" => $validator->errors()
            ]);
        }

        $contract = Contract::create($input);

        return response()->json([
            "success" => true,
            "message" => "Contract created successfully.",
            "data" => $contract
        ]);
    }

    public function show($id)
    {
        $contract = Contract::find($id);

        if (is_null($contract)) {
            return response()->json([
                "success" => false,
                "message" => "Contract not found."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Contract retrieved successfully.",
            "data" => $contract
        ]);
    }

    public function update($id, Request $request)
    {
        $contract = Contract::find($id);

        if (is_null($contract)) {
            return response()->json([
                "success" => false,
                "message" => "Contract not found."
            ]);
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'contract_number' => 'required',
            'item_name' => 'required',
            'contract_start_date' => 'required',
            'contract_end_date' => 'required',
            'contract_value' => 'required',
            'contract_type' => 'required',
            'item_type' => 'required',
            'contract_note' => 'required',
            'contract_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error",
                "data" => $validator->errors()
            ]);
        }

        $input = $request->all();

        $contract->company_id = $input['company_id'];
        $contract->contract_number = $input['contract_number'];
        $contract->item_name = $input['item_name'];
        $contract->contract_start_date = $input['contract_start_date'];
        $contract->contract_end_date = $input['contract_end_date'];
        $contract->contract_value = $input['contract_value'];
        $contract->contract_type = $input['contract_type'];
        $contract->item_type = $input['item_type'];
        $contract->contract_note = $input['contract_note'];
        $contract->contract_status = $input['contract_status'];
        $contract->save();

        return response()->json([
            "success" => true,
            "message" => "Contract updated successfully.",
            "data" => $contract
        ]);
    }

    public function destroy($id)
    {
        $contract = Contract::find($id);

        if (is_null($contract)) {
            return response()->json([
                "success" => false,
                "message" => "Contract not found."
            ]);
        }

        $contract->delete();

        return response()->json([
            "success" => true,
            "message" => "Contract deleted successfully.",
            "data" => $contract
        ]);
    }

    public function export()
    {
        return Excel::download(new ContractExport, 'contract.xlsx');
    }

    public function import()
    {
        Excel::import(new ContractImport, request()->file('file'));

        return back();
    }
}
