<?php

namespace App\Exports;

use App\Models\AcquisitionValue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AcquisitionValueExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return AcquisitionValue::select(
            "contracts.contract_number",
            "contracts.contract_start_date",
            "contracts.contract_end_date",
            "contracts.item_name",
            "acquisition_values.item_merk",
            "acquisition_values.item_type",
            "acquisition_values.item_total",
            "acquisition_values.item_value"
        )
            ->leftJoin('contracts', 'contracts.id', '=', 'acquisition_values.contract_id')
            ->get();
    }

    public function headings(): array
    {
        return ["Nomor Kontrak", "Tanggal Mulai", "Tanggal Selesai", "Perangkat", "Merk", "Tipe", "Total", "Nilai Perolehan"];
    }
}
