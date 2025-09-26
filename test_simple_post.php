<?php
// Prueba simple POST vehiculo
$base_url = 'http://127.0.0.1:8000/api';
$token = '47|GOvJ6tHFY045Y8kgRjxW4iykpwksvG9ls90cI54272e3af4d';

$vehiculo_data = [
    'placa' => 'TEST' . rand(100, 999),
    'marca' => 'Toyota',
    'modelo' => 'Corolla',
    'color' => 'Negro',
    'anio' => 2023,
    'tipo_vehiculo_id' => 1
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ],
        'content' => json_encode($vehiculo_data)
    ]
]);

echo "Enviando datos:\n";
echo json_encode($vehiculo_data, JSON_PRETTY_PRINT) . "\n\n";

$response = @file_get_contents("$base_url/vehiculos", false, $context);

if ($response === false) {
    echo "❌ Error en la petición\n";
    // Mostrar headers de error
    if (isset($http_response_header)) {
        echo "Headers:\n";
        foreach ($http_response_header as $header) {
            echo "  $header\n";
        }
    }
} else {
    echo "✅ Respuesta recibida:\n";
    $data = json_decode($response, true);
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
}
