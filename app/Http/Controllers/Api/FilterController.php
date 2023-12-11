<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AcquisitionValue;
use App\Models\Claim;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Item;
use App\Models\User;

class FilterController extends Controller
{
    public function index()
    {
        $allData = DB::table('items')
            ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('acquisition_values.*', 'contracts.*', 'companies.*', 'items.*', 'users.*')
            ->count();
    }

    public function itemSearch($keyword)
    {

        // Pastikan $keyword tidak kosong sebelum melakukan pencarian
        if ($keyword) {
            $items = DB::table('items')
                ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
                ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
                ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
                ->leftJoin('users', 'items.user_id', '=', 'users.id')
                ->select('acquisition_values.item_merk', 'acquisition_values.item_type', 'contracts.item_name', 'companies.company_name', 'items.*', 'users.name', 'users.positiontext')
                ->where('contracts.item_name', 'like', "%$keyword%")
                ->orWhere('acquisition_values.item_merk', 'like', "%$keyword%")
                ->orWhere('acquisition_values.item_type', 'like', "%$keyword%")
                ->orWhere('users.name', 'like', "%$keyword%")
                ->orWhere('items.item_location', 'like', "%$keyword%")
                ->orWhere('items.item_area', 'like', "%$keyword%")
                ->get();

            $items_count = DB::table('items')
                ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
                ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
                ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
                ->leftJoin('users', 'items.user_id', '=', 'users.id')
                ->select('acquisition_values.item_merk', 'acquisition_values.item_type', 'contracts.item_name', 'companies.company_name', 'items.*', 'users.name', 'users.positiontext')
                ->where('contracts.item_name', 'like', "%$keyword%")
                ->orWhere('acquisition_values.item_merk', 'like', "%$keyword%")
                ->orWhere('acquisition_values.item_type', 'like', "%$keyword%")
                ->orWhere('users.name', 'like', "%$keyword%")
                ->orWhere('items.item_location', 'like', "%$keyword%")
                ->orWhere('items.item_area', 'like', "%$keyword%")
                ->count();

            return response()->json([
                "success" => true,
                "message" => "Item List",
                "data" => $items,
                "count" => $items_count
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Keyword is required for search."
            ]);
        }
    }

    public function acquisitionValueSearch($keyword)
    {

        // Pastikan $keyword tidak kosong sebelum melakukan pencarian
        if ($keyword) {
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
                ->where('companies.company_name', 'like', "%$keyword%")
                ->orWhere('contracts.contract_number', 'like', "%$keyword%")
                ->orWhere('contracts.contract_start_date', 'like', "%$keyword%")
                ->orWhere('contracts.contract_end_date', 'like', "%$keyword%")
                ->orWhere('contracts.item_name', 'like', "%$keyword%")
                ->orWhere('acquisition_values.item_merk', 'like', "%$keyword%")
                ->orWhere('acquisition_values.item_type', 'like', "%$keyword%")
                ->get();

            $acquisition_value_count = DB::table('acquisition_values')
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
                ->where('companies.company_name', 'like', "%$keyword%")
                ->orWhere('contracts.contract_number', 'like', "%$keyword%")
                ->orWhere('contracts.contract_start_date', 'like', "%$keyword%")
                ->orWhere('contracts.contract_end_date', 'like', "%$keyword%")
                ->orWhere('contracts.item_name', 'like', "%$keyword%")
                ->orWhere('acquisition_values.item_merk', 'like', "%$keyword%")
                ->orWhere('acquisition_values.item_type', 'like', "%$keyword%")
                ->count();

            return response()->json([
                "success" => true,
                "message" => "Item List",
                "data" => $acquisition_values,
                "count" => $acquisition_value_count
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Keyword is required for search."
            ]);
        }
    }

