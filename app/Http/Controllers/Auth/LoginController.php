<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Menangani proses login menggunakan username.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login    = trim($request->username);
        $password = $request->password;

        // Cari user berdasarkan username ATAU email
        $user = User::where('username', $login)
                    ->orWhere('email', $login)
                    ->first();

        // Jika belum ketemu, coba cari lewat NIM mahasiswa
        if (!$user) {
            $mahasiswa = Mahasiswa::where('nim', $login)->first();
            if ($mahasiswa) {
                $user = $mahasiswa->user;
            }
        }

        // Validasi password
        if (!$user || !Hash::check($password, $user->password)) {
            return back()
                ->withErrors(['username' => 'Username, email, NIM, atau password salah.'])
                ->onlyInput('username');
        }

        // Pengecekan status akun
        if ($user->status !== 'active') {
            $message = ($user->status === 'pending')
                ? 'Akun Anda sedang menunggu verifikasi admin.'
                : 'Akun Anda ditolak, hubungi admin.';

            return back()->withErrors(['username' => $message])->onlyInput('username');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
