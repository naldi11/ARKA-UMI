<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Thesis;

$t = Thesis::whereHas('mahasiswa', function($q) { $q->where('nim', '2022101010'); })->first();
if ($t) {
    echo "NIM: 2022101010\n";
    echo "Status: " . $t->status . "\n";
    echo "Doc Skripsi: " . ($t->doc_skripsi ? 'OK' : 'MISSING') . "\n";
    echo "Doc Meja Hijau: " . ($t->doc_meja_hijau ? 'OK' : 'MISSING') . "\n";
    echo "Doc Jurnal: " . ($t->doc_jurnal ? 'OK' : 'MISSING') . "\n";
    echo "Doc Target Jurnal: " . ($t->doc_target_jurnal ? 'OK' : 'MISSING') . "\n";
    echo "Doc SK 1: " . ($t->doc_sk_pembimbing_1 ? 'OK' : 'MISSING') . "\n";
    echo "Doc SK 2: " . ($t->doc_sk_pembimbing_2 ? 'OK' : 'MISSING') . "\n";
    echo "Doc Izin Penelitian: " . ($t->doc_izin_penelitian ? 'OK' : 'MISSING') . "\n";
    echo "Doc CD: " . ($t->doc_cd ? 'OK' : 'MISSING') . "\n";
}
