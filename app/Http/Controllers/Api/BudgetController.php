<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\AcquisitionValue;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $sum = 0;

        $acquisition_values = DB::table('acquisition_values')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->select(
                'contracts.contract_number',
                'contracts.contract_start_date',
                'contracts.contract_end_date',
                'contracts.item_name',
                'acquisition_values.*'
            )
            ->get();

        foreach ($acquisition_values as $acquisition) {
            $contractStartDate = Carbon::parse($acquisition->contract_start_date)->year;
            $contractEndDate = Carbon::parse($acquisition->contract_end_date)->year;

            $startDate = Carbon::parse($acquisition->contract_start_date);
            $endDate = Carbon::parse($acquisition->contract_end_date);

            $day = $startDate->diffInDays($endDate);

            $startOfYear = Carbon::create($currentYear, 1, 1);
            $endOfYear = Carbon::createFromDate($currentYear, 12, 31);

            if ($day > 0) {
                $avgValue = $acquisition->item_value / $day;
            } else {
                // Handle jika $day sama dengan 0, misalnya, memberikan nilai default atau memberikan pesan error
                $avgValue = 0; // atau nilai default lainnya
            }

            // Check if contract_start_date is in the current year
            if ($currentYear >= $contractStartDate && $currentYear <= $contractEndDate) {
                if ($contractStartDate == $currentYear && $contractEndDate == $currentYear) {
                    $remainingDay = $day;
                } else if ($contractStartDate == $currentYear && $contractEndDate > $currentYear) {
                    $remainingDay = $startDate->diffInDays($endOfYear);
                } else if ($contractStartDate < $currentYear && $contractEndDate > $currentYear) {
                    $remainingDay = $startOfYear->diffInDays($endOfYear);
                } else if ($contractStartDate < $currentYear && $contractEndDate == $currentYear) {
                    $remainingDay = $startOfYear->diffInDays($endDate);
                } else {
                    $remainingDay = 0;
                }

                // Multiply item_value with total
                $result = $avgValue * $remainingDay * $acquisition->item_total;
                $sum = $sum + $result;
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Anggaran",
            "avgValue" => $avgValue,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "remainingDay" => $remainingDay,
            "currentYear" => $currentYear,
            "currentBudget" => $sum,
        ]);
    }

    public function monthlyBudget($year)
    {

        $acquisition_values = DB::table('acquisition_values')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->select(
                'contracts.contract_number',
                'contracts.contract_start_date',
                'contracts.contract_end_date',
                'contracts.item_name',
                'acquisition_values.*'
            )
            ->get();

        foreach ($acquisition_values as $acquisition) {
            $contractStartYear = Carbon::parse($acquisition->contract_start_date)->year;

            // Check if contract_start_date is in the current year
            if ($contractStartYear == $year) {
                $contractStartMonth = Carbon::parse($acquisition->contract_start_date)->month;

                for ($month = 1; $month <= 12; $month++) {

                    if ($contractStartMonth == $month) {
                        $result = $acquisition->item_value * $acquisition->item_total;
                        $monthlyBudget[$month] = isset($monthlyBudget[$month]) ? $monthlyBudget[$month] + $result : $result;
                    } else {
                        $monthlyBudget[$month] = isset($monthlyBudget[$month]) ? $monthlyBudget[$month] : 0;
                    }
                }
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Anggaran",
            "budget" => $monthlyBudget
        ]);
    }

    public function trend($year)
    {
        $currentBudget = 0;
        $oneYearBudget = 0;
        $twoYearBudget = 0;
        $threeYearBudget = 0;
        $fourYearBudget = 0;

        $acquisition_values = DB::table('acquisition_values')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->select(
                'contracts.contract_number',
                'contracts.contract_start_date',
                'contracts.contract_end_date',
                'contracts.item_name',
                'acquisition_values.*'
            )
            ->get();

        foreach ($acquisition_values as $acquisition) {
            $contractStartDate = Carbon::parse($acquisition->contract_start_date)->year;

            // Check if contract_start_date is in the current year
            if ($contractStartDate == $year) {
                // Multiply item_value with total
                $result = $acquisition->item_value * $acquisition->item_total;
                $currentBudget = $currentBudget + $result;
            }

            if ($contractStartDate == ($year - 1)) {
                // Multiply item_value with total
                $result = $acquisition->item_value * $acquisition->item_total;
                $oneYearBudget = $oneYearBudget + $result;
            }

            if ($contractStartDate == ($year - 2)) {
                // Multiply item_value with total
                $result = $acquisition->item_value * $acquisition->item_total;
                $twoYearBudget = $twoYearBudget + $result;
            }

            if ($contractStartDate == ($year - 3)) {
                // Multiply item_value with total
                $result = $acquisition->item_value * $acquisition->item_total;
                $threeYearBudget = $threeYearBudget + $result;
            }

            if ($contractStartDate == ($year - 4)) {
                // Multiply item_value with total
                $result = $acquisition->item_value * $acquisition->item_total;
                $fourYearBudget = $fourYearBudget + $result;
            }
        }

        $trend_budget = [
            $year => $currentBudget,
            $year - 1 => $oneYearBudget,
            $year - 2 => $twoYearBudget,
            $year - 3 => $threeYearBudget,
            $year - 4 => $fourYearBudget
        ];

        return response()->json([
            "success" => true,
            "message" => "Anggaran",
            "trend" => $trend_budget
        ]);
    }
}
