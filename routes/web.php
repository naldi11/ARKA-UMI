<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Halaman Publik
Route::get('/', [\App\Http\Controllers\PublicThesisController::class, 'index'])->name('home');
Route::get('/skripsi/{thesis}', [\App\Http\Controllers\PublicThesisController::class, 'show'])->name('thesis.show');
Route::get('/skripsi/{thesis}/unduh/{type}', [\App\Http\Controllers\PublicThesisController::class, 'download'])->name('thesis.download');
Route::get('/panduan', function () { return view('guidelines'); })->name('guidelines');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/masuk', function () { return view('auth.login'); })->name('login');
    Route::post('/masuk', [LoginController::class, 'login']);

    Route::get('/daftar', function () { return view('auth.register'); })->name('register');
    Route::post('/daftar', [RegisterController::class, 'register']);
});

Route::post('/keluar', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Halaman Terproteksi
Route::middleware(['auth', 'check.status'])->group(function () {

    // Redirect berdasarkan role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'dosen') return redirect()->route('dosen.dashboard');
        return redirect()->route('mahasiswa.dashboard');
    })->name('dashboard');

    // ===================== ADMIN ROUTES =====================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');

        // Manajemen Mahasiswa (Verifikasi & Master)
        Route::get('/mahasiswa/verifikasi', [\App\Http\Controllers\Admin\MahasiswaController::class, 'pending'])->name('mahasiswa.pending');
        Route::get('/mahasiswa', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('mahasiswa.index');
        Route::post('/mahasiswa', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('mahasiswa.store');
        Route::put('/mahasiswa/{mahasiswa}', [\App\Http\Controllers\Admin\StudentController::class, 'update'])->name('mahasiswa.update');
        Route::delete('/mahasiswa/{mahasiswa}', [\App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('mahasiswa.destroy');
        Route::post('/mahasiswa/{user}/setujui', [\App\Http\Controllers\Admin\MahasiswaController::class, 'approve'])->name('mahasiswa.approve');
        Route::post('/mahasiswa/{user}/tolak', [\App\Http\Controllers\Admin\MahasiswaController::class, 'reject'])->name('mahasiswa.reject');

        // Konfirmasi Judul Skripsi
        Route::get('/konfirmasi-judul', [\App\Http\Controllers\Admin\TitleProposalController::class, 'index'])->name('title-proposals.index');
        Route::post('/konfirmasi-judul/{proposal}/setujui', [\App\Http\Controllers\Admin\TitleProposalController::class, 'approve'])->name('title-proposals.approve');
        Route::post('/konfirmasi-judul/{proposal}/tolak', [\App\Http\Controllers\Admin\TitleProposalController::class, 'reject'])->name('title-proposals.reject');

        // Manajemen Dosen
        Route::get('/dosen', [\App\Http\Controllers\Admin\DosenController::class, 'index'])->name('dosen.index');
        Route::post('/dosen', [\App\Http\Controllers\Admin\DosenController::class, 'store'])->name('dosen.store');
        Route::put('/dosen/{dosen}', [\App\Http\Controllers\Admin\DosenController::class, 'update'])->name('dosen.update');
        Route::delete('/dosen/{dosen}', [\App\Http\Controllers\Admin\DosenController::class, 'destroy'])->name('dosen.destroy');

        // Pengaturan
        Route::get('/pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // Kelola Skripsi
        Route::get('/skripsi', [\App\Http\Controllers\Admin\ThesisController::class, 'index'])->name('theses.index');
        Route::get('/skripsi/{thesis}', [\App\Http\Controllers\Admin\ThesisController::class, 'show'])->name('theses.show');
        Route::put('/skripsi/{thesis}', [\App\Http\Controllers\Admin\ThesisController::class, 'update'])->name('theses.update');
        Route::post('/skripsi/{thesis}/tugaskan', [\App\Http\Controllers\Admin\ThesisController::class, 'assign'])->name('theses.assign');
        Route::post('/skripsi/{thesis}/verifikasi', [\App\Http\Controllers\Admin\ThesisController::class, 'verify'])->name('theses.verify');
        Route::post('/skripsi/{thesis}/review-dokumen', [\App\Http\Controllers\Admin\ThesisController::class, 'reviewDocument'])->name('theses.review-document');

        // Kelola Angkatan
        Route::get('/angkatan', [\App\Http\Controllers\Admin\AngkatanController::class, 'index'])->name('angkatan.index');
        Route::post('/angkatan', [\App\Http\Controllers\Admin\AngkatanController::class, 'store'])->name('angkatan.store');
        Route::post('/angkatan/{angkatan}/toggle', [\App\Http\Controllers\Admin\AngkatanController::class, 'toggle'])->name('angkatan.toggle');
        Route::delete('/angkatan/{angkatan}', [\App\Http\Controllers\Admin\AngkatanController::class, 'destroy'])->name('angkatan.destroy');

        // Arsip & Export
        Route::get('/activity', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activity.index');
        Route::delete('/activity/clear', [\App\Http\Controllers\Admin\ActivityController::class, 'clear'])->name('activity.clear');
        Route::get('/arsip', [\App\Http\Controllers\Admin\ArchiveController::class, 'index'])->name('archive');
        Route::get('/arsip/export', [\App\Http\Controllers\Admin\ArchiveController::class, 'export'])->name('archive.export');
    });

    // ===================== DOSEN ROUTES =====================
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Dosen\ThesisReviewController::class, 'dashboard'])->name('dashboard');
        Route::get('/bimbingan', [\App\Http\Controllers\Dosen\ThesisReviewController::class, 'index'])->name('theses.index');
        Route::post('/bimbingan/{thesis}/review', [\App\Http\Controllers\Dosen\ThesisReviewController::class, 'review'])->name('theses.review');
    });

    // ===================== MAHASISWA ROUTES =====================
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'dashboard'])->name('dashboard');
        Route::get('/profil', [\App\Http\Controllers\Mahasiswa\ProfileController::class, 'index'])->name('profile');
        Route::post('/profil', [\App\Http\Controllers\Mahasiswa\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/skripsi/status', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'status'])->name('thesis.status');

        // Pengajuan Judul (multi-proposal)
        Route::post('/judul/ajukan', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'storeProposal'])->name('proposal.store');
        Route::delete('/judul/{proposal}/hapus', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'deleteProposal'])->name('proposal.delete');

        // Batch 1: Administrasi
        Route::get('/upload/administrasi', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'uploadAdministrasiPage'])->name('upload.administrasi.page');
        Route::post('/upload/administrasi', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'uploadAdministrasi'])->name('upload.administrasi');

        // Batch 2: Penelitian
        Route::get('/upload/penelitian', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'uploadPenelitianPage'])->name('upload.penelitian.page');
        Route::post('/upload/penelitian', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'uploadPenelitian'])->name('upload.penelitian');

        // Batch 3: Kelulusan
        Route::get('/upload/kelulusan', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'uploadKelulusanPage'])->name('upload.kelulusan.page');
        Route::post('/upload/kelulusan', [\App\Http\Controllers\Mahasiswa\ThesisController::class, 'uploadKelulusan'])->name('upload.kelulusan');
    });

    // Download dokumen dengan Signed Route
    Route::get('/dokumen/{thesis}/unduh/{type}', [\App\Http\Controllers\DocumentController::class, 'download'])
         ->name('dokumen.download')->middleware('signed');
});
