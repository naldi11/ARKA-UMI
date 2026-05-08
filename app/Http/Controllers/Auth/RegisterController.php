<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Menangani proses registrasi mahasiswa.
     * Alur: Buat User -> Buat Profil Mahasiswa -> Update Whitelist.
     */
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            // 1. Buat User baru (Role: Mahasiswa, Status: Pending)
            $user = User::create([
                'username' => $request->username,
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'mahasiswa',
                'status'   => 'pending', // Menunggu approval admin sesuai PRD
            ]);

            // 2. Buat profil Mahasiswa
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim'     => $request->nim,
            ]);

            // 3. NIM Whitelist logic removed as it's replaced by Angkatan system.
            // All NIMs matching an open Angkatan are now allowed automatically.

            DB::commit();

            return redirect()->route('login')
                ->with('status', 'Akun berhasil dibuat! Menunggu verifikasi admin sebelum dapat login.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['email' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()]);
        }
    }
}
