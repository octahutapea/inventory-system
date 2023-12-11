<?php

namespace App\Imports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class CompanyImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $row = array_change_key_case($row, CASE_LOWER);

        return new Company([
            'company_name' => $row["nama"],
            'company_address' => $row["alamat"],
            'company_telephone' => $row["telepon"],
            'company_pic' => $row["pic"],
        ]);
    }
}
