<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Thesis;
use App\Models\Mahasiswa;

$mahasiswas = Mahasiswa::with('thesis')->get();
foreach ($mahasiswas as $m) {
    echo "MHS: " . $m->nim . " | Thesis Status: " . ($m->thesis ? $m->thesis->status : 'NULL') . "\n";
}
