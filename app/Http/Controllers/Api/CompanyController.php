<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Exports\CompanyExport;
use App\Imports\CompanyImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = DB::table('companies')
            ->leftJoin('contracts', 'contracts.company_id', '=', 'companies.id')
            ->select('companies.id', 'companies.company_name', 'companies.company_address', 'companies.company_telephone', 'companies.company_pic', DB::raw('COUNT(contracts.id) as contractCount'))
            ->groupBy('companies.id', 'companies.company_name', 'companies.company_address', 'companies.company_telephone', 'companies.company_pic')
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Company List",
            "data" => $companies
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'company_name' => 'required',
            'company_address' => 'required',
            'company_telephone' => 'required',
            'company_pic' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Company doesn't created.",
                "data" => $validator->errors()
            ]);
        }

        $company = Company::create($input);

        return response()->json([
            "success" => true,
            "message" => "Company created successfully.",
            "data" => $company
        ]);
    }

    public function show($id)
    {
        $company = Company::find($id);

        if (is_null($company)) {
            return response()->json([
                "success" => false,
                "message" => "Company not found."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Company retrieved successfully.",
            "data" => $company
        ]);
    }

    public function update($id, Request $request)
    {
        $company = Company::find($id);

        if (is_null($company)) {
            return response()->json([
                "success" => false,
                "message" => "Company not found."
            ]);
        }

        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'company_address' => 'required',
            'company_telephone' => 'required',
            'company_pic' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error",
                "data" => $validator->errors()
            ]);
        }

        $input = $request->all();

        $company->company_name = $input['company_name'];
        $company->company_address = $input['company_address'];
        $company->company_telephone = $input['company_telephone'];
        $company->company_pic = $input['company_pic'];
        $company->save();

        return response()->json([
            "success" => true,
            "message" => "Company updated successfully.",
            "data" => $company
        ]);
    }

    public function destroy($id)
    {
        $company = Company::find($id);

        if (is_null($company)) {
            return response()->json([
                "success" => false,
                "message" => "Company not found."
            ]);
        }

        $company->delete();

        return response()->json([
            "success" => true,
            "message" => "Company deleted successfully.",
            "data" => $company
        ]);
    }

    public function export()
    {
        return Excel::download(new CompanyExport, 'company.xlsx');
    }

    public function import()
    {
        Excel::import(new CompanyImport, request()->file('file'));

        return back();
    }
}
