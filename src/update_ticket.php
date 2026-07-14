<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$g = App\Models\Gangguan::find(1);
if ($g) {
    $g->status = 'Open';
    $g->save();
    $g->status = 'Diverifikasi';
    $g->save();
    echo "Successfully updated ticket verified data.\n";
} else {
    echo "Ticket not found.\n";
}
