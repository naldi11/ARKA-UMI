<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil mahasiswa yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        return view('mahasiswa.profile', compact('user', 'mahasiswa'));
    }

    /**
     * Update data profil mahasiswa (nama, nomor HP, password).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'password'     => 'nullable|string|min:8|confirmed',
        ]);

        // Update nama di tabel users
        $user->update(['name' => $request->name]);

        // Update nomor HP di tabel mahasiswas
        if ($user->mahasiswa) {
            $user->mahasiswa->update(['phone' => $request->phone]);
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}
