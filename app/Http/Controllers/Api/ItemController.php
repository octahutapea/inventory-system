<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Exports\ItemExport;
use App\Imports\ItemImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $itemCounts = DB::table('items')
            ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('acquisition_values.*', 'contracts.*', 'companies.*', 'items.*', 'users.*')
            ->count();

        $softwareCounts = DB::table('items')
            ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('acquisition_values.*', 'contracts.*', 'companies.*', 'items.*', 'users.*')
            ->where('contracts.item_type', '=', 'Software')
            ->count();

        $hardwareCounts = DB::table('items')
            ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('acquisition_values.*', 'contracts.*', 'companies.*', 'items.*', 'users.*')
            ->where('contracts.item_type', '=', 'Hardware')
            ->count();

        // $items = Item::all();
        $items = DB::table('items')
            ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->select('acquisition_values.item_merk', 'acquisition_values.item_type', 'contracts.item_name', 'companies.company_name', 'items.*', 'users.name', 'users.positiontext')
            ->get();

        // $items = DB::table('items')
        //     ->join('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
        //     ->join('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
        //     ->join('companies', 'contracts.company_id', '=', 'companies.id')
        //     ->join('users', 'items.user_id', '=', 'users.id')
        //     ->select('acquisition_values.type', 'contracts.item_name', 'contracts.serial_number', 'companies.name AS company_name', 'items.id', 'items.user_id', 'items.acquisition_value_id', 'items.area', 'items.location', 'items.status', 'users.name', 'users.position', DB::raw('COUNT(items.id) as itemCount'))
        //     ->groupBy('acquisition_values.type', 'contracts.item_name', 'contracts.serial_number', 'companies.name', 'items.id', 'items.user_id', 'items.acquisition_value_id', 'items.area', 'items.location', 'items.status', 'users.name', 'users.position')
        //     ->get();

        return response()->json([
            "success" => true,
            "message" => "Item List",
            "data" => $items,
            "total" => $itemCounts,
            "software" => $softwareCounts,
            "hardware" => $hardwareCounts
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'acquisition_value_id' => 'required',
            'item_area' => 'required',
            'item_location' => 'required',
            'serial_number' => 'required',
            'item_pict' => 'image|mimes:jpeg,jpg,png|max:2048',
            'item_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Item doesn't created.",
                "data" => $validator->errors()
            ]);
        }

        if ($request->hasFile('item_pict')) {
            $image = $request->file('item_pict');

            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $filenameSave = $filename . '_' . time() . '.' . $extension;

            // Store the image in the 'public/claim_document' directory
            $path = $image->storeAs('public/item_pict', $filenameSave);
        } else {
            $filenameSave = 'noimage.jpg';
        }

        // Update the 'item_pict' property in $input
        $input['item_pict'] = $filenameSave;

        $item = Item::create($input);

        return response()->json([
            "success" => true,
            "message" => "Item created successfully.",
            "data" => $item
        ]);
    }

    public function show($id)
    {
        $item = Item::find($id);

        if (is_null($item)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Item retrieved successfully.",
            "data" => $item
        ]);
    }

    public function update($id, Request $request)
    {
        $item = Item::find($id);

        if (is_null($item)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ]);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'acquisition_value_id' => 'required',
            'item_area' => 'required',
            'item_location' => 'required',
            'serial_number' => 'required',
            'item_pict' => 'image|mimes:jpeg,jpg,png|max:2048',
            'item_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error",
                "data" => $validator->errors()
            ]);
        }

        $input = $request->all();

        if ($request->hasFile('item_pict')) {
            $file = $request->file('item_pict');

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameSave = $filename . '_' . time() . '.' . $extension;

            // Store the file in the 'public/item_pict' directory
            $path = $file->storeAs('public/item_pict', $filenameSave);

            $input['item_pict'] = $filenameSave;
        } else {
            $filenameSave = 'noimage.jpg';
        }

        $item->item_area = $input['item_area'];
        $item->item_location = $input['item_location'];
        $item->serial_number = $input['serial_number'];
        $item->item_pict = $input['item_pict'];
        $item->item_status = $input['item_status'];
        $item->save();

        return response()->json([
            "success" => true,
            "message" => "Item updated successfully.",
            "data" => $item
        ]);
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (is_null($item)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ]);
        }

        $item->delete();

        return response()->json([
            "success" => true,
            "message" => "Item deleted successfully.",
            "data" => $item
        ]);
    }

    public function openImage($filename)
    {
        $path = storage_path('app/public/item_pict/' . $filename);

        if (!Storage::exists('public/item_pict/' . $filename)) {
            abort(404);
        }

        // Menggunakan response dengan header content-type yang sesuai
        return response()->file($path, ['Content-Type' => mime_content_type($path)]);
    }

    public function export()
    {
        return Excel::download(new ItemExport, 'item.xlsx');
    }

    public function import()
    {
        Excel::import(new ItemImport, request()->file('file'));

        return back();
    }
}
