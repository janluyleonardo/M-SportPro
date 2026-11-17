<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Club;
use App\Models\Module;

$club = Club::find(2);

if (!$club) {
    echo "Club not found\n";
    exit;
}

echo "Club: " . $club->name . " (ID: " . $club->id . ")\n";
echo "\nMódulos habilitados para este club:\n";

$enabledModules = $club->modules()->get();

if ($enabledModules->isEmpty()) {
    echo "⚠ No hay módulos habilitados\n";
} else {
    foreach ($enabledModules as $m) {
        echo "✓ " . $m->name . " (ID: " . $m->id . ", Slug: " . $m->slug . ")\n";
    }
}

echo "\n--- Todos los módulos disponibles en el sistema ---\n";
$allModules = Module::all();

foreach ($allModules as $m) {
    echo "- " . $m->name . " (ID: " . $m->id . ", Slug: " . $m->slug . ")\n";
}
