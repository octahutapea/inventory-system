<?php

namespace App\Imports;

use App\Models\AcquisitionValue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AcquisitionValueImport implements ToModel, WithHeadingRow
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

        return new AcquisitionValue([
            'contract_id' => $row['contract_id'],
            'item_merk' => $row["merk"],
            'item_type' => $row["tipe"],
            'item_total' => $row["total"],
            'item_value' => $row["nilai_perolehan"],
        ]);
    }
}
