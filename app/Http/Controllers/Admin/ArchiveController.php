<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\ThesisExport;
use App\Models\Thesis;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    /**
     * Menampilkan dashboard arsip data skripsi.
     */
    public function index()
    {
        // Total data skripsi yang sudah selesai
        $totalFinished = Thesis::withoutGlobalScope(\App\Models\Scopes\ThreeYearScope::class)
                               ->where('status', 'finished')
                               ->count();

        // Data 3 tahun terakhir dengan status finished (aktif di pencarian publik)
        $totalPublic = Thesis::where('status', 'finished')->count();

        return view('admin.archive.index', compact('totalFinished', 'totalPublic'));
    }

    /**
     * Memulai proses download file Excel arsip skripsi.
     */
    public function export()
    {
        return Excel::download(new ThesisExport, 'arsip-skripsi-floren-' . now()->format('Y-m-d') . '.xlsx');
    }
}
