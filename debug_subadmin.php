<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Club;
use App\Models\Module;

$user = User::find(9); // SubAdmin
echo "Usuario: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";
echo "Rol: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
echo "Club ID: " . $user->club_id . "\n";

if ($user->club) {
    echo "\nClub: " . $user->club->name . "\n";

    echo "\n--- Validando @module directive ---\n";

    $moduleSlugs = ['tournaments', 'financial', 'classes'];

    foreach ($moduleSlugs as $slug) {
        $exists = $user->club->hasModule($slug);
        $status = $exists ? "✓" : "✗";
        echo "$status @module('$slug') = " . ($exists ? "true" : "false") . "\n";
    }

    echo "\n--- Relación club->modules ---\n";
    $user->club->load('modules');
    echo "Módulos cargados: " . $user->club->modules()->count() . "\n";
    foreach ($user->club->modules as $m) {
        echo "- " . $m->name . " (slug: " . $m->slug . ")\n";
    }
} else {
    echo "⚠ Usuario sin club asignado\n";
}
