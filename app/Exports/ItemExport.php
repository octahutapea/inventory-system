<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Item::select(
            "items.item_area",
            "companies.company_name",
            "contracts.item_name",
            "acquisition_values.item_merk",
            "acquisition_values.item_type",
            "contracts.serial_number",
            "users.name",
            "users.positiontext",
            "items.item_location",
            "items.item_status"
        )
            ->leftJoin('acquisition_values', 'items.acquisition_value_id', '=', 'acquisition_values.id')
            ->leftJoin('contracts', 'acquisition_values.contract_id', '=', 'contracts.id')
            ->leftJoin('companies', 'contracts.company_id', '=', 'companies.id')
            ->leftJoin('users', 'items.user_id', '=', 'users.id')
            ->get();
    }

    public function headings(): array
    {
        return ["Area", "Instansi", "Perangkat", "Merk", "Tipe", "Nomor Serial", "Nama Pengguna", "Jabatan", "Lokasi", "Status"];
    }
}
