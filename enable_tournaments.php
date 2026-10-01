<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Club;
use App\Models\Module;

$club = Club::find(1); // Club deportivo jackeline fs
$tournamentsModule = Module::where('slug', 'tournaments')->first();

if ($club && $tournamentsModule) {
    // Verificar si ya está asignado
    if (!$club->modules()->where('module_id', $tournamentsModule->id)->exists()) {
        $club->modules()->attach($tournamentsModule->id);
        echo "✓ Módulo 'Torneos' habilitado para " . $club->name . "\n";
    } else {
        echo "✓ Módulo 'Torneos' ya estaba habilitado para " . $club->name . "\n";
    }

    echo "\nMódulos ahora habilitados para " . $club->name . ":\n";
    $club->load('modules');
    foreach ($club->modules as $m) {
        echo "- " . $m->name . " (slug: " . $m->slug . ")\n";
    }
} else {
    echo "Error: Club o módulo no encontrado\n";
}
