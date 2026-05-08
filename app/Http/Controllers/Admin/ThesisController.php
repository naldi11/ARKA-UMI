<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Thesis;
use App\Models\ThesisSupervisor;
use App\Models\Scopes\ThreeYearScope;
use Illuminate\Http\Request;

class ThesisController extends Controller
{
    /**
     * Menampilkan semua daftar skripsi untuk dikelola admin.
     */
    public function index()
    {
        $theses = Thesis::withoutGlobalScope(ThreeYearScope::class)->with(['mahasiswa.user', 'supervisors.dosen'])->latest()->paginate(10);
        $dosens = Dosen::with('user')->get();

        return view('admin.theses.index', compact('theses', 'dosens'));
    }

    /**
     * Detail skripsi untuk verifikasi dokumen (Step-by-step).
     */
    public function show(Thesis $thesis)
    {
        $thesis->load(['mahasiswa.user', 'supervisors.dosen']);
        return view('admin.theses.show', compact('thesis'));
    }

    /**
     * Menugaskan Dosen Pembimbing 1 & 2 ke sebuah skripsi.
     */
    public function assign(Request $request, Thesis $thesis)
    {
        $request->validate([
            'dospem1_id' => 'required|exists:dosens,id',
            'dospem2_id' => 'required|exists:dosens,id|different:dospem1_id',
        ]);

        if ($thesis->status === 'finished') {
            return back()->with('error', 'Tidak dapat mengubah pembimbing pada skripsi yang sudah selesai.');
        }

        $thesis->supervisors()->delete();

        ThesisSupervisor::create([
            'thesis_id' => $thesis->id,
            'dosen_id' => $request->dospem1_id,
            'type' => 1,
        ]);

        ThesisSupervisor::create([
            'thesis_id' => $thesis->id,
            'dosen_id' => $request->dospem2_id,
            'type' => 2,
        ]);

        // Update status skripsi agar mahasiswa bisa mulai bimbingan/upload
        $thesis->update(['status' => 'approved']);

        // Log Aktivitas
        \App\Http\Controllers\Admin\ActivityController::log('assign_supervisor', "Menugaskan pembimbing untuk skripsi mahasiswa {$thesis->mahasiswa->user->name}");

        return back()->with('status', 'Dosen Pembimbing berhasil ditugaskan.');
    }

    /**
     * Verifikasi dokumen final oleh admin (Step 5).
     */
    public function verify(Request $request, Thesis $thesis)
    {
        $request->validate([
            'action' => 'required|in:finish,reject',
            'notes'  => 'nullable|string',
        ]);

        if ($request->action === 'finish') {
            // Cek apakah semua dokumen sudah disetujui (opsional, tapi disarankan)
            $thesis->update([
                'status' => 'finished',
                'approved_at' => now(),
                'admin_notes' => $request->notes,
            ]);
            
            $thesis->mahasiswa->user->notify(new \App\Notifications\ThesisStatusUpdated(
                'Selamat! Dokumen final skripsi Anda telah diverifikasi oleh Admin dan dinyatakan Selesai.', 'success', $thesis->id
            ));

            \App\Http\Controllers\Admin\ActivityController::log('verify_thesis', "Menyetujui dokumen final skripsi mahasiswa {$thesis->mahasiswa->user->name}");

            return redirect()->route('admin.theses.index')->with('status', 'Skripsi berhasil diverifikasi dan diselesaikan.');
        } else {
            // Jika ditolak, set status kembali ke approved agar mahasiswa bisa upload ulang
            // Dan secara otomatis tandai dokumen yang masih 'pending' menjadi 'rejected'
            $vData = $thesis->verification_data ?? [];
            $docs = ['sk_pembimbing_1', 'sk_pembimbing_2', 'target_jurnal', 'izin_penelitian', 'jurnal', 'skripsi', 'meja_hijau', 'cd'];
            
            foreach ($docs as $doc) {
                if (!isset($vData[$doc]) || $vData[$doc]['status'] === 'pending') {
                    if ($thesis->{"doc_$doc"}) { // Hanya jika filenya ada
                        $vData[$doc] = [
                            'status' => 'rejected',
                            'notes' => $request->notes ?? 'Ditolak melalui verifikasi total.',
                            'reviewed_at' => now()->toDateTimeString(),
                        ];
                    }
                }
            }

            $thesis->update([
                'status' => 'approved',
                'admin_notes' => $request->notes,
                'verification_data' => $vData,
            ]);
            
            $thesis->mahasiswa->user->notify(new \App\Notifications\ThesisStatusUpdated(
                'Dokumen Anda ditolak oleh Admin. Silakan baca catatan dan unggah ulang file Anda.', 'error', $thesis->id
            ));

            \App\Http\Controllers\Admin\ActivityController::log('reject_document', "Menolak dokumen final skripsi mahasiswa {$thesis->mahasiswa->user->name}");

            return redirect()->route('admin.theses.index')->with('status', 'Dokumen berhasil ditolak. Seluruh berkas pending telah ditandai sebagai revisi.');
        }
    }

    /**
     * Verifikasi dokumen individual.
     */
    public function reviewDocument(Request $request, Thesis $thesis)
    {
        $request->validate([
            'doc_key' => 'required|string',
            'status'  => 'required|in:accepted,rejected',
            'notes'   => 'nullable|string',
        ]);

        $data = $thesis->verification_data ?? [];
        $data[$request->doc_key] = [
            'status' => $request->status,
            'notes'  => $request->notes,
            'reviewed_at' => now()->toDateTimeString(),
        ];

        $thesis->update(['verification_data' => $data]);

        return back()->with('status', 'Status dokumen berhasil diperbarui.');
    }
    /**
     * Update data skripsi (Ganti Judul/Jurnal).
     */
    public function update(Request $request, Thesis $thesis)
    {
        $request->validate([
            'title' => 'required|string|max:500',
            'jurnal_name' => 'nullable|string|max:255',
        ]);

        if ($thesis->status === 'finished') {
            return back()->with('error', 'Tidak dapat mengubah judul pada skripsi yang sudah selesai.');
        }

        $thesis->update([
            'title' => $request->title,
            'jurnal_name' => $request->jurnal_name,
        ]);

        // Log Aktivitas
        \App\Http\Controllers\Admin\ActivityController::log('update_thesis', "Mengubah judul skripsi mahasiswa {$thesis->mahasiswa->user->name}");

        return back()->with('status', 'Judul skripsi berhasil diperbarui.');
    }
}
