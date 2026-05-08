<?php

namespace App\Exports;

use App\Models\Thesis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ThesisExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    * Mengambil data skripsi yang sudah selesai (finished) untuk diarsipkan.
    */
    public function collection()
    {
        return Thesis::withoutGlobalScope(\App\Models\Scopes\ThreeYearScope::class)
                     ->where('status', 'finished')
                     ->with(['mahasiswa.user', 'supervisors.dosen'])
                     ->get();
    }

    /**
     * Header kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Mahasiswa',
            'NIM',
            'Judul Skripsi',
            'Nama Jurnal',
            'Dospem 1',
            'Dospem 2',
            'Tanggal Disetujui',
        ];
    }

    /**
     * Mapping data ke kolom Excel.
     */
    public function map($thesis): array
    {
        $dospem1 = $thesis->supervisors->where('type', 1)->first()?->dosen->nama_gelar ?? '-';
        $dospem2 = $thesis->supervisors->where('type', 2)->first()?->dosen->nama_gelar ?? '-';

        return [
            $thesis->id,
            $thesis->mahasiswa->user->name,
            $thesis->mahasiswa->nim,
            $thesis->title,
            $thesis->jurnal_name,
            $dospem1,
            $dospem2,
            $thesis->approved_at ? $thesis->approved_at->format('d/m/Y') : '-',
        ];
    }
}
