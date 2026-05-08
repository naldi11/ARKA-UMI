<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\ThesisSupervisor;
use App\Models\Scopes\ThreeYearScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThesisReviewController extends Controller
{
    /**
     * Dashboard Dosen.
     */
    public function dashboard()
    {
        $dosen = Auth::user()->dosen;
        $totalBimbingan = 0;
        $needReview = 0;
        $doneReview = 0;
        $theses = collect();

        if ($dosen) {
            $thesisIds = ThesisSupervisor::where('dosen_id', $dosen->id)->pluck('thesis_id');
            $totalBimbingan = $thesisIds->count();
            $needReview = ThesisSupervisor::where('dosen_id', $dosen->id)->whereNull('reviewed_at')->count();
            $doneReview = ThesisSupervisor::where('dosen_id', $dosen->id)->whereNotNull('reviewed_at')->count();
            $theses = Thesis::withoutGlobalScope(ThreeYearScope::class)
                ->whereIn('id', $thesisIds)
                ->with('mahasiswa.user')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dosen.dashboard', compact('dosen', 'totalBimbingan', 'needReview', 'doneReview', 'theses'));
    }

    /**
     * Menampilkan daftar skripsi yang perlu di-review oleh dosen.
     */
    public function index()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) return back()->with('error', 'Profil dosen tidak ditemukan.');

        // Ambil ID skripsi di mana dosen ini menjadi pembimbing
        $thesisIds = ThesisSupervisor::where('dosen_id', $dosen->id)->pluck('thesis_id');
        
        $theses = Thesis::withoutGlobalScope(ThreeYearScope::class)
                        ->whereIn('id', $thesisIds)
                        ->with('mahasiswa')
                        ->latest()
                        ->get();

        return view('dosen.theses.index', compact('theses', 'dosen'));
    }

    /**
     * Menangani persetujuan/penolakan judul skripsi.
     */
    public function review(Request $request, Thesis $thesis)
    {
        $dosen = Auth::user()->dosen;
        $supervisor = ThesisSupervisor::where('thesis_id', $thesis->id)
                                      ->where('dosen_id', $dosen->id)
                                      ->first();

        if (!$supervisor) abort(403);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        $isDospem1 = ($supervisor->type == 1);
        $isDospem2 = ($supervisor->type == 2);

        if ($request->action === 'approve') {
            $msg = 'Dosen Pembimbing memberikan catatan baru pada skripsi Anda.';
            $thesis->mahasiswa->user->notify(new \App\Notifications\ThesisStatusUpdated($msg, 'success', $thesis->id));
        } else {
            $thesis->mahasiswa->user->notify(new \App\Notifications\ThesisStatusUpdated('Dosen Pembimbing memberikan masukan/revisi. Silakan cek catatan.', 'error', $thesis->id));
        }

        $supervisor->update([
            'review_notes' => $request->notes,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', 'Review berhasil disimpan.');
    }
}
