<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    /**
     * Menampilkan tabel daftar Dosen.
     */
    public function index()
    {
        $dosens = Dosen::with('user')->latest()->paginate(10);
        return view('admin.dosen.index', compact('dosens'));
    }

    /**
     * Menyimpan/Membuat Akun Dosen baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip'        => 'required|string|unique:dosens,nip',
            'nama_gelar' => 'required|string',
            'username'   => 'required|string|unique:users,username',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'     => $request->nama_gelar,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'dosen',
            'status'   => 'active',
        ]);

        $user->dosen()->create([
            'nip'        => $request->nip,
            'nama_gelar' => $request->nama_gelar,
        ]);

        return back()->with('status', 'Akun dosen berhasil ditambahkan.');
    }

    /**
     * Update data dosen.
     */
    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nip'        => 'required|string|unique:dosens,nip,' . $dosen->id,
            'nama_gelar' => 'required|string',
            'email'      => 'required|email|unique:users,email,' . $dosen->user_id,
        ]);

        $dosen->update([
            'nip'        => $request->nip,
            'nama_gelar' => $request->nama_gelar,
        ]);

        $dosen->user->update([
            'name'  => $request->nama_gelar,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $dosen->user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('status', 'Data dosen berhasil diperbarui.');
    }

    /**
     * Hapus dosen beserta akun user-nya.
     */
    public function destroy(Dosen $dosen)
    {
        $user = $dosen->user;
        $dosen->delete();
        $user->delete();

        return back()->with('status', 'Data dosen berhasil dihapus.');
    }
}
