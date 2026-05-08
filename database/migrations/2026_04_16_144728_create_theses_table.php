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
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained()->onDelete('cascade'); // Pemilik skripsi
            $table->string('title'); // Judul skripsi
            $table->string('jurnal_name')->unique(); // Nama jurnal (wajib unik sesuai PRD)
            
            // Status: pending, dosen1_approved, dosen2_approved, finished, rejected
            $table->string('status')->default('pending'); 
            
            // Kolom dokumen (Private Storage)
            $table->string('doc_skripsi')->nullable();
            $table->string('doc_meja_hijau')->nullable();
            $table->string('doc_final')->nullable();
            
            $table->text('admin_notes')->nullable(); // Catatan dari admin saat verifikasi
            $table->timestamp('approved_at')->nullable(); // Waktu verifikasi final oleh admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theses');
    }
};
