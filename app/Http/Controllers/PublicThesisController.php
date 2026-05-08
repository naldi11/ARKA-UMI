<?php

namespace App\Http\Controllers;

use App\Models\Thesis;
use Illuminate\Http\Request;

class PublicThesisController extends Controller
{
    /**
     * Menampilkan halaman pencarian publik.
     * Semua data dimuat sekaligus → Live search dilakukan di browser (client-side).
     * Global Scope ThreeYearScope otomatis membatasi hanya data 3 tahun terakhir.
     */
    public function index()
    {
        $theses = Thesis::with(['mahasiswa.user', 'supervisors.dosen'])
                        ->where('status', 'finished')
                        ->latest('approved_at')
                        ->get();

        return view('welcome', compact('theses'));
    }
    public function show(Thesis $thesis)
    {
        // Hanya skripsi yang sudah selesai/terarsip yang bisa dilihat publik
        if ($thesis->status !== 'finished') {
            abort(404);
        }

        $thesis->load(['mahasiswa.user', 'supervisors.dosen']);

        return view('thesis-detail', compact('thesis'));
    }
    /**
     * Download dokumen publik (Hanya Jurnal & Skripsi).
     */
    public function download(Thesis $thesis, $type)
    {
        if ($thesis->status !== 'finished') {
            abort(403);
        }

        if (!in_array($type, ['skripsi', 'jurnal'])) {
            abort(404);
        }

        $field = 'doc_' . $type;
        $path = $thesis->$field;

        if (!$path || !\Illuminate\Support\Facades\Storage::disk('private')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return \Illuminate\Support\Facades\Storage::disk('private')->download($path);
    }
}
