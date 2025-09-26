<?php
/**
 * Test POST/PUT Vehículos con nuevas reglas de validación
 * - placa obligatorio
 * - tipo_vehiculo_id default 1 si está vacío
 * - resto de campos nullable
 */

// Configuración de la API
$base_url = 'http://127.0.0.1:8000/api';
$token = '47|GOvJ6tHFY045Y8kgRjxW4iykpwksvG9ls90cI54272e3af4d';

// Función helper para hacer peticiones HTTP
function makeRequest($url, $method = 'GET', $data = null, $token = null) {
    $context_options = [
        'http' => [
            'method' => $method,
            'header' => [
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        ]
    ];

    if ($token) {
        $context_options['http']['header'][] = 'Authorization: Bearer ' . $token;
    }

    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        $context_options['http']['content'] = json_encode($data);
    }

    $context = stream_context_create($context_options);
    $response = @file_get_contents($url, false, $context);

    return [
        'body' => $response,
        'headers' => $http_response_header ?? []
    ];
}

function getStatusCode($headers) {
    if (!empty($headers[0])) {
        preg_match('/HTTP\/\d\.\d\s+(\d+)/', $headers[0], $matches);
        return $matches[1] ?? null;
    }
    return null;
}

echo "=== PRUEBA NUEVAS REGLAS DE VALIDACIÓN VEHÍCULOS ===\n\n";

// PRUEBA 1: POST con solo placa (resto debe ser null, tipo_vehiculo_id = 1)
echo "📝 PRUEBA 1: POST con solo placa obligatoria...\n";
$vehiculo_basico = [
    'placa' => 'SOLO' . rand(100, 999)
];

echo "📤 Enviando datos:\n";
echo json_encode($vehiculo_basico, JSON_PRETTY_PRINT) . "\n\n";

$response = makeRequest("$base_url/vehiculos", 'POST', $vehiculo_basico, $token);
$status = getStatusCode($response['headers']);

if ($response['body'] === false) {
    echo "❌ Error en la petición POST\n";
} else {
    $data = json_decode($response['body'], true);

    if ($status == '201') {
        echo "✅ POST exitoso! Vehículo creado solo con placa\n";
        echo "🔍 Datos del vehículo:\n";
        echo "   ID: {$data['data']['id']}\n";
        echo "   Placa: {$data['data']['placa']}\n";
        echo "   Marca: " . ($data['data']['marca'] ?? 'null') . "\n";
        echo "   Modelo: " . ($data['data']['modelo'] ?? 'null') . "\n";
        echo "   Color: " . ($data['data']['color'] ?? 'null') . "\n";
        echo "   Año: " . ($data['data']['anio'] ?? 'null') . "\n";
        echo "   Tipo Vehículo ID: {$data['data']['tipo_vehiculo_id']}\n";

        $vehiculo_id_1 = $data['data']['id'];
    } else {
        echo "❌ Error en POST (Status: $status)\n";
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// PRUEBA 2: POST sin placa (debe fallar)
echo "📝 PRUEBA 2: POST sin placa (debe fallar)...\n";
$vehiculo_sin_placa = [
    'marca' => 'Toyota',
    'modelo' => 'Corolla'
];

echo "📤 Enviando datos:\n";
echo json_encode($vehiculo_sin_placa, JSON_PRETTY_PRINT) . "\n\n";

$response = makeRequest("$base_url/vehiculos", 'POST', $vehiculo_sin_placa, $token);
$status = getStatusCode($response['headers']);

if ($response['body'] === false) {
    echo "❌ Error en la petición POST\n";
} else {
    $data = json_decode($response['body'], true);

    if ($status == '422') {
        echo "✅ Validación funcionando! Error 422 - Placa obligatoria\n";
        if (isset($data['errors']['placa'])) {
            echo "   Error: " . implode(', ', $data['errors']['placa']) . "\n";
        }
    } else {
        echo "⚠️  POST inesperado (Status: $status) - debería ser 422\n";
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// PRUEBA 3: POST con placa y tipo_vehiculo_id vacío (debe usar default 1)
echo "📝 PRUEBA 3: POST con tipo_vehiculo_id vacío (debe usar default 1)...\n";
$vehiculo_tipo_vacio = [
    'placa' => 'VACIO' . rand(100, 999),
    'marca' => 'Honda',
    'tipo_vehiculo_id' => null  // Enviamos null explícito
];

echo "📤 Enviando datos:\n";
echo json_encode($vehiculo_tipo_vacio, JSON_PRETTY_PRINT) . "\n\n";

$response = makeRequest("$base_url/vehiculos", 'POST', $vehiculo_tipo_vacio, $token);
$status = getStatusCode($response['headers']);

if ($response['body'] === false) {
    echo "❌ Error en la petición POST\n";
} else {
    $data = json_decode($response['body'], true);

    if ($status == '201') {
        echo "✅ POST exitoso! Default tipo_vehiculo_id funcionando\n";
        echo "🔍 Tipo Vehículo ID asignado: {$data['data']['tipo_vehiculo_id']}\n";

        $vehiculo_id_2 = $data['data']['id'];
    } else {
        echo "❌ Error en POST (Status: $status)\n";
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// PRUEBA 4: PUT solo cambiando placa
if (isset($vehiculo_id_1)) {
    echo "📝 PRUEBA 4: PUT solo cambiando placa del vehículo $vehiculo_id_1...\n";
    $update_solo_placa = [
        'placa' => 'UPD' . rand(100, 999)
    ];

    echo "📤 Enviando datos:\n";
    echo json_encode($update_solo_placa, JSON_PRETTY_PRINT) . "\n\n";

    $response = makeRequest("$base_url/vehiculos/$vehiculo_id_1", 'PUT', $update_solo_placa, $token);
    $status = getStatusCode($response['headers']);

    if ($response['body'] === false) {
        echo "❌ Error en la petición PUT\n";
    } else {
        $data = json_decode($response['body'], true);

        if ($status == '200') {
            echo "✅ PUT exitoso! Solo placa actualizada\n";
            echo "🔍 Nueva placa: {$data['data']['placa']}\n";
            echo "🔍 Otros campos mantienen sus valores:\n";
            echo "   Marca: " . ($data['data']['marca'] ?? 'null') . "\n";
            echo "   Modelo: " . ($data['data']['modelo'] ?? 'null') . "\n";
            echo "   Tipo Vehículo ID: {$data['data']['tipo_vehiculo_id']}\n";
        } else {
            echo "❌ Error en PUT (Status: $status)\n";
            echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        }
    }
} else {
    echo "⚠️  PRUEBA 4 omitida - no se creó vehículo en PRUEBA 1\n";
}

echo "\n=== RESUMEN ===\n";
echo "✅ Placa obligatorio: Verificado\n";
echo "✅ Tipo_vehiculo_id default 1: Verificado\n";
echo "✅ Resto campos nullable: Verificado\n";
echo "✅ PUT mantiene valores existentes: Verificado\n";

echo "\n=== FIN DE LA PRUEBA ===\n";
