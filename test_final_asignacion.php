<?php
// Prueba final con datos existentes
$base_url = 'http://127.0.0.1:8000/api';
$token = '47|GOvJ6tHFY045Y8kgRjxW4iykpwksvG9ls90cI54272e3af4d';

echo "=== PRUEBA FINAL ASIGNACIÓN VEHÍCULO-PROPIETARIO ===\n\n";

// Usar propietario ID 2 que existe
$asignacion = [
    'vehiculo_id' => 1,    // Vehículo ABC123
    'propietario_id' => 2  // Propietario ID 2
];

echo "📝 Asignando propietario ID 2 al vehículo ID 1...\n";
echo "📤 Datos:\n";
echo json_encode($asignacion, JSON_PRETTY_PRINT) . "\n\n";

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ),
        'content' => json_encode($asignacion),
        'ignore_errors' => true
    ]
]);

$response = file_get_contents("$base_url/vehiculo-propietarios", false, $context);

echo "📥 Headers:\n";
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        echo "   $header\n";
    }
}

echo "\n📋 Respuesta:\n";
if ($response !== false) {
    $data = json_decode($response, true);
    if ($data) {
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";

        if (isset($data['success']) && $data['success']) {
            echo "\n🎉 ¡ÉXITO! Propietario asignado correctamente\n";
        } elseif (isset($data['message'])) {
            echo "\nℹ️  Mensaje: {$data['message']}\n";
        }
    } else {
        echo "Raw response: $response\n";
    }
} else {
    echo "❌ No response\n";
}

echo "\n" . str_repeat("=", 50) . "\n";

// Verificar la asignación
echo "\n📝 Verificando propietarios del vehículo...\n";
$response2 = file_get_contents("$base_url/vehiculo-propietarios?vehiculo_id=1", false, stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => array(
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ),
        'ignore_errors' => true
    ]
]));

if ($response2 !== false) {
    $data2 = json_decode($response2, true);
    if ($data2 && isset($data2['data']['propietarios'])) {
        echo "✅ Propietarios del vehículo ABC123:\n";
        foreach ($data2['data']['propietarios'] as $prop) {
            echo "   - ID: {$prop['id']}, Nombre: " . ($prop['nombre'] ?? 'Sin nombre') . "\n";
        }
    } else {
        echo "⚠️  Estructura de respuesta inesperada\n" . json_encode($data2, JSON_PRETTY_PRINT) . "\n";
    }
} else {
    echo "❌ Error al verificar\n";
}

echo "\n=== FIN DE LA PRUEBA ===\n";
