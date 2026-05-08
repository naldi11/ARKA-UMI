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
        Schema::create('angkatans', function (Blueprint $table) {
            $table->id();
            $table->string('year', 4)->unique(); // Tahun masuk, misal: 2021
            $table->boolean('is_open')->default(true); // Status buka/tutup pendaftaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angkatans');
    }
};
