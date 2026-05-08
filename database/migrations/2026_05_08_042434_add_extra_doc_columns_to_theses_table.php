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
        Schema::table('theses', function (Blueprint $table) {
            $table->string('doc_target_jurnal')->nullable()->after('jurnal_name');
            $table->string('doc_sk_pembimbing_1')->nullable()->after('doc_skripsi');
            $table->string('doc_sk_pembimbing_2')->nullable()->after('doc_sk_pembimbing_1');
            $table->string('doc_izin_penelitian')->nullable()->after('doc_sk_pembimbing_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theses', function (Blueprint $table) {
            $table->dropColumn([
                'doc_target_jurnal',
                'doc_sk_pembimbing_1',
                'doc_sk_pembimbing_2',
                'doc_izin_penelitian'
            ]);
        });
    }
};
