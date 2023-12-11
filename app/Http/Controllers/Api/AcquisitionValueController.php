<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcquisitionValue;
use App\Exports\AcquisitionValueExport;
use App\Imports\AcquisitionValueImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AcquisitionValueController extends Controller
{
    public function index()
    {
        // $acquisition_values = AcquisitionValue::all();
        $acquisition_values = DB::table('acquisition_values')
            ->leftJoin('contracts', 'contracts.id', '=', 'acquisition_values.contract_id')
            ->leftJoin('companies', 'companies.id', '=', 'contracts.company_id')
            ->select(
                'companies.company_name',
                'contracts.contract_number',
                'contracts.contract_start_date',
                'contracts.contract_end_date',
                'contracts.item_name',
                'acquisition_values.*'
            )
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Acquisition Value List",
            "data" => $acquisition_values
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'contract_id' => 'required',
            'item_merk' => 'required',
            'item_type' => 'required',
            'item_total' => 'required',
            'item_value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Acquisition Value doesn't created.",
                "data" => $validator->errors()
            ]);
        }

        $acquisition_value = AcquisitionValue::create($input);

        return response()->json([
            "success" => true,
            "message" => "Acquisition Value created successfully.",
            "data" => $acquisition_value
        ]);
    }

    public function show($id)
    {
        $acquisition_value = AcquisitionValue::find($id);

        if (is_null($acquisition_value)) {
            return response()->json([
                "success" => false,
                "message" => "Acquisition Value not found."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Acquisition Value retrieved successfully.",
            "data" => $acquisition_value
        ]);
    }

    public function update($id, Request $request)
    {
        $acquisition_value = AcquisitionValue::find($id);

        if (is_null($acquisition_value)) {
            return response()->json([
                "success" => false,
                "message" => "Acquisition Value not found."
            ]);
        }

        $validator = Validator::make($request->all(), [
            'contract_id' => 'required',
            'item_merk' => 'required',
            'item_type' => 'required',
            'item_total' => 'required',
            'item_value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error",
                "data" => $validator->errors()
            ]);
        }

        $input = $request->all();

        $acquisition_value->contract_id = $input['contract_id'];
        $acquisition_value->item_merk = $input['item_merk'];
        $acquisition_value->item_type = $input['item_type'];
        $acquisition_value->item_total = $input['item_total'];
        $acquisition_value->item_value = $input['item_value'];
        $acquisition_value->save();

        return response()->json([
            "success" => true,
            "message" => "Acquisition Value updated successfully.",
            "data" => $acquisition_value
        ]);
    }

    public function destroy($id)
    {
        $acquisition_value = AcquisitionValue::find($id);

        if (is_null($acquisition_value)) {
            return response()->json([
                "success" => false,
                "message" => "Acquisition Value not found."
            ]);
        }

        $acquisition_value->delete();

        return response()->json([
            "success" => true,
            "message" => "Acquisition Value deleted successfully.",
            "data" => $acquisition_value
        ]);
    }

    public function export()
    {
        return Excel::download(new AcquisitionValueExport, 'acquisition_value.xlsx');
    }

    public function import()
    {
        Excel::import(new AcquisitionValueImport, request()->file('file'));

        return back();
    }
}
