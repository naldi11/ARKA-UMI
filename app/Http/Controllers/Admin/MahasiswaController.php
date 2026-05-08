<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa yang sedang menunggu verifikasi (status=pending).
     */
    public function pending()
    {
        $users = User::where('role', 'mahasiswa')
                     ->where('status', 'pending')
                     ->latest()
                     ->paginate(10);

        return view('admin.mahasiswa.pending', compact('users'));
    }

    /**
     * Menyetujui akun mahasiswa.
     */
    public function approve(User $user)
    {
        $user->update(['status' => 'active']);

        return back()->with('status', "Akun {$user->name} berhasil disetujui.");
    }

    /**
     * Menolak akun mahasiswa.
     */
    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);

        return back()->with('status', "Akun {$user->name} telah ditolak.");
    }
}
