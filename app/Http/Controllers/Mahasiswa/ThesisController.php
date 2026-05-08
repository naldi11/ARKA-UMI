<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\TitleProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ThesisController extends Controller
{
    public function dashboard()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) return redirect()->route('home')->with('error', 'Data mahasiswa tidak ditemukan.');

        $thesis = $mahasiswa->thesis()->with('supervisors.dosen')->first();
        
        // Auto-fix status jika berkas sudah lengkap
        if ($thesis && $thesis->status === 'approved' && $thesis->allDocsUploaded()) {
            $thesis->update(['status' => 'uploaded']);
        }

        $proposals = $mahasiswa->titleProposals()->latest()->get();

        return view('mahasiswa.dashboard', compact('thesis', 'proposals'));
    }

    public function status()
    {
        $thesis = Auth::user()->mahasiswa->thesis()->with('supervisors.dosen')->first();
        
        // Auto-fix status jika berkas sudah lengkap tapi masih 'approved' (misal setelah revisi)
        if ($thesis && $thesis->status === 'approved' && $thesis->allDocsUploaded()) {
            $thesis->update(['status' => 'uploaded']);
        }

        return view('mahasiswa.thesis-status', compact('thesis'));
    }

    public function storeProposal(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:500',
            'jurnal_name' => 'nullable|string|max:255',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        
        if ($mahasiswa->titleProposals()->where('status', 'approved')->exists()) {
            return back()->with('error', 'Judul Anda sudah ada yang disetujui.');
        }

        if ($mahasiswa->titleProposals()->count() >= 5) {
            return back()->with('error', 'Batas maksimal pengajuan judul adalah 5.');
        }

        TitleProposal::create([
            'mahasiswa_id' => $mahasiswa->id,
            'title' => $request->title,
            'jurnal_name' => $request->jurnal_name,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Judul berhasil diajukan.');
    }

    public function deleteProposal(TitleProposal $proposal)
    {
        if ($proposal->mahasiswa_id !== Auth::user()->mahasiswa->id) abort(403);
        if ($proposal->status !== 'pending') return back()->with('error', 'Hanya judul pending yang bisa dihapus.');
        
        $proposal->delete();
        return back()->with('status', 'Pengajuan judul dibatalkan.');
    }

    // ===================== BATCH UPLOADS =====================

    /**
     * Batch 1: Administrasi
     */
    public function uploadAdministrasiPage()
    {
        $thesis = Auth::user()->mahasiswa->thesis;
        if (!$thesis) return redirect()->route('mahasiswa.dashboard');
        return view('mahasiswa.upload.administrasi', compact('thesis'));
    }

    public function uploadAdministrasi(Request $request)
    {
        $request->validate([
            'doc_sk_pembimbing_1' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'doc_sk_pembimbing_2' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'doc_target_jurnal'   => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'doc_izin_penelitian' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        $thesis = Auth::user()->mahasiswa->thesis;
        $fields = ['doc_sk_pembimbing_1', 'doc_sk_pembimbing_2', 'doc_target_jurnal', 'doc_izin_penelitian'];
        
        $vData = $thesis->verification_data ?? [];
        $hasChange = false;

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $key = str_replace('doc_', '', $field);
                
                // Skip jika sudah diverifikasi (accepted)
                if (isset($vData[$key]['status']) && $vData[$key]['status'] === 'accepted') {
                    continue;
                }

                if ($thesis->$field) Storage::disk('private')->delete($thesis->$field);
                $thesis->$field = $request->file($field)->store('theses/' . $key, 'private');
                
                // Reset verification status untuk field ini
                unset($vData[$key]);
                $hasChange = true;
            }
        }

        if ($hasChange) {
            if ($thesis->allDocsUploaded()) {
                $thesis->status = 'uploaded';
            }
            $thesis->verification_data = $vData;
            $thesis->save();
            return back()->with('status', 'Berkas administrasi berhasil diperbarui.');
        }

        return back();
    }

    /**
     * Batch 2: Penelitian (Locked if Batch 1 incomplete)
     */
    public function uploadPenelitianPage()
    {
        $thesis = Auth::user()->mahasiswa->thesis;
        if (!$thesis) return redirect()->route('mahasiswa.dashboard');
        if (!$thesis->isBatch1Complete()) return redirect()->route('mahasiswa.upload.administrasi.page')->with('error', 'Lengkapi berkas administrasi terlebih dahulu.');
        
        return view('mahasiswa.upload.penelitian', compact('thesis'));
    }

    public function uploadPenelitian(Request $request)
    {
        $request->validate([
            'doc_jurnal'  => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'doc_skripsi' => 'nullable|file|mimes:pdf,doc,docx,txt|max:20480',
        ]);

        $thesis = Auth::user()->mahasiswa->thesis;
        if (!$thesis->isBatch1Complete()) return back()->with('error', 'Akses ditolak.');

        $fields = ['doc_jurnal', 'doc_skripsi'];
        $vData = $thesis->verification_data ?? [];
        $hasChange = false;

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $key = str_replace('doc_', '', $field);

                // Skip jika sudah diverifikasi (accepted)
                if (isset($vData[$key]['status']) && $vData[$key]['status'] === 'accepted') {
                    continue;
                }

                if ($thesis->$field) Storage::disk('private')->delete($thesis->$field);
                $thesis->$field = $request->file($field)->store('theses/' . $key, 'private');
                
                unset($vData[$key]);
                $hasChange = true;
            }
        }

        if ($hasChange) {
            if ($thesis->allDocsUploaded()) {
                $thesis->status = 'uploaded';
            }
            $thesis->verification_data = $vData;
            $thesis->save();
            return back()->with('status', 'Berkas penelitian berhasil diperbarui.');
        }

        return back();
    }

    /**
     * Batch 3: Kelulusan (Locked if Batch 2 incomplete)
     */
    public function uploadKelulusanPage()
    {
        $thesis = Auth::user()->mahasiswa->thesis;
        if (!$thesis) return redirect()->route('mahasiswa.dashboard');
        
        $batch2Complete = $thesis->doc_jurnal && $thesis->doc_skripsi;
        if (!$batch2Complete) return redirect()->route('mahasiswa.upload.penelitian.page')->with('error', 'Lengkapi berkas penelitian terlebih dahulu.');

        return view('mahasiswa.upload.kelulusan', compact('thesis'));
    }

    public function uploadKelulusan(Request $request)
    {
        $request->validate([
            'doc_meja_hijau' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'doc_cd'         => 'nullable|file|mimes:pdf,zip,rar,doc,docx,txt|max:51200',
        ]);

        $thesis = Auth::user()->mahasiswa->thesis;
        $batch2Complete = $thesis->doc_jurnal && $thesis->doc_skripsi;
        if (!$batch2Complete) return back()->with('error', 'Akses ditolak.');

        $fields = ['doc_meja_hijau', 'doc_cd'];
        $vData = $thesis->verification_data ?? [];
        $hasChange = false;

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $key = str_replace('doc_', '', $field);

                // Skip jika sudah diverifikasi (accepted)
                if (isset($vData[$key]['status']) && $vData[$key]['status'] === 'accepted') {
                    continue;
                }

                if ($thesis->$field) Storage::disk('private')->delete($thesis->$field);
                $thesis->$field = $request->file($field)->store('theses/' . $key, 'private');
                
                unset($vData[$key]);
                $hasChange = true;
            }
        }

        if ($hasChange) {
            if ($thesis->allDocsUploaded()) {
                $thesis->status = 'uploaded';
            }
            $thesis->verification_data = $vData;
            $thesis->save();
            return back()->with('status', 'Berkas kelulusan berhasil diperbarui.');
        }

        return back();
    }
}
