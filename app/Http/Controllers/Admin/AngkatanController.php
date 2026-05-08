<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use Illuminate\Http\Request;

class AngkatanController extends Controller
{
    public function index()
    {
        $angkatans = Angkatan::orderByDesc('year')->get();
        return view('admin.angkatan.index', compact('angkatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|digits:4|integer|min:2000|max:2099|unique:angkatans,year',
        ], [
            'year.unique'  => 'Angkatan ' . $request->year . ' sudah terdaftar.',
            'year.digits'  => 'Tahun harus 4 digit.',
            'year.min'     => 'Tahun tidak valid.',
            'year.max'     => 'Tahun tidak valid.',
        ]);

        Angkatan::create(['year' => $request->year, 'is_open' => true]);

        return back()->with('status', 'Angkatan ' . $request->year . ' berhasil dibuka.');
    }

    public function toggle(Angkatan $angkatan)
    {
        $angkatan->update(['is_open' => !$angkatan->is_open]);

        $status = $angkatan->is_open ? 'dibuka' : 'ditutup';
        return back()->with('status', 'Pendaftaran angkatan ' . $angkatan->year . ' ' . $status . '.');
    }

    public function destroy(Angkatan $angkatan)
    {
        $angkatan->delete();
        return back()->with('status', 'Angkatan ' . $angkatan->year . ' dihapus.');
    }
}
