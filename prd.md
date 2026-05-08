Role & Akses
RoleCara MasukAdminDibuat via SeederDosenDibuat oleh AdminMahasiswaRegister mandiri dengan validasi NIM atau Email domain kampus
Login semua role menggunakan username. Tidak ada register publik untuk Dosen dan Admin.

Alur Registrasi Mahasiswa
Validasi bersifat kondisional, bukan keduanya wajib sekaligus:
Mahasiswa input: NIM (opsional), Email, Username, Password

KONDISI A — Daftar dengan NIM:
    → NIM wajib ada di tabel nim_whitelist
    → NIM belum pernah dipakai register (is_used = false)
    → Email bebas, tidak harus domain kampus
    → Jika lolos → akun dibuat, status PENDING

KONDISI B — Daftar dengan Email Domain Kampus:
    → Email harus sesuai domain yang diset admin (misal @mahasiswa.univ.ac.id)
    → Karena pakai email kampus, NIM ikut WAJIB diisi
    → NIM wajib ada di whitelist dan belum dipakai
    → Jika lolos → akun dibuat, status PENDING

KONDISI GAGAL:
    → NIM tidak diisi DAN email bukan domain kampus
       → Tolak: "Masukkan NIM terdaftar atau gunakan email institusi"
    → NIM diisi tapi tidak ada di whitelist
       → Tolak: "NIM tidak dikenali, hubungi admin"
    → NIM sudah pernah dipakai
       → Tolak: "NIM sudah terdaftar"
    → Email domain kampus tapi NIM tidak diisi
       → Tolak: "Email institusi wajib disertai NIM"

SETELAH REGISTER (semua kondisi):
    → Tampilkan pesan sistem: "Akun berhasil dibuat, menunggu verifikasi admin"
    → Admin approve/reject via panel
    → Jika approve: mahasiswa bisa login
    → Jika reject: muncul pesan sistem saat coba login: "Akun ditolak, hubungi admin"

Status Flow Skripsi
Step 1 → Submit Judul + Nama Jurnal oleh Mahasiswa
            → jurnal_name wajib UNIQUE di seluruh tabel theses
            → Status: pending

Step 2 → Review oleh Dospem 1
            Approve → lanjut ke Dospem 2
            Reject  → mahasiswa bisa revisi judul & resubmit (status kembali ke pending)

Step 3 → Review oleh Dospem 2 (hanya aktif setelah Dospem 1 approve)
            Approve → mahasiswa bisa upload dokumen
            Reject  → mahasiswa bisa revisi & resubmit dari awal

Step 4 → Upload Dokumen oleh Mahasiswa
            3 slot wajib semua terisi:
            - Dokumen Skripsi (PDF)
            - Dokumen Meja Hijau/Persidangan
            - File CD Skripsi / Dokumen Final (PDF)
            → Setelah semua terupload, submit ke admin

Step 5 → Verifikasi Dokumen oleh Admin
            Approve → approved_at di-set ke NOW(), status = selesai
            Reject  → mahasiswa upload ulang dokumen bermasalah + catatan admin
Stepper UI di halaman mahasiswa:
[1: Submit Judul] → [2: Review Dospem 1] → [3: Review Dospem 2] 
→ [4: Upload Dokumen] → [5: Verifikasi Admin]

Warna state:
- Abu-abu  : Belum sampai step ini
- Kuning   : Sedang diproses
- Hijau    : Selesai / Approved
- Merah    : Ditolak (tampilkan catatan)

Business Logic & Rules
Validasi Unik Jurnal
php// FormRequest saat submit judul
'jurnal_name' => 'required|unique:theses,jurnal_name'

// Saat revisi/edit (abaikan ID sendiri)
'jurnal_name' => 'required|unique:theses,jurnal_name,' . $thesis->id
Validasi Register Kondisional
php// Di RegisterRequest - withValidator()
$validator->after(function ($validator) use ($data) {
    $emailDomain  = '@' . explode('@', $data['email'])[1];
    $domainKampus = Setting::get('allowed_email_domain'); // misal @mahasiswa.univ.ac.id
    $isEmailKampus = $emailDomain === $domainKampus;
    $nimDiisi      = !empty($data['nim']);

    // Email kampus → NIM wajib
    if ($isEmailKampus && !$nimDiisi) {
        $validator->errors()->add('nim', 'Email institusi wajib disertai NIM.');
    }

    // Bukan email kampus dan NIM tidak diisi → tolak
    if (!$isEmailKampus && !$nimDiisi) {
        $validator->errors()->add('nim', 'Masukkan NIM terdaftar atau gunakan email institusi.');
    }

    // NIM diisi → cek whitelist
    if ($nimDiisi) {
        $whitelist = NimWhitelist::where('nim', $data['nim'])->first();
        if (!$whitelist) {
            $validator->errors()->add('nim', 'NIM tidak dikenali, hubungi admin.');
        } elseif ($whitelist->is_used) {
            $validator->errors()->add('nim', 'NIM sudah terdaftar.');
        }
    }
});
Global Scope 3 Tahun
php// App\Scopes\ThreeYearScope.php
// Hitung dari approved_at (tanggal admin verifikasi dokumen final)
class ThreeYearScope implements Scope {
    public function apply(Builder $builder, Model $model) {
        $builder->where('approved_at', '>=', now()->subYears(3));
    }
}

