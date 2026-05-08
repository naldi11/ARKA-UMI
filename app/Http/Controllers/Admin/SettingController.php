<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Menampilkan pengaturan sistem.
     */
    public function index()
    {
        $domain = Setting::where('key', 'allowed_email_domain')->first();
        return view('admin.settings.index', compact('domain'));
    }

    /**
     * Mengupdate pengaturan (misal, domain email).
     */
    public function update(Request $request)
    {
        $request->validate([
            'allowed_email_domain' => 'required|string|starts_with:@',
        ]);

        Setting::updateOrCreate(
            ['key' => 'allowed_email_domain'],
            ['value' => $request->allowed_email_domain]
        );

        return back()->with('status', 'Pengaturan berhasil diperbarui.');
    }
}
