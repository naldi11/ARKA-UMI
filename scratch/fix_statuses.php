<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Thesis;

$theses = Thesis::where('status', 'pending')->get();
$count = 0;
foreach ($theses as $t) {
    if ($t->supervisors()->count() >= 2) {
        $t->update(['status' => 'approved']);
        $count++;
    }
}
echo "Updated $count theses to 'approved'.\n";
