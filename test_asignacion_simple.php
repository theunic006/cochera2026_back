<?php
// Prueba simple de asignación propietario-vehículo
$base_url = 'http://127.0.0.1:8000/api';
$token = '47|GOvJ6tHFY045Y8kgRjxW4iykpwksvG9ls90cI54272e3af4d';

echo "=== PRUEBA SIMPLE ASIGNACIÓN ===\n\n";

// Datos de prueba con IDs que sabemos que existen
$asignacion = [
    'vehiculo_id' => 1,      // Vehículo que sabemos que existe
    'propietario_id' => 1,   // Propietario que sabemos que existe
    'fecha_inicio' => '2025-09-26'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ],
        'content' => json_encode($asignacion)
    ]
]);

echo "📤 Enviando datos:\n";
echo json_encode($asignacion, JSON_PRETTY_PRINT) . "\n\n";

$response = @file_get_contents("$base_url/vehiculo-propietarios", false, $context);

echo "📥 Headers de respuesta:\n";
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        echo "   $header\n";
    }
}
echo "\n";

if ($response === false) {
    echo "❌ Error en la petición\n";
} else {
    echo "✅ Respuesta recibida:\n";
    $data = json_decode($response, true);
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
}

echo "\n=== FIN ===\n";
