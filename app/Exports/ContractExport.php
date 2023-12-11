<?php

namespace App\Exports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContractExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Contract::select("contract_number", "contract_start_date", "contract_end_date", "item_name", "serial_number", "contract_value", "contract_category", "contract_type", "contract_note")->get();
    }

    public function headings(): array
    {
        return ["Nomor Kontrak", "Tanggal Mulai", "Tanggal Selesai", "Perangkat", "Nomor Serial", "Nilai Kontrak", "Jenis Kontrak", "Tipe Kontrak", "Keterangan"];
    }
}