// Di Model Thesis — aktif otomatis untuk mahasiswa & publik
protected static function booted() {
    static::addGlobalScope(new ThreeYearScope);
}

// Admin & Dosen bypass scope ini
Thesis::withoutGlobalScopes()->get();
Export Arsip Admin
php// Ambil data yang sudah lewat 3 tahun (tidak terlihat mahasiswa/publik)
Thesis::withoutGlobalScopes()
    ->whereNotNull('approved_at')
    ->where('approved_at', '<', now()->subYears(3))
    ->with(['mahasiswa', 'supervisors.dosen'])
    ->get();

// Export via maatwebsite/excel
// Tombol "Export Arsip" tersedia di dashboard admin
Secure File Download
php// Dokumen disimpan di disk private, tidak bisa diakses langsung via URL
// Generate temporary signed URL berlaku 5 menit
return URL::temporarySignedRoute(
    'dokumen.download',
    now()->addMinutes(5),
    ['thesis' => $thesis->id, 'type' => $type]
);

// Di controller download, validasi signature dulu
// Lalu stream file dari storage private ke browser
Notifikasi Sistem (Tanpa Email)
Gunakan Laravel Database Notification (tabel notifications)
Bell icon di navbar dengan badge jumlah belum dibaca

Event yang memicu notifikasi:
- Admin approve/reject akun mahasiswa
- Dospem 1 approve/reject judul
- Dospem 2 approve/reject judul
- Admin approve/reject dokumen

Public Search (Tanpa Login)
Endpoint: GET /search?q=keyword

Behavior:
- Bisa diakses tanpa login
- Search by nama mahasiswa ATAU nama dosen pembimbing
- Global Scope aktif → hanya tampil data approved_at <= 3 tahun terakhir
- Klik detail → redirect ke /login dengan pesan "Login untuk melihat detail"

Query:
Thesis::whereHas('mahasiswa', fn($q) => $q->where('nama_lengkap', 'LIKE', "%$keyword%"))
      ->orWhereHas('supervisors.dosen', fn($q) => $q->where('nama_lengkap', 'LIKE', "%$keyword%"))
      ->with(['mahasiswa', 'supervisors.dosen'])
      ->get();

Fitur Per Role
Admin

Login
Approve/Reject akun mahasiswa yang register
Kelola Dosen: CRUD + buatkan akun login dosen
Kelola Mahasiswa: CRUD manual jika diperlukan
Import NIM whitelist via file CSV
Setting domain email kampus yang diizinkan (bisa diubah kapanpun)
Monitor semua skripsi tanpa batasan 3 tahun
Verifikasi dokumen mahasiswa (approve/reject + catatan)
Export arsip skripsi lebih dari 3 tahun ke Excel
Dashboard: statistik total mahasiswa, skripsi pending, skripsi selesai, menunggu verifikasi

Dosen

Login
Lihat daftar skripsi yang ia menjadi pembimbing
Review judul skripsi sesuai urutan (Dospem 1 harus approve dulu sebelum Dospem 2 bisa review)
Beri catatan saat approve maupun reject
Monitor progres upload dokumen mahasiswa bimbingan
Akses semua data tanpa batasan 3 tahun

Mahasiswa

Register dengan validasi kondisional NIM atau email domain kampus
Login setelah di-approve admin
Kelola profil: foto profil (public disk) dan data diri
Submit judul skripsi + nama jurnal (dengan validasi unique jurnal)
Lihat stepper 5 langkah progress skripsi
Lihat catatan dari Dospem atau Admin di tiap step
Upload 3 dokumen setelah judul disetujui 2 Dospem (private disk)
Download dokumen milik sendiri via temporary signed URL
Terima notifikasi sistem untuk setiap perubahan status


Struktur Folder
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   └── RegisterController.php
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── MahasiswaController.php
│   │   │   ├── DosenController.php
│   │   │   ├── NimWhitelistController.php
│   │   │   ├── ThesisController.php
│   │   │   └── SettingController.php
│   │   ├── Dosen/
│   │   │   ├── DashboardController.php
│   │   │   └── ThesisReviewController.php
│   │   └── Mahasiswa/
│   │       ├── DashboardController.php
│   │       ├── ProfileController.php
│   │       ├── ThesisController.php
│   │       └── DocumentController.php
│   ├── Middleware/
│   │   ├── RoleMiddleware.php
│   │   └── CheckAccountStatus.php
│   └── Requests/
│       ├── Auth/RegisterRequest.php
│       ├── Thesis/ThesisSubmitRequest.php
│       └── Thesis/DocumentUploadRequest.php
├── Models/
│   ├── User.php
│   ├── Mahasiswa.php
│   ├── Dosen.php
│   ├── Thesis.php
│   ├── ThesisSupervisor.php
│   ├── NimWhitelist.php
│   └── Setting.php
└── Scopes/
    └── ThreeYearScope.php

database/
├── migrations/
└── seeders/
    └── AdminSeeder.php   ← akun admin + setting domain default

Seeder
php// AdminSeeder.php

// Akun admin pertama
User::create([
    'username' => 'admin',
    'email'    => 'admin@univ.ac.id',
    'password' => bcrypt('admin123'),
    'role'     => 'admin',
    'status'   => 'active',
]);

// Setting domain email kampus default (bisa diubah via panel admin)
Setting::create([
    'key'   => 'allowed_email_domain',
    'value' => '@mahasiswa.univ.ac.id',
]);