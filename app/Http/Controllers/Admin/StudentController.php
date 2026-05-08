<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Menampilkan daftar semua mahasiswa.
     */
    public function index()
    {
        $students = Mahasiswa::with('user')->latest()->paginate(15);
        return view('admin.mahasiswa.index', compact('students'));
    }

    /**
     * Menyimpan data mahasiswa baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim'      => 'required|string|unique:mahasiswas,nim',
            'name'     => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa',
            'status'   => 'active',
        ]);

        $user->mahasiswa()->create([
            'nim' => $request->nim,
        ]);

        return back()->with('status', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * Memperbarui data mahasiswa.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim'    => 'required|string|unique:mahasiswas,nim,' . $mahasiswa->id,
            'name'   => 'required|string',
            'email'  => 'required|email|unique:users,email,' . $mahasiswa->user_id,
            'status' => 'required|in:active,pending,rejected',
        ]);

        $mahasiswa->update([
            'nim' => $request->nim,
        ]);

        $mahasiswa->user->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $mahasiswa->user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('status', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Menghapus data mahasiswa.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $user = $mahasiswa->user;
        $mahasiswa->delete();
        $user->delete();

        return back()->with('status', 'Data mahasiswa berhasil dihapus.');
    }
}
