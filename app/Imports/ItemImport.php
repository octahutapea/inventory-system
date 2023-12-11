<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $row = array_change_key_case($row, CASE_LOWER);

        $user = User::where([
            'name' => $row["nama_pengguna"],
            'positiontext' => $row["jabatan"],
        ])->first();

        $acquisition_value_id = request()->input('acquisition_value_id');
        $row['acquisition_value_id'] = $acquisition_value_id;

        // Pastikan pengguna (user) ditemukan sebelum mencoba mengakses 'id'
        if ($user) {
            return new Item([
                'user_id' => $user->id,
                'acquisition_value_id' => $row["acquisition_value_id"],
                'item_area' => $row["area"],
                'item_location' => $row["lokasi"],
                'serial_number' => $row["nomor_serial"],
                'item_status' => $row["status"],
            ]);
        } else {
            // Handle jika pengguna (user) tidak ditemukan, misalnya lewatkan data atau tangani dengan cara tertentu
            return null;
        }
    }
}
