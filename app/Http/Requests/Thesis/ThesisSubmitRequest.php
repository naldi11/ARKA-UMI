<?php

namespace App\Http\Requests\Thesis;

use Illuminate\Foundation\Http\FormRequest;

class ThesisSubmitRequest extends FormRequest
{
    /**
     * Tentukan apakah user punya izin menjalankan request ini.
     */
    public function authorize(): bool
    {
        return auth()->user()->isMahasiswa();
    }

    /**
     * Aturan validasi untuk submit judul.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:500',
            'jurnal_name' => 'required|string|max:255|unique:theses,jurnal_name', // Harus unik sesuai PRD
        ];
    }

    public function messages(): array
    {
        return [
            'jurnal_name.unique' => 'Nama jurnal tersebut sudah terdaftar di sistem. Gunakan jurnal lain.',
        ];
    }
}
