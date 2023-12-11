<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Imports\PaymentImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index($id)
    {
        // $claims = Claim::all();

        $payments = DB::table('payments')
            ->leftJoin('contracts', 'contracts.id', '=', 'payments.contract_id')
            ->select('contracts.*', 'payments.*')
            ->where('payments.contract_id', $id)
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Claim List",
            "data" => $payments
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'contract_id' => 'required',
            'period' => 'required',
            'payment_note' => '',
            'period_start' => 'required',
            'period_end' => 'required',
            'details' => 'required',
            'total' => 'required',
            'invoice' => 'nullable|max:2048',
            'payment_status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Payment doesn't created.",
                "data" => $validator->errors()
            ]);
        }

        if ($request->hasFile('invoice')) {
            $file = $request->file('invoice');

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameSave = $filename . '_' . time() . '.' . $extension;

            // Store the file in the 'public/invoice' directory
            $path = $file->storeAs('public/invoice', $filenameSave);
        } else {
            $filenameSave = 'nodocument.pdf';
        }

        // Update the 'invoice' property in $input
        $input['invoice'] = $filenameSave;

        // Create a new Payment instance with updated $input
        $payments = Payment::create($input);

        return response()->json([
            "success" => true,
            "message" => "Payment created successfully.",
            "data" => $payments
        ]);
    }

    public function show($id)
    {
        $payments = Payment::find($id);

        if (is_null($payments)) {
            return response()->json([
                "success" => false,
                "message" => "Payment not found."
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Payment retrieved successfully.",
            "data" => $payments
        ]);
    }

    public function update($id, Request $request)
    {
        $payments = Payment::find($id);

        if (is_null($payments)) {
            return response()->json([
                "success" => false,
                "message" => "Payment not found."
            ]);
        }

        $validator = Validator::make($request->all(), [
            'invoice' => 'file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error",
                "data" => $validator->errors()
            ]);
        }

        $input = $request->all();

        if ($request->hasFile('invoice')) {
            $file = $request->file('invoice');

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filenameSave = $filename . '_' . time() . '.' . $extension;

            // Store the file in the 'public/invoice' directory
            $path = $file->storeAs('public/invoice', $filenameSave);

            $input['invoice'] = $filenameSave;
        } else {
            // Jika tidak ada file invoice yang diupload, tidak perlu mengubah nilai invoice
            $filenameSave = 'nodocument.pdf';
        }

        // Update hanya field invoice
        // $payments->update($input);
        $payments->invoice = $input['invoice'];
        $payments->update(['payment_status' => 'Lunas']);

        return response()->json([
            "success" => true,
            "message" => "Payment updated successfully.",
            "data" => $payments
        ]);
    }

    public function destroy($id)
    {
        $payments = Payment::find($id);

        if (is_null($payments)) {
            return response()->json([
                "success" => false,
                "message" => "Payment not found."
            ]);
        }

        $payments->delete();

        return response()->json([
            "success" => true,
            "message" => "Payment deleted successfully.",
            "data" => $payments
        ]);
    }

    public function import()
    {
        Excel::import(new PaymentImport, request()->file('file'));

        return back();
    }

    public function openInvoice($filename)
    {
        $path = storage_path('app/public/invoice/' . $filename);

        if (!Storage::exists('public/invoice/' . $filename)) {
            abort(404);
        }

        // Menggunakan response dengan header content-type yang sesuai
        return response()->file($path, ['Content-Type' => mime_content_type($path)]);
    }
}
