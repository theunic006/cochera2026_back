<?php
// Crear propietario de prueba y asignar a vehículo
$base_url = 'http://127.0.0.1:8000/api';
$token = '47|GOvJ6tHFY045Y8kgRjxW4iykpwksvG9ls90cI54272e3af4d';

echo "=== CREAR PROPIETARIO Y ASIGNAR ===\n\n";

// PASO 1: Crear propietario
echo "📝 PASO 1: Creando propietario...\n";
$propietario_data = [
    'nombre' => 'Juan Carlos Pérez',
    'telefono' => '987654321',
    'tipo_boleta' => 'DNI',
    'numero_boleta' => '12345678'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ],
        'content' => json_encode($propietario_data)
    ]
]);

echo "📤 Enviando datos:\n";
echo json_encode($propietario_data, JSON_PRETTY_PRINT) . "\n\n";

$response = @file_get_contents("$base_url/propietarios", false, $context);

if ($response === false) {
    echo "❌ Error al crear propietario\n";
    exit(1);
}

$propietario_creado = json_decode($response, true);
echo "✅ Propietario creado:\n";
echo json_encode($propietario_creado, JSON_PRETTY_PRINT) . "\n\n";

$propietario_id = $propietario_creado['data']['id'];
echo "🆔 ID del propietario: $propietario_id\n\n";

echo str_repeat("-", 50) . "\n\n";

// PASO 2: Asignar propietario al vehículo
echo "📝 PASO 2: Asignando propietario al vehículo...\n";
$asignacion_data = [
    'vehiculo_id' => 1,  // Vehículo ABC123
    'propietario_id' => $propietario_id,
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
        'content' => json_encode($asignacion_data)
    ]
]);

echo "📤 Enviando datos:\n";
echo json_encode($asignacion_data, JSON_PRETTY_PRINT) . "\n\n";

$response = @file_get_contents("$base_url/vehiculo-propietarios", false, $context);

echo "📥 Headers de respuesta:\n";
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        echo "   $header\n";
    }
}
echo "\n";

if ($response === false) {
    echo "❌ Error en la asignación\n";
} else {
    $data = json_decode($response, true);
    echo "✅ Respuesta de la asignación:\n";
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n";

    if (isset($data['success']) && $data['success']) {
        echo "\n🎉 ¡Asignación exitosa!\n";
        echo "🔍 Mensaje: {$data['message']}\n";
    }
}

echo "\n=== FIN ===\n";
