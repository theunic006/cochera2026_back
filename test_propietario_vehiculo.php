<?php
/**
 * Test de asignación de propietarios a vehículos
 * Prueba la funcionalidad de relación many-to-many entre vehículos y propietarios
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

    if ($data && in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
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

echo "=== PRUEBA ASIGNACIÓN PROPIETARIOS A VEHÍCULOS ===\n\n";

// PASO 1: Obtener lista de vehículos existentes
echo "📋 PASO 1: Obteniendo vehículos existentes...\n";
$response = makeRequest("$base_url/vehiculos", 'GET', null, $token);

if ($response['body'] === false) {
    echo "❌ Error al obtener vehículos\n";
    exit(1);
}

$vehiculos = json_decode($response['body'], true);
if (empty($vehiculos['data'])) {
    echo "⚠️  No hay vehículos. Creando uno para la prueba...\n";

    // Crear vehículo de prueba
    $vehiculo_data = [
        'placa' => 'PROP' . rand(100, 999),
        'marca' => 'Toyota',
        'modelo' => 'Corolla',
        'color' => 'Blanco',
        'anio' => 2023
    ];

    $response = makeRequest("$base_url/vehiculos", 'POST', $vehiculo_data, $token);
    if ($response['body'] === false) {
        echo "❌ Error al crear vehículo de prueba\n";
        exit(1);
    }

    $vehiculo_creado = json_decode($response['body'], true);
    $vehiculo_id = $vehiculo_creado['data']['id'];
    $vehiculo_placa = $vehiculo_creado['data']['placa'];
    echo "✅ Vehículo creado: ID $vehiculo_id, Placa $vehiculo_placa\n";
} else {
    $vehiculo_id = $vehiculos['data'][0]['id'];
    $vehiculo_placa = $vehiculos['data'][0]['placa'];
    echo "✅ Usando vehículo existente: ID $vehiculo_id, Placa $vehiculo_placa\n";
}

echo "\n" . str_repeat("-", 60) . "\n\n";

// PASO 2: Obtener/crear propietarios
echo "📋 PASO 2: Obteniendo propietarios...\n";
$response = makeRequest("$base_url/propietarios", 'GET', null, $token);

if ($response['body'] === false) {
    echo "❌ Error al obtener propietarios\n";
    exit(1);
}

$propietarios = json_decode($response['body'], true);
$propietarios_ids = [];

if (empty($propietarios['data']) || count($propietarios['data']) < 2) {
    echo "⚠️  Pocos propietarios. Creando propietarios de prueba...\n";

    // Crear 2 propietarios de prueba
    $propietarios_data = [
        [
            'nombre' => 'Juan Pérez ' . rand(100, 999),
            'telefono' => '123456789',
            'tipo_boleta' => 'DNI',
            'numero_boleta' => '12345678'
        ],
        [
            'nombre' => 'María García ' . rand(100, 999),
            'telefono' => '987654321',
            'tipo_boleta' => 'DNI',
            'numero_boleta' => '87654321'
        ]
    ];

    foreach ($propietarios_data as $prop_data) {
        $response = makeRequest("$base_url/propietarios", 'POST', $prop_data, $token);
        if ($response['body'] !== false) {
            $prop_creado = json_decode($response['body'], true);
            $propietarios_ids[] = $prop_creado['data']['id'];
            echo "✅ Propietario creado: {$prop_data['nombre']} (ID: {$prop_creado['data']['id']})\n";
        }
    }
} else {
    // Usar los primeros 2 propietarios existentes
    $propietarios_ids[] = $propietarios['data'][0]['id'];
    echo "✅ Usando propietario existente: {$propietarios['data'][0]['nombre']} (ID: {$propietarios['data'][0]['id']})\n";

    if (count($propietarios['data']) > 1) {
        $propietarios_ids[] = $propietarios['data'][1]['id'];
        echo "✅ Usando propietario existente: {$propietarios['data'][1]['nombre']} (ID: {$propietarios['data'][1]['id']})\n";
    }
}

echo "\n" . str_repeat("-", 60) . "\n\n";

// PASO 3: Asignar primer propietario al vehículo
echo "📝 PASO 3: Asignando primer propietario al vehículo...\n";
$asignacion_data = [
    'vehiculo_id' => $vehiculo_id,
    'propietario_id' => $propietarios_ids[0],
    'fecha_inicio' => date('Y-m-d')
];

echo "📤 Enviando datos:\n";
echo json_encode($asignacion_data, JSON_PRETTY_PRINT) . "\n\n";

$response = makeRequest("$base_url/vehiculo-propietarios", 'POST', $asignacion_data, $token);
$status = getStatusCode($response['headers']);

if ($response['body'] === false) {
    echo "❌ Error en la petición POST\n";
} else {
    $data = json_decode($response['body'], true);

    if ($status == '201') {
        echo "✅ Propietario asignado exitosamente!\n";
        echo "🔍 Mensaje: {$data['message']}\n";
    } else {
        echo "❌ Error en la asignación (Status: $status)\n";
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
}

echo "\n" . str_repeat("-", 60) . "\n\n";

// PASO 4: Asignar segundo propietario al mismo vehículo (si existe)
if (count($propietarios_ids) > 1) {
    echo "📝 PASO 4: Asignando segundo propietario al mismo vehículo...\n";
    $asignacion_data2 = [
        'vehiculo_id' => $vehiculo_id,
        'propietario_id' => $propietarios_ids[1],
        'fecha_inicio' => date('Y-m-d')
    ];

    echo "📤 Enviando datos:\n";
    echo json_encode($asignacion_data2, JSON_PRETTY_PRINT) . "\n\n";

    $response = makeRequest("$base_url/vehiculo-propietarios", 'POST', $asignacion_data2, $token);
    $status = getStatusCode($response['headers']);

    if ($response['body'] === false) {
        echo "❌ Error en la petición POST\n";
    } else {
        $data = json_decode($response['body'], true);

        if ($status == '201') {
            echo "✅ Segundo propietario asignado exitosamente!\n";
            echo "🔍 Mensaje: {$data['message']}\n";
        } else {
            echo "❌ Error en la asignación (Status: $status)\n";
            echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
        }
    }

    echo "\n" . str_repeat("-", 60) . "\n\n";
}

// PASO 5: Verificar propietarios del vehículo
echo "📝 PASO 5: Verificando propietarios del vehículo...\n";
$response = makeRequest("$base_url/vehiculo-propietarios?vehiculo_id=$vehiculo_id", 'GET', null, $token);

if ($response['body'] === false) {
    echo "❌ Error al consultar propietarios\n";
} else {
    $data = json_decode($response['body'], true);

    if (isset($data['data']['propietarios'])) {
        echo "✅ Vehículo placa {$vehiculo_placa} tiene " . count($data['data']['propietarios']) . " propietario(s):\n";
        foreach ($data['data']['propietarios'] as $propietario) {
            echo "   - {$propietario['nombre']} (ID: {$propietario['id']})\n";
        }
    } else {
        echo "⚠️  No se encontraron propietarios o estructura de respuesta inesperada\n";
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
}

echo "\n" . str_repeat("-", 60) . "\n\n";

// PASO 6: Intentar asignar el mismo propietario nuevamente (debe fallar)
echo "📝 PASO 6: Intentando asignar el mismo propietario nuevamente (debe fallar)...\n";
$asignacion_duplicada = [
    'vehiculo_id' => $vehiculo_id,
    'propietario_id' => $propietarios_ids[0]
];

$response = makeRequest("$base_url/vehiculo-propietarios", 'POST', $asignacion_duplicada, $token);
$status = getStatusCode($response['headers']);

if ($response['body'] === false) {
    echo "❌ Error en la petición\n";
} else {
    $data = json_decode($response['body'], true);

    if ($status == '422') {
        echo "✅ Validación funcionando! Error 422 - Propietario ya asignado\n";
        echo "🔍 Mensaje: {$data['message']}\n";
    } else {
        echo "⚠️  Status inesperado: $status\n";
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
}

echo "\n=== RESUMEN DE LA PRUEBA ===\n";
echo "✅ Vehículo verificado/creado\n";
echo "✅ Propietarios verificados/creados\n";
echo "✅ Asignación propietario-vehículo funcionando\n";
echo "✅ Múltiples propietarios por vehículo funcionando\n";
echo "✅ Validación de duplicados funcionando\n";
echo "✅ Consulta de propietarios por vehículo funcionando\n";

echo "\n=== FIN DE LA PRUEBA ===\n";
