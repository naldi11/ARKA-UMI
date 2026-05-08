<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nim_whitelists', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique(); // NIM yang diizinkan daftar
            $table->string('name'); // Nama mahasiswa (opsional untuk validasi awal)
            $table->boolean('is_used')->default(false); // Apakah sudah dipakai register
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nim_whitelists');
    }
};
