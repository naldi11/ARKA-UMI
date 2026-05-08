<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Thesis;
use App\Models\ThesisSupervisor;
use App\Models\TitleProposal;
use Illuminate\Http\Request;

class TitleProposalController extends Controller
{
    /**
     * Tampilkan semua pengajuan judul (Daftar Flat sesuai keinginan USER).
     */
    public function index()
    {
        $proposals = TitleProposal::with('mahasiswa.user')
            ->latest()
            ->paginate(20);

        $dosens = Dosen::with('user')->get();

        return view('admin.title-proposals.index', compact('proposals', 'dosens'));
    }

    /**
     * Admin menyetujui satu judul dan otomatis menolak judul lain dari mahasiswa tersebut.
     */
    public function approve(Request $request, TitleProposal $proposal)
    {
        $request->validate([
            'dospem1_id' => 'required|exists:dosens,id',
            'dospem2_id' => 'required|exists:dosens,id|different:dospem1_id',
        ]);

        $mahasiswa = $proposal->mahasiswa;
        
        // Cek apakah mahasiswa sudah punya skripsi aktif (APPROVED)
        if ($mahasiswa->thesis) {
            return back()->with('error', 'Mahasiswa ini sudah memiliki judul yang disetujui.');
        }

        $proposal->update(['status' => 'approved']);

        // VALIDASI OTOMATIS: Tolak semua proposal lain mahasiswa ini secara otomatis
        TitleProposal::where('mahasiswa_id', $proposal->mahasiswa_id)
            ->where('id', '!=', $proposal->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected', 'rejection_reason' => 'Judul lain telah disetujui oleh Admin.']);

        // Buat record thesis
        $thesis = Thesis::create([
            'mahasiswa_id'      => $proposal->mahasiswa_id,
            'title_proposal_id' => $proposal->id,
            'title'             => $proposal->title,
            'jurnal_name'       => $proposal->jurnal_name ?? '-',
            'status'            => 'approved',
        ]);

        ThesisSupervisor::create(['thesis_id' => $thesis->id, 'dosen_id' => $request->dospem1_id, 'type' => 1]);
        ThesisSupervisor::create(['thesis_id' => $thesis->id, 'dosen_id' => $request->dospem2_id, 'type' => 2]);

        $mahasiswa->user->notify(new \App\Notifications\ThesisStatusUpdated(
            'Judul skripsi Anda telah disetujui.', 'success', $thesis->id
        ));

        // Log Aktivitas
        \App\Http\Controllers\Admin\ActivityController::log('approve_title', "Menyetujui judul '{$proposal->title}' untuk mahasiswa {$mahasiswa->user->name}");

        return back()->with('status', 'Judul berhasil disetujui. Judul lain dari mahasiswa ini telah otomatis ditolak.');
    }

    /**
     * Admin menolak pengajuan judul.
     */
    public function reject(Request $request, TitleProposal $proposal)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $proposal->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason ?? 'Ditolak oleh Admin.',
        ]);

        // Log Aktivitas
        \App\Http\Controllers\Admin\ActivityController::log('reject_title', "Menolak judul '{$proposal->title}' untuk mahasiswa {$proposal->mahasiswa->user->name}");

        return back()->with('status', 'Pengajuan judul ditolak.');
    }
}
