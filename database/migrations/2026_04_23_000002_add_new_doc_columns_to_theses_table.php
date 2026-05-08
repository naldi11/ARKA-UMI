<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('theses', function (Blueprint $table) {
            $table->string('doc_jurnal')->nullable()->after('doc_meja_hijau');
            $table->string('doc_cd')->nullable()->after('doc_jurnal');
            // title_proposal_id untuk tracing proposal yang disetujui
            $table->unsignedBigInteger('title_proposal_id')->nullable()->after('mahasiswa_id');
        });
    }

    public function down(): void
    {
        Schema::table('theses', function (Blueprint $table) {
            $table->dropColumn(['doc_jurnal', 'doc_cd', 'title_proposal_id']);
        });
    }
};
