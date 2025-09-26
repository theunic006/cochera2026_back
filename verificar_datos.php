<?php
// Verificar datos existentes
$base_url = 'http://127.0.0.1:8000/api';
$token = '47|GOvJ6tHFY045Y8kgRjxW4iykpwksvG9ls90cI54272e3af4d';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ]
    ]
]);

echo "=== VERIFICAR DATOS EXISTENTES ===\n\n";

echo "📋 VEHÍCULOS:\n";
$response = @file_get_contents("$base_url/vehiculos", false, $context);
if ($response !== false) {
    $vehiculos = json_decode($response, true);
    if (isset($vehiculos['data'])) {
        foreach ($vehiculos['data'] as $v) {
            echo "   ID: {$v['id']}, Placa: {$v['placa']}\n";
        }
    } else {
        echo "   Estructura inesperada o sin datos\n";
    }
} else {
    echo "   ❌ Error al obtener vehículos\n";
}

echo "\n📋 PROPIETARIOS:\n";
$response = @file_get_contents("$base_url/propietarios", false, $context);
if ($response !== false) {
    $propietarios = json_decode($response, true);
    if (isset($propietarios['data'])) {
        foreach ($propietarios['data'] as $p) {
            echo "   ID: {$p['id']}, Nombre: " . ($p['nombre'] ?? 'Sin nombre') . "\n";
        }
    } else {
        echo "   Estructura inesperada o sin datos\n";
    }
} else {
    echo "   ❌ Error al obtener propietarios\n";
}

echo "\n=== FIN ===\n";
