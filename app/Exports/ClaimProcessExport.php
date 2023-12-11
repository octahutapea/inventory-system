<?php

namespace App\Exports;

use App\Models\Claim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClaimProcessExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Claim::select(
            "users.name",
            "users.positiontext",
            "claims.claim_location",
            "claims.facility_type",
            "claims.claim_imei",
            "claims.claim_date",
            "claims.claim_value",
        )
            ->leftJoin('users', 'users.id', '=', 'claims.user_id')
            ->where('claims.claim_status', 'Proses HC')
            ->get();
    }

    public function headings(): array
    {
        return ["Nama Lengkap", "Jabatan", "Lokasi", "Jenis Fasilitas", "Nomor IMEI", "Tanggal Klaim", "Nilai Klaim"];
    }

    public function map($claim): array
    {
        // Ambil path dokumen dari kolom 'document' dalam model Claim
        $documentPath = storage_path('app/public/claim_document/' . $claim->document);

        return [
            $claim->name,
            $claim->positiontext,
            $claim->claim_location,
            $claim->facility_type,
            $claim->claim_imei,
            $claim->claim_date,
            $claim->claim_value,
            // $documentPath, // Gunakan path dokumen untuk mendapatkan tautan
        ];
    }
}
