<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    public function index()
    {
        $areas = ['HO', 'NSA', 'DDA', 'ORA', 'CSA', 'SSA', 'WJA', 'EJA', 'KAL'];

        $responseData = [];

        foreach ($areas as $area) {
            $itemCounts = DB::table('items')
                ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
                ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
                ->where('items.item_area', $area)
                ->select('contracts.item_name', 'items.item_area', DB::raw('COUNT(*) as count'))
                ->groupBy('contracts.item_name', 'items.item_area')
                ->get();

            foreach ($itemCounts as $itemCount) {
                $itemName = $itemCount->item_name;
                if (!isset($responseData[$itemName])) {
                    $responseData[$itemName] = [
                        'item_name' => $itemName,
                        'areas' => [],
                    ];
                }

                // $hardwareCounts = DB::table('items')
                //     ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
                //     ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
                //     ->where('items.item_area', $area)
                //     ->where('contracts.item_type', 'Hardware')
                //     ->select('contracts.item_name', 'contracts.item_type', 'items.item_area', DB::raw('COUNT(*) as count'))
                //     ->groupBy('contracts.item_name', 'contracts.item_type', 'items.item_area')
                //     ->count();

                // Tambahkan count untuk area saat ini
                $responseData[$itemName]['areas'][] = [
                    'area' => $area,
                    'count' => $itemCount->count,
                    // 'hardware_counts' => $hardwareCounts,
                ];
            }
        }

        $responseData = array_values($responseData);

        return response()->json([
            'success' => true,
            'message' => 'Item counts retrieved successfully',
            'data' => $responseData,
        ]);
    }

    public function getItemOnArea()
    {
        $areas = ['HO', 'NSA', 'DDA', 'ORA', 'CSA', 'SSA', 'WJA', 'EJA', 'KAL'];

        $itemCounts = [];

        foreach ($areas as $area) {
            $itemCounts = DB::table('items')
                ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
                ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
                ->where('items.item_area', $area)
                ->select('contracts.item_name', 'items.item_area', DB::raw('COUNT(*) as count'))
                ->groupBy('contracts.item_name', 'items.item_area')
                ->count();

            $hardwareCounts = DB::table('items')
                ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
                ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
                ->where('items.item_area', $area)
                ->where('contracts.item_type', 'Hardware')
                ->select('contracts.item_name', 'contracts.item_type', 'items.item_area', DB::raw('COUNT(*) as count'))
                ->groupBy('contracts.item_name', 'contracts.item_type', 'items.item_area')
                ->count();

            $softwareCounts = DB::table('items')
                ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
                ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
                ->where('items.item_area', $area)
                ->where('contracts.item_type', 'Software')
                ->select('contracts.item_name', 'contracts.item_type', 'items.item_area', DB::raw('COUNT(*) as count'))
                ->groupBy('contracts.item_name', 'contracts.item_type', 'items.item_area')
                ->count();

            $responseData[] = [
                'area' => $area,
                'count' => $itemCounts,
                'hardwareCount' => $hardwareCounts,
                'softwareCount' => $softwareCounts,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Item counts retrieved successfully',
            'data' => $responseData,
        ]);
    }

    public function getItemByArea($area)
    {
        $items = DB::table('items')
            ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->where('items.item_area', '=', $area)
            ->select('acquisition_values.item_merk', 'acquisition_values.item_type', 'contracts.item_name', 'companies.company_name', 'items.*', 'users.name', 'users.positiontext')
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Item List",
            "data" => $items,
        ]);
    }
}
