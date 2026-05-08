<?php

namespace App\Http\Requests\Auth;

use App\Models\Angkatan;
use App\Models\Mahasiswa;
use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    /**
     * Tentukan apakah user punya izin menjalankan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi dasar.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nim' => 'nullable|string', // Validasi detail ada di withValidator
        ];
    }

    /**
     * Validasi kondisional sesuai PRD (Kondisi A & B).
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            
            // Cek domain email
            $emailParts = explode('@', $data['email'] ?? '');
            $emailDomain = count($emailParts) > 1 ? '@' . $emailParts[1] : '';
            
            $domainKampus = Setting::where('key', 'allowed_email_domain')->value('value'); 
            $isEmailKampus = ($emailDomain === $domainKampus);
            $nimDiisi = !empty($data['nim']);

            // KONDISI B: Email kampus -> NIM WAJIB diisi
            if ($isEmailKampus && !$nimDiisi) {
                $validator->errors()->add('nim', 'Email institusi wajib disertai NIM.');
            }

            // KONDISI GAGAL: Bukan email kampus && NIM tidak diisi
            if (!$isEmailKampus && !$nimDiisi) {
                $validator->errors()->add('nim', 'Masukkan NIM terdaftar atau gunakan email institusi.');
            }

            // Validasi NIM berbasis angkatan
            if ($nimDiisi) {
                $nim    = $data['nim'];
                $tahun  = substr($nim, 0, 4);

                // Cek apakah angkatan dibuka
                $angkatan = Angkatan::where('year', $tahun)->first();

                if (!$angkatan) {
                    $validator->errors()->add('nim', "Angkatan {$tahun} belum dibuka pendaftarannya. Hubungi admin.");
                } elseif (!$angkatan->is_open) {
                    $validator->errors()->add('nim', "Pendaftaran angkatan {$tahun} saat ini ditutup.");
                }

                // Cek NIM sudah dipakai mahasiswa lain
                if (Mahasiswa::where('nim', $nim)->exists()) {
                    $validator->errors()->add('nim', 'NIM tersebut sudah terdaftar di sistem.');
                }
            }
        });
    }

    /**
     * Pesan error kustom (opsional).
     */
    public function messages(): array
    {
        return [
            'nim.unique' => 'NIM sudah terdaftar.',
        ];
    }
}
