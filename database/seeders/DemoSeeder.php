<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\NimWhitelist;
use App\Models\Thesis;
use App\Models\ThesisSupervisor;

class DemoSeeder extends Seeder
{
    /**
     * Seeder data demo untuk testing UI.
     * Membuat: NIM Whitelist, Dosen (3), Mahasiswa (4), dan Skripsi (4) dengan berbagai status.
     * JALANKAN: php artisan db:seed --class=DemoSeeder
     * RESET:    php artisan migrate:fresh --seed (akan hapus semua data)
     */
    public function run(): void
    {
        // ============================================================
        // 1. NIM WHITELIST — Daftar NIM yang boleh mendaftar
        // ============================================================
        $nims = [
            ['nim' => '2021110001', 'name' => 'Andi Pratama'],
            ['nim' => '2021110002', 'name' => 'Dewi Lestari'],
            ['nim' => '2021110003', 'name' => 'Rizky Firmansyah'],
            ['nim' => '2021110004', 'name' => 'Nurul Hidayah'],
            ['nim' => '2021110005', 'name' => 'Bagas Saputra'],
            ['nim' => '2021110006', 'name' => 'Fitria Utami'],
            ['nim' => '2021110007', 'name' => 'Dimas Kurniawan'],
            ['nim' => '2021110008', 'name' => 'Sari Wulandari'],
        ];
        foreach ($nims as $data) {
            NimWhitelist::firstOrCreate(
                ['nim' => $data['nim']],
                ['name' => $data['name'], 'is_used' => false]
            );
        }
        $this->command->info('✅ NIM Whitelist seeded: ' . count($nims) . ' entri');

        // ============================================================
        // 2. DOSEN — 3 akun dosen dengan profil lengkap
        // ============================================================
        $dosenData = [
            [
                'name'      => 'Prof. Dr. Budi Santoso, M.Kom.',
                'username'  => 'budi.santoso',
                'email'     => 'budi.santoso@univ.ac.id',
                'nip'       => '198001012005011001',
                'nama_gelar'=> 'Prof. Dr. Budi Santoso, M.Kom.',
            ],
            [
                'name'      => 'Dr. Siti Rahayu, S.T., M.T.',
                'username'  => 'siti.rahayu',
                'email'     => 'siti.rahayu@univ.ac.id',
                'nip'       => '198505152010012003',
                'nama_gelar'=> 'Dr. Siti Rahayu, S.T., M.T.',
            ],
            [
                'name'      => 'Ir. Ahmad Fauzi, M.Sc.',
                'username'  => 'ahmad.fauzi',
                'email'     => 'ahmad.fauzi@univ.ac.id',
                'nip'       => '197803202003121002',
                'nama_gelar'=> 'Ir. Ahmad Fauzi, M.Sc.',
            ],
        ];

        $dosens = [];
        foreach ($dosenData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'username' => $data['username'],
                    'password' => Hash::make('password123'),
                    'role'     => 'dosen',
                    'status'   => 'active',
                ]
            );
            $dosen = Dosen::firstOrCreate(
                ['nip' => $data['nip']],
                ['user_id' => $user->id, 'nama_gelar' => $data['nama_gelar']]
            );
            $dosens[] = $dosen;
        }
        $this->command->info('✅ Dosen seeded: ' . count($dosens) . ' akun');

        // ============================================================
        // 3. MAHASISWA — 4 akun dengan status berbeda
        // ============================================================
        $mahasiswaData = [
            [
                'name'     => 'Andi Pratama',
                'username' => 'andi.pratama',
                'email'    => 'andi.pratama@student.ac.id',
                'nim'      => '2021110001',
                'status'   => 'active', // sudah aktif
            ],
            [
                'name'     => 'Dewi Lestari',
                'username' => 'dewi.lestari',
                'email'    => 'dewi.lestari@student.ac.id',
                'nim'      => '2021110002',
                'status'   => 'active',
            ],
            [
                'name'     => 'Rizky Firmansyah',
                'username' => 'rizky.firmansyah',
                'email'    => 'rizky.firmansyah@student.ac.id',
                'nim'      => '2021110003',
                'status'   => 'active',
            ],
            [
                'name'     => 'Nurul Hidayah',
                'username' => 'nurul.hidayah',
                'email'    => 'nurul.hidayah@student.ac.id',
                'nim'      => '2021110004',
                'status'   => 'pending', // menunggu verifikasi admin
            ],
        ];

        $mahasiswas = [];
        foreach ($mahasiswaData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'username' => $data['username'],
                    'password' => Hash::make('password123'),
                    'role'     => 'mahasiswa',
                    'status'   => $data['status'],
                ]
            );
            $mhs = Mahasiswa::firstOrCreate(
                ['nim' => $data['nim']],
                ['user_id' => $user->id]
            );
            $mahasiswas[] = $mhs;
        }
        $this->command->info('✅ Mahasiswa seeded: ' . count($mahasiswas) . ' akun (1 pending verifikasi)');

        // ============================================================
        // 4. SKRIPSI — 4 thesis dengan berbagai status
        // ============================================================
        $thesisData = [
            [
                'mahasiswa_idx' => 0, // Andi Pratama
                'title'         => 'Implementasi Machine Learning untuk Deteksi Dini Penyakit Tanaman Padi Berbasis Citra Digital',
                'jurnal_name'   => 'Journal of Agricultural Informatics',
                'status'        => 'pending', // belum ditugaskan dosen
            ],
            [
                'mahasiswa_idx' => 1, // Dewi Lestari
                'title'         => 'Sistem Informasi Manajemen Keuangan UMKM Berbasis Web dengan Framework Laravel',
                'jurnal_name'   => 'Indonesian Journal of Information Systems',
                'status'        => 'approved', // sudah ada dosen, sedang proses
                'dospem1_idx'   => 0, // Prof. Budi
                'dospem2_idx'   => 1, // Dr. Siti
            ],
            [
                'mahasiswa_idx' => 2, // Rizky Firmansyah
                'title'         => 'Analisis Sentimen Ulasan Produk E-Commerce Menggunakan Metode BERT dan SVM',
                'jurnal_name'   => 'Jurnal Teknologi Informasi dan Komunikasi',
                'status'        => 'uploaded', // sudah upload, menunggu verifikasi admin
                'dospem1_idx'   => 1, // Dr. Siti
                'dospem2_idx'   => 2, // Ir. Ahmad
            ],
            [
                'mahasiswa_idx' => 0, // Andi Pratama (skripsi lama - sudah lulus)
                'title'         => 'Rancang Bangun Aplikasi Monitoring Kehadiran Mahasiswa Berbasis QR Code',
                'jurnal_name'   => 'Jurnal Ilmu Komputer Nusantara',
                'status'        => 'finished', // lulus
                'dospem1_idx'   => 0,
                'dospem2_idx'   => 2,
                'approved_at'   => now()->subMonths(8),
            ],
        ];

        foreach ($thesisData as $data) {
            $mhs = $mahasiswas[$data['mahasiswa_idx']];

            // Cek jika sudah ada skripsi dengan judul sama (hindari duplicate)
            $thesis = Thesis::withoutGlobalScopes()->firstOrCreate(
                [
                    'mahasiswa_id' => $mhs->id,
                    'title'        => $data['title'],
                ],
                [
                    'jurnal_name' => $data['jurnal_name'],
                    'status'      => $data['status'],
                    'approved_at' => $data['approved_at'] ?? null,
                ]
            );

            // Assign dosen pembimbing jika ada
            if (isset($data['dospem1_idx'])) {
                ThesisSupervisor::firstOrCreate(
                    ['thesis_id' => $thesis->id, 'type' => 1],
                    ['dosen_id' => $dosens[$data['dospem1_idx']]->id]
                );
            }
            if (isset($data['dospem2_idx'])) {
                ThesisSupervisor::firstOrCreate(
                    ['thesis_id' => $thesis->id, 'type' => 2],
                    ['dosen_id' => $dosens[$data['dospem2_idx']]->id]
                );
            }
        }
        $this->command->info('✅ Skripsi seeded: ' . count($thesisData) . ' record (pending, approved, uploaded, finished)');

        // ============================================================
        // Ringkasan Login Credentials
        // ============================================================
        $this->command->newLine();
        $this->command->info('╔══════════════════════════════════════════════════╗');
        $this->command->info('║           DEMO LOGIN CREDENTIALS                ║');
        $this->command->info('╠══════════════════════════════════════════════════╣');
        $this->command->info('║ DOSEN 1  : budi.santoso / password123           ║');
        $this->command->info('║ DOSEN 2  : siti.rahayu  / password123           ║');
        $this->command->info('║ DOSEN 3  : ahmad.fauzi  / password123           ║');
        $this->command->info('╠══════════════════════════════════════════════════╣');
        $this->command->info('║ MAHASISWA 1: andi.pratama    / password123      ║');
        $this->command->info('║ MAHASISWA 2: dewi.lestari    / password123      ║');
        $this->command->info('║ MAHASISWA 3: rizky.firmansyah/ password123      ║');
        $this->command->info('║ MAHASISWA 4: nurul.hidayah   / password123      ║');
        $this->command->info('║             (status: PENDING - perlu disetujui) ║');
        $this->command->info('╚══════════════════════════════════════════════════╝');
    }
}