    public function contractSearch($keyword)
    {

        // Pastikan $keyword tidak kosong sebelum melakukan pencarian
        if ($keyword) {
            $contracts = DB::table('contracts')
                ->leftJoin('companies', 'companies.id', '=', 'contracts.company_id')
                ->select('companies.*', 'contracts.*')
                ->where('contracts.contract_type', 'like', "%$keyword%")
                ->orWhere('contracts.contract_number', 'like', "%$keyword%")
                ->orWhere('contracts.contract_start_date', 'like', "%$keyword%")
                ->orWhere('contracts.contract_end_date', 'like', "%$keyword%")
                ->orWhere('contracts.item_name', 'like', "%$keyword%")
                ->orWhere('contracts.item_type', 'like', "%$keyword%")
                ->orWhere('contracts.contract_status', 'like', "%$keyword%")
                ->get();

            $contract_count = DB::table('contracts')
                ->leftJoin('companies', 'companies.id', '=', 'contracts.company_id')
                ->select('companies.*', 'contracts.*')
                ->where('contracts.contract_type', 'like', "%$keyword%")
                ->orWhere('contracts.contract_number', 'like', "%$keyword%")
                ->orWhere('contracts.contract_start_date', 'like', "%$keyword%")
                ->orWhere('contracts.contract_end_date', 'like', "%$keyword%")
                ->orWhere('contracts.item_name', 'like', "%$keyword%")
                ->orWhere('contracts.item_type', 'like', "%$keyword%")
                ->orWhere('contracts.contract_status', 'like', "%$keyword%")
                ->count();

            return response()->json([
                "success" => true,
                "message" => "Item List",
                "data" => $contracts,
                "count" => $contract_count
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Keyword is required for search."
            ]);
        }
    }

    public function companySearch($keyword)
    {

        // Pastikan $keyword tidak kosong sebelum melakukan pencarian
        if ($keyword) {
            $companies = DB::table('companies')
                ->leftJoin('contracts', 'contracts.company_id', '=', 'companies.id')
                ->select('companies.id', 'companies.company_name', 'companies.company_address', 'companies.company_telephone', 'companies.company_pic', DB::raw('COUNT(contracts.id) as contractCount'))
                ->groupBy('companies.id', 'companies.company_name', 'companies.company_address', 'companies.company_telephone', 'companies.company_pic')
                ->where('companies.company_name', 'like', "%$keyword%")
                ->orWhere('companies.company_address', 'like', "%$keyword%")
                ->orWhere('companies.company_telephone', 'like', "%$keyword%")
                ->orWhere('companies.company_pic', 'like', "%$keyword%")
                ->get();

            $company_count = DB::table('companies')
                ->leftJoin('contracts', 'contracts.company_id', '=', 'companies.id')
                ->select('companies.id', 'companies.company_name', 'companies.company_address', 'companies.company_telephone', 'companies.company_pic', DB::raw('COUNT(contracts.id) as contractCount'))
                ->groupBy('companies.id', 'companies.company_name', 'companies.company_address', 'companies.company_telephone', 'companies.company_pic')
                ->where('companies.company_name', 'like', "%$keyword%")
                ->orWhere('companies.company_address', 'like', "%$keyword%")
                ->orWhere('companies.company_telephone', 'like', "%$keyword%")
                ->orWhere('companies.company_pic', 'like', "%$keyword%")
                ->count();

            return response()->json([
                "success" => true,
                "message" => "Item List",
                "data" => $companies,
                "count" => $company_count
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Keyword is required for search."
            ]);
        }
    }

    public function claimSearch($keyword)
    {

        // Pastikan $keyword tidak kosong sebelum melakukan pencarian
        if ($keyword) {
            $claims = DB::table('claims')
                ->leftJoin('users', 'users.id', '=', 'claims.user_id')
                ->select('claims.*', 'users.name', 'users.positiontext')
                ->where('users.name', 'like', "%$keyword%")
                ->orWhere('users.positiontext', 'like', "%$keyword%")
                ->orWhere('claims.claim_location', 'like', "%$keyword%")
                ->orWhere('claims.facility_type', 'like', "%$keyword%")
                ->orWhere('claims.claim_imei', 'like', "%$keyword%")
                ->orWhere('claims.claim_date', 'like', "%$keyword%")
                ->orWhere('claims.claim_status', 'like', "%$keyword%")
                ->get();

            $claim_count = DB::table('claims')
                ->leftJoin('users', 'users.id', '=', 'claims.user_id')
                ->select('claims.*', 'users.name', 'users.positiontext')
                ->where('users.name', 'like', "%$keyword%")
                ->orWhere('users.positiontext', 'like', "%$keyword%")
                ->orWhere('claims.claim_location', 'like', "%$keyword%")
                ->orWhere('claims.facility_type', 'like', "%$keyword%")
                ->orWhere('claims.claim_imei', 'like', "%$keyword%")
                ->orWhere('claims.claim_date', 'like', "%$keyword%")
                ->orWhere('claims.claim_status', 'like', "%$keyword%")
                ->count();

            return response()->json([
                "success" => true,
                "message" => "Item List",
                "data" => $claims,
                "count" => $claim_count
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Keyword is required for search."
            ]);
        }
    }

