<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Angkatan;
use App\Models\Thesis;
use App\Models\ThesisSupervisor;
use App\Models\Setting;

class TestingSeeder extends Seeder
{
    /**
     * Seeder lengkap untuk testing semua role dan alur pendaftaran per-Angkatan.
     * JALANKAN: php artisan db:seed --class=TestingSeeder
     */
    public function run(): void
    {
        // 1. SETTINGS — Domain email kampus
        Setting::updateOrCreate(
            ['key' => 'allowed_email_domain'],
            ['value' => '@mahasiswa.ac.id']
        );
        $this->command->info('✅ Setting: Email domain set to @mahasiswa.ac.id');

        // 2. AKSES ANGKATAN — Buka akses pendaftaran untuk 2021-2024
        $years = [2021, 2022, 2023, 2024];
        foreach ($years as $year) {
            Angkatan::updateOrCreate(
                ['year' => $year],
                ['is_open' => true]
            );
        }
        $this->command->info('✅ Angkatan: Akses dibuka untuk ' . implode(', ', $years));

        // 3. ADMIN ACCOUNT
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator Utama',
                'email'    => 'admin@kampus.ac.id',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'status'   => 'active',
            ]
        );
        $this->command->info('✅ User: Admin account created (admin / password)');

        // 4. DOSEN ACCOUNTS (2 Dosen)
        $dosenNames = ['Dr. Hendra Saputra, M.T.', 'Siti Aminah, S.Kom., M.Cs.'];
        $dosens = [];
        foreach ($dosenNames as $i => $name) {
            $user = User::updateOrCreate(
                ['username' => 'dosen' . ($i + 1)],
                [
                    'name'     => $name,
                    'email'    => 'dosen' . ($i + 1) . '@kampus.ac.id',
                    'password' => Hash::make('password'),
                    'role'     => 'dosen',
                    'status'   => 'active',
                ]
            );
            $dosens[] = Dosen::updateOrCreate(
                ['user_id' => $user->id],
                ['nip' => '1980' . ($i + 10) . '01001', 'nama_gelar' => $name]
            );
        }
        $this->command->info('✅ User: 2 Dosen accounts created (dosen1, dosen2 / password)');

        // 5. MAHASISWA ACCOUNTS (Satu per Angkatan)
        $mhsData = [
            ['year' => 2021, 'status' => 'active'],
            ['year' => 2022, 'status' => 'active'],
            ['year' => 2023, 'status' => 'active'],
            ['year' => 2024, 'status' => 'pending'],
        ];

        $mahasiswas = [];
        foreach ($mhsData as $data) {
            $user = User::updateOrCreate(
                ['username' => 'mhs' . $data['year']],
                [
                    'name'     => 'Mahasiswa ' . $data['year'],
                    'email'    => 'mhs' . $data['year'] . '@mahasiswa.ac.id',
                    'password' => Hash::make('password'),
                    'role'     => 'mahasiswa',
                    'status'   => $data['status'],
                ]
            );
            $mahasiswas[$data['year']] = Mahasiswa::updateOrCreate(
                ['user_id' => $user->id],
                ['nim' => $data['year'] . '110001']
            );
        }
        $this->command->info('✅ User: 4 Mahasiswa created (mhs2021-mhs2024 / password)');

        // 6. SAMPLE THESES (Skripsi)
        // a. Skripsi Lulus (2021)
        $thesis1 = Thesis::withoutGlobalScopes()->updateOrCreate(
            ['mahasiswa_id' => $mahasiswas[2021]->id],
            [
                'title'       => 'Analisis Keamanan Jaringan pada Infrastruktur Cloud Hybrid',
                'jurnal_name' => 'Journal of Cyber Security',
                'status'      => 'finished',
                'approved_at' => now()->subMonths(6),
            ]
        );
        ThesisSupervisor::updateOrCreate(['thesis_id' => $thesis1->id, 'type' => 1], ['dosen_id' => $dosens[0]->id]);
        ThesisSupervisor::updateOrCreate(['thesis_id' => $thesis1->id, 'type' => 2], ['dosen_id' => $dosens[1]->id]);

        // b. Skripsi Berjalan (2022)
        $thesis2 = Thesis::withoutGlobalScopes()->updateOrCreate(
            ['mahasiswa_id' => $mahasiswas[2022]->id],
            [
                'title'       => 'Pengembangan Sistem E-Learning Berbasis Microservices',
                'jurnal_name' => 'Indonesian Education Journal',
                'status'      => 'approved',
            ]
        );
        ThesisSupervisor::updateOrCreate(['thesis_id' => $thesis2->id, 'type' => 1], ['dosen_id' => $dosens[1]->id]);

        // c. Skripsi Pending (2023)
        Thesis::withoutGlobalScopes()->updateOrCreate(
            ['mahasiswa_id' => $mahasiswas[2023]->id],
            [
                'title'       => 'Optimasi Algoritma Klasifikasi untuk Deteksi Penyakit Daun',
                'jurnal_name' => 'AgriTech Journal',
                'status'      => 'pending',
            ]
        );
        $this->command->info('✅ Thesis: Sample theses created (Finished, Approved, Pending)');

        $this->command->newLine();
        $this->command->info('🚀 Testing environment ready!');
        $this->command->info('Admin: admin / password');
        $this->command->info('Dosen: dosen1 / password');
        $this->command->info('MHS: mhs2021 / password (NIM: 2021110001)');
    }
}
