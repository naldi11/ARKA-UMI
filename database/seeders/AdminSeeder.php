<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat akun admin pertama dan setting default sesuai PRD.
     */
    public function run(): void
    {
        // Akun Admin Utama
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@univ.ac.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Setting Domain Email Kampus (Default)
        Setting::updateOrCreate(
            ['key' => 'allowed_email_domain'],
            ['value' => '@mahasiswa.univ.ac.id']
        );
    }
}
