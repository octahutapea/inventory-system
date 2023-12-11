<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompanyExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Company::select("company_name", "company_address", "company_telephone", "company_pic")->get();
    }

    public function headings(): array
    {
        return ["Nama", "Alamat", "Telepon", "PIC"];
    }
}
