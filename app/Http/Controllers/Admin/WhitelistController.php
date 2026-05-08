<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NimWhitelist;
use App\Imports\WhitelistImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class WhitelistController extends Controller
{
    /**
     * Menampilkan daftar NIM yang masuk dalam whitelist.
     */
    public function index()
    {
        $whitelists = NimWhitelist::latest()->paginate(20);
        return view('admin.whitelist.index', compact('whitelists'));
    }

    /**
     * Tambah NIM secara manual satu per satu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim'  => 'required|string|unique:nim_whitelists,nim',
            'name' => 'nullable|string|max:255',
        ], [
            'nim.unique' => 'NIM tersebut sudah ada di whitelist.',
        ]);

        NimWhitelist::create([
            'nim'     => trim($request->nim),
            'name'    => trim($request->name ?? ''),
            'is_used' => false,
        ]);

        return back()->with('status', 'NIM ' . $request->nim . ' berhasil ditambahkan.');
    }

    /**
     * Hapus satu entri NIM dari whitelist.
     */
    public function destroy(NimWhitelist $whitelist)
    {
        if ($whitelist->is_used) {
            return back()->with('error', 'NIM ini sudah dipakai oleh mahasiswa terdaftar, tidak dapat dihapus.');
        }

        $whitelist->delete();
        return back()->with('status', 'NIM ' . $whitelist->nim . ' berhasil dihapus.');
    }

    /**
     * Mengunduh file template CSV untuk panduan import.
     */
    public function template()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-whitelist-nim.csv"',
        ];

        $rows = [
            ['nim', 'name'],
            ['2021110001', 'Budi Santoso'],
            ['2021110002', 'Siti Rahayu'],
            ['2021110003', 'Ahmad Fauzi'],
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mengimpor data NIM dari file CSV/Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx|max:2048',
        ]);

        try {
            Excel::import(new WhitelistImport, $request->file('file'));
            return back()->with('status', 'Data NIM berhasil diimpor ke Whitelist.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
