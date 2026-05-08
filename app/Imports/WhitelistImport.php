<?php

namespace App\Imports;

use App\Models\NimWhitelist;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WhitelistImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    * Memasukkan data NIM dari file (CSV/Excel) ke database.
    */
    public function model(array $row)
    {
        // Asumsi kolom di file bernama 'nim' (dan opsional 'name')
        if (empty($row['nim'])) return null;

        $nim = trim($row['nim']);

        // Skip duplikat NIM yang sudah ada
        if (NimWhitelist::where('nim', $nim)->exists()) return null;

        return new NimWhitelist([
            'nim'     => $nim,
            'name'    => trim($row['name'] ?? $nim), // fallback ke NIM jika kolom name tidak ada
            'is_used' => false,
        ]);
    }
}
