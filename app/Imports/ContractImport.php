<?php

namespace App\Imports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContractImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $company_id = request()->input('company_id');

        $row = array_change_key_case($row, CASE_LOWER);

        $row['company_id'] = $company_id;

        return new Contract([
            'company_id' => $row['company_id'],
            'contract_number' => $row["nomor_kontrak"],
            'contract_start_date' => $row["tanggal_mulai"],
            'contract_end_date' => $row["tanggal_selesai"],
            'item_name' => $row["perangkat"],
            'contract_value' => $row["nilai_kontrak"],
            'contract_type' => $row["tipe_kontrak"],
            'item_type' => $row["jenis_perangkat"],
            'contract_note' => $row["keterangan"],
            'contract_status' => $row["status"],
        ]);
    }
}