    public function requestSearch($keyword)
    {

        // Pastikan $keyword tidak kosong sebelum melakukan pencarian
        if ($keyword) {
            $claims = DB::table('claims')
                ->leftJoin('users', 'users.id', '=', 'claims.user_id')
                ->select('claims.*', 'users.name', 'users.positiontext')
                ->where('claims.claim_status', 'Proses Admin')
                ->where(function ($query) use ($keyword) {
                    $query->where('users.name', 'like', "%$keyword%")
                        ->orWhere('users.positiontext', 'like', "%$keyword%")
                        ->orWhere('claims.claim_location', 'like', "%$keyword%")
                        ->orWhere('claims.facility_type', 'like', "%$keyword%")
                        ->orWhere('claims.claim_imei', 'like', "%$keyword%")
                        ->orWhere('claims.claim_date', 'like', "%$keyword%");
                })
                ->get();

            $claim_count = DB::table('claims')
                ->leftJoin('users', 'users.id', '=', 'claims.user_id')
                ->select('claims.*', 'users.name', 'users.positiontext')
                ->where('claims.claim_status', 'Proses Admin')
                ->where(function ($query) use ($keyword) {
                    $query->where('users.name', 'like', "%$keyword%")
                        ->orWhere('users.positiontext', 'like', "%$keyword%")
                        ->orWhere('claims.claim_location', 'like', "%$keyword%")
                        ->orWhere('claims.facility_type', 'like', "%$keyword%")
                        ->orWhere('claims.claim_imei', 'like', "%$keyword%")
                        ->orWhere('claims.claim_date', 'like', "%$keyword%");
                })
                ->count();

            return response()->json([
                "success" => true,
                "message" => "Item List",
                "data" => $claims,
                "count" => $claim_count
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Keyword is required for search."
            ]);
        }
    }

    public function claimProcessSearch($keyword)
    {

        // Pastikan $keyword tidak kosong sebelum melakukan pencarian
        if ($keyword) {
            $claims = DB::table('claims')
                ->leftJoin('users', 'users.id', '=', 'claims.user_id')
                ->select('claims.*', 'users.name', 'users.positiontext')
                ->where('claims.claim_status', 'Proses HC')
                ->where(function ($query) use ($keyword) {
                    $query->where('users.name', 'like', "%$keyword%")
                        ->orWhere('users.positiontext', 'like', "%$keyword%")
                        ->orWhere('claims.claim_location', 'like', "%$keyword%")
                        ->orWhere('claims.facility_type', 'like', "%$keyword%")
                        ->orWhere('claims.claim_imei', 'like', "%$keyword%")
                        ->orWhere('claims.claim_date', 'like', "%$keyword%");
                })
                ->get();

            $claim_count = DB::table('claims')
                ->leftJoin('users', 'users.id', '=', 'claims.user_id')
                ->select('claims.*', 'users.name', 'users.positiontext')
                ->where('claims.claim_status', 'Proses HC')
                ->where(function ($query) use ($keyword) {
                    $query->where('users.name', 'like', "%$keyword%")
                        ->orWhere('users.positiontext', 'like', "%$keyword%")
                        ->orWhere('claims.claim_location', 'like', "%$keyword%")
                        ->orWhere('claims.facility_type', 'like', "%$keyword%")
                        ->orWhere('claims.claim_imei', 'like', "%$keyword%")
                        ->orWhere('claims.claim_date', 'like', "%$keyword%");
                })
                ->count();

            return response()->json([
                "success" => true,
                "message" => "Item List",
                "data" => $claims,
                "count" => $claim_count
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Keyword is required for search."
            ]);
        }
    }
}
