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
        Schema::create('thesis_supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')->constrained()->onDelete('cascade'); // Relasi ke skripsi
            $table->foreignId('dosen_id')->constrained()->onDelete('cascade'); // Relasi ke dosen pembimbing
            $table->enum('type', [1, 2]); // Tipe pembimbing: 1 (Dospem 1), 2 (Dospem 2)
            $table->text('review_notes')->nullable(); // Catatan review saat approve/reject
            $table->timestamp('reviewed_at')->nullable(); // Waktu review dilakukan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_supervisors');
    }
};
