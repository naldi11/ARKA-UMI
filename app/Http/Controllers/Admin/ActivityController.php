<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Menampilkan daftar log aktivitas sistem (Audit Trail).
     */
    public function index()
    {
        $activities = Activity::with('user')->latest()->paginate(20);
        return view('admin.activity.index', compact('activities'));
    }

    /**
     * Menghapus semua log aktivitas.
     */
    public function clear()
    {
        Activity::truncate();
        return back()->with('status', 'Seluruh log aktivitas sistem berhasil dibersihkan.');
    }

    /**
     * Helper static untuk mencatat aktivitas (opsional jika ingin dipanggil manual).
     */
    public static function log($action, $description)
    {
        Activity::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
