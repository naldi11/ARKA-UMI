<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     * Memastikan akun user berstatus 'active'. 
     * Jika 'pending' atau 'rejected', berikan feedback yang sesuai.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if ($user->status === 'pending') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun Anda berhasil dibuat, menunggu verifikasi admin.');
            }

            if ($user->status === 'rejected') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun ditolak, hubungi admin.');
            }
        }

        return $next($request);
    }
}
