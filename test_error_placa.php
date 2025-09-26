<?php
// Prueba específica para verificar error sin placa
$base_url = 'http://127.0.0.1:8000/api';
$token = '47|GOvJ6tHFY045Y8kgRjxW4iykpwksvG9ls90cI54272e3af4d';

echo "=== PRUEBA ERROR SIN PLACA ===\n\n";

$vehiculo_sin_placa = [
    'marca' => 'Toyota',
    'modelo' => 'Corolla'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ],
        'content' => json_encode($vehiculo_sin_placa)
    ]
]);

echo "📤 Enviando datos sin placa:\n";
echo json_encode($vehiculo_sin_placa, JSON_PRETTY_PRINT) . "\n\n";

$response = @file_get_contents("$base_url/vehiculos", false, $context);

echo "📥 Headers de respuesta:\n";
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        echo "   $header\n";
    }
}
echo "\n";

if ($response === false) {
    echo "❌ Error en la petición (esperado para validación)\n";
} else {
    echo "📋 Respuesta del servidor:\n";
    $data = json_decode($response, true);
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n";

    // Verificar status code
    if (isset($http_response_header[0])) {
        preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0], $matches);
        $status_code = $matches[1] ?? null;

        if ($status_code == '422') {
            echo "\n✅ Validación funcionando correctamente! Error 422\n";
            if (isset($data['errors']['placa'])) {
                echo "🔍 Error de placa: " . implode(', ', $data['errors']['placa']) . "\n";
            }
        } else {
            echo "\n⚠️  Status inesperado: $status_code\n";
        }
    }
}

echo "\n=== FIN ===\n";
