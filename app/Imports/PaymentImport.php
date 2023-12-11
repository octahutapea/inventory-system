<?php

namespace App\Imports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PaymentImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $contract_id = request()->input('contract_id');

        $row = array_change_key_case($row, CASE_LOWER);

        $row['contract_id'] = $contract_id;

        return new Payment([
            'contract_id' => $row['contract_id'],
            'period' => $row["tagihan_periode"],
            'payment_note' => $row["keterangan"],
            'period_start' => $row["periode_mulai"],
            'period_end' => $row["periode_akhir"],
            'details' => $row["rincian"],
            'total' => $row["total"],
            'payment_status' => $row["status_pembayaran"],
        ]);
    }
}
