<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Exports\ClaimExport;
use App\Exports\ClaimProcessExport;
use App\Imports\ClaimImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    public function index()
    {
        // $claims = Claim::all();

        $claims = DB::table('claims')
            ->leftJoin('users', 'users.id', '=', 'claims.user_id')
            ->select('claims.*', 'users.name', 'users.positiontext', 'users.prl_plan')
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Claim List",
            "data" => $claims
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'claim_location' => 'required',
            'facility_type' => 'required',
            'claim_imei' => 'required',
            'claim_date' => 'required',
            'claim_value' => 'required',
            'claim_document' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'claim_status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Claim doesn't created.",
                "data" => $validator->errors()
            ]);
        }

        if ($request->hasFile('claim_document')) {
            $file = $request->file('claim_document');

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameSave = $filename . '_' . time() . '.' . $extension;

            // Store the file in the 'public/claim_document' directory
            $path = $file->storeAs('public/claim_document', $filenameSave);
        } else {
            $filenameSave = 'nodocument.pdf';
        }

        // Update the 'claim_document' property in $input
        $input['claim_document'] = $filenameSave;

        // Create a new Claim instance with updated $input
        $claim = Claim::create($input);

        return response()->json([
            "success" => true,
            "message" => "Claim created successfully.",
            "data" => $claim
        ]);
    }

    public function show($id)
    {
        $claim = Claim::find($id);

        if (is_null($claim)) {
            return response()->json([
                "success" => false,
                "message" => "Claim not found."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Claim retrieved successfully.",
            "data" => $claim
        ]);
    }

    public function update($id, Request $request)
    {
        $claim = Claim::find($id);

        if (is_null($claim)) {
            return response()->json([
                "success" => false,
                "message" => "Claim not found."
            ]);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'claim_location' => 'required',
            'facility_type' => 'required',
            'claim_imei' => 'required',
            'claim_date' => 'required',
            'claim_value' => 'required',
            'claim_document' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'claim_status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error",
                "data" => $validator->errors()
            ]);
        }

        $input = $request->all();

        if ($request->hasFile('claim_document')) {
            $file = $request->file('claim_document');

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameSave = $filename . '_' . time() . '.' . $extension;

            // Store the file in the 'public/claim_document' directory
            $path = $file->storeAs('public/claim_document', $filenameSave);

            $input['claim_document'] = $filenameSave;
        } else {
            $filenameSave = 'nodocument.pdf';
        }

        $claim->user_id = $input['user_id'];
        $claim->claim_location = $input['claim_location'];
        $claim->facility_type = $input['facility_type'];
        $claim->claim_imei = $input['claim_imei'];
        $claim->claim_date = $input['claim_date'];
        $claim->claim_value = $input['claim_value'];
        $claim->claim_document = $input['claim_document'];
        $claim->claim_status = $input['claim_status'];
        $claim->save();

        return response()->json([
            "success" => true,
            "message" => "Claim updated successfully.",
            "data" => $claim
        ]);
    }

    public function destroy($id)
    {
        $claim = Claim::find($id);

        if (is_null($claim)) {
            return response()->json([
                "success" => false,
                "message" => "Claim not found."
            ]);
        }

        $claim->delete();

        return response()->json([
            "success" => true,
            "message" => "Claim deleted successfully.",
            "data" => $claim
        ]);
    }

    public function openDocument($filename)
    {
        $path = storage_path('app/public/claim_document/' . $filename);

        if (!Storage::exists('public/claim_document/' . $filename)) {
            abort(404);
        }

        // Menggunakan response dengan header content-type yang sesuai
        return response()->file($path, ['Content-Type' => mime_content_type($path)]);
    }

    public function export()
    {
        return Excel::download(new ClaimExport, 'claim.xlsx');
    }

    public function claimProcessExport()
    {
        return Excel::download(new ClaimProcessExport, 'claim.xlsx');
    }

    public function import()
    {
        Excel::import(new ClaimImport, request()->file('file'));

        return back();
    }
}
