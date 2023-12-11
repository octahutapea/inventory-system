<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function getRequestAdmin()
    {
        $claims = DB::table('claims')
            ->leftJoin('users', 'users.id', '=', 'claims.user_id')
            ->select('claims.*', 'users.name', 'users.positiontext', 'users.prl_plan')
            ->where('claims.claim_status', 'Proses Admin')
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Claim List",
            "data" => $claims
        ]);
    }

    public function getRequestManager()
    {
        $claims = DB::table('claims')
            ->leftJoin('users', 'users.id', '=', 'claims.user_id')
            ->select('claims.*', 'users.name', 'users.positiontext', 'users.prl_plan')
            ->where('claims.claim_status', 'Proses Manager')
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Claim List",
            "data" => $claims
        ]);
    }

    public function getClaimById($id)
    {
        $claims = DB::table('claims')
            ->where('user_id', $id)
            ->whereIn('claim_status', ['Proses Manager', 'Proses Admin', 'Proses HC', 'Diterima'])
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Claim List",
            "data" => $claims
        ]);
    }

    public function getHistoryById($id)
    {
        $claims = DB::table('claims')
            ->where('user_id', $id)
            ->whereIn('claim_status', ['Selesai', 'Ditolak'])
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Claim List",
            "data" => $claims
        ]);
    }

    public function acceptClaimByAdmin($id)
    {
        // Ganti status klaim menjadi 'Proses Manager'
        DB::table('claims')->where('id', $id)->update(['claim_status' => 'Proses Manager']);

        return response()->json([
            "success" => true,
            "message" => "Claim accepted successfully"
        ]);
    }

    public function acceptClaimByManager($id)
    {
        // Ganti status klaim menjadi 'Proses HC'
        DB::table('claims')->where('id', $id)->update(['claim_status' => 'Proses HC']);

        return response()->json([
            "success" => true,
            "message" => "Claim accepted successfully"
        ]);
    }

    public function processClaimByAdmin()
    {
        $claims = DB::table('claims')
            ->leftJoin('users', 'users.id', '=', 'claims.user_id')
            ->select('claims.*', 'users.name', 'users.positiontext')
            ->where('claims.claim_status', 'Proses HC')
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Claim List",
            "data" => $claims
        ]);
    }

    public function rejectClaim($id)
    {
        // Ganti status klaim menjadi 'Ditolak'
        DB::table('claims')->where('id', $id)->update(['claim_status' => 'Ditolak']);

        return response()->json([
            "success" => true,
            "message" => "Claim rejected successfully"
        ]);
    }

    public function finishClaim($id)
    {
        // Ganti status klaim menjadi 'Ditolak'
        DB::table('claims')->where('id', $id)->update(['claim_status' => 'Selesai']);

        return response()->json([
            "success" => true,
            "message" => "Claim finished"
        ]);
    }
}
