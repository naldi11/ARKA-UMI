<?php

namespace App\Http\Controllers;

use App\Models\Thesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Mengunduh dokumen skripsi melalu jalur aman tersertifikasi (Signed Route).
     */
    public function download(Request $request, Thesis $thesis, $type)
    {
        // Validasi parameter type agar aman dari Local File Inclusion
        if (!in_array($type, [
            'skripsi', 'meja_hijau', 'jurnal', 'cd', 'final', 
            'sk_pembimbing_1', 'sk_pembimbing_2', 'izin_penelitian', 'target_jurnal'
        ])) {
            abort(404, 'Tipe dokumen tidak didukung.');
        }

        // Ambil path dokumen berdasarkan tipe
        $field = 'doc_' . $type;
        $path = $thesis->$field;

        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        // Tentukan nama file saat didownload
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $fileName = 'File-' . strtoupper($type) . '-FL' . $thesis->id . '.' . $extension;

        // Stream file ke browser (Inline untuk preview)
        return Storage::disk('private')->response($path, $fileName);
    }
}
