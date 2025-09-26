<?php
/**
 * VERIFICACIÓN COMPLETA DE LOS 5 ENDPOINTS CRUD DE PROPIETARIOS
 * Verifica que todos los endpoints básicos estén funcionando correctamente
 */

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
            ],
            'ignore_errors' => true
        ]
    ];

    if ($token) {
        $context_options['http']['header'][] = 'Authorization: Bearer ' . $token;
    }

    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        $context_options['http']['content'] = json_encode($data);
    }

    $context = stream_context_create($context_options);
    $response = file_get_contents($url, false, $context);

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

echo "=== VERIFICACIÓN COMPLETA DE 5 ENDPOINTS CRUD PROPIETARIOS ===\n\n";

$propietario_id_creado = null;

// ============================================================================
// 1. GET /api/propietarios - Listar propietarios
// ============================================================================
echo "📋 ENDPOINT 1: GET /api/propietarios - Listar propietarios\n";
echo "URL: $base_url/propietarios\n";

$response1 = makeRequest("$base_url/propietarios", 'GET', null, $token);
$status1 = getStatusCode($response1['headers']);

echo "Status Code: $status1\n";

if ($response1['body'] !== false) {
    $data1 = json_decode($response1['body'], true);

    if ($status1 == '200') {
        echo "✅ ENDPOINT 1 FUNCIONANDO - Lista de propietarios obtenida\n";
        echo "🔍 Total de propietarios: " . (isset($data1['data']) ? count($data1['data']) : 'N/A') . "\n";

        if (isset($data1['data'][0])) {
            echo "🔍 Estructura del primer propietario:\n";
            echo "   - ID: " . ($data1['data'][0]['id'] ?? 'N/A') . "\n";
            echo "   - Nombres: " . ($data1['data'][0]['nombres'] ?? 'N/A') . "\n";
            echo "   - Apellidos: " . ($data1['data'][0]['apellidos'] ?? 'N/A') . "\n";
            echo "   - Documento: " . ($data1['data'][0]['documento'] ?? 'N/A') . "\n";
            echo "   - Email: " . ($data1['data'][0]['email'] ?? 'N/A') . "\n";
        }
    } else {
        echo "❌ ENDPOINT 1 ERROR - Status: $status1\n";
        echo "Respuesta: " . substr($response1['body'], 0, 200) . "...\n";
    }
} else {
    echo "❌ ENDPOINT 1 ERROR - No se pudo conectar\n";
}

echo "\n" . str_repeat("=", 80) . "\n\n";

// ============================================================================
// 2. POST /api/propietarios - Crear nuevo propietario
// ============================================================================
echo "📝 ENDPOINT 2: POST /api/propietarios - Crear nuevo propietario\n";
echo "URL: $base_url/propietarios\n";

$nuevo_propietario = [
    'nombres' => 'Test Usuario',
    'apellidos' => 'Verificación Sistema',
    'documento' => 'TEST' . time(), // Documento único usando timestamp
    'telefono' => '555-TEST',
    'email' => 'test.verificacion.' . time() . '@example.com', // Email único
    'direccion' => 'Dirección de prueba para verificación'
];

echo "📤 Datos a enviar:\n";
echo json_encode($nuevo_propietario, JSON_PRETTY_PRINT) . "\n\n";

$response2 = makeRequest("$base_url/propietarios", 'POST', $nuevo_propietario, $token);
$status2 = getStatusCode($response2['headers']);

echo "Status Code: $status2\n";

if ($response2['body'] !== false) {
    $data2 = json_decode($response2['body'], true);

    if ($status2 == '201') {
        echo "✅ ENDPOINT 2 FUNCIONANDO - Propietario creado exitosamente\n";
        $propietario_id_creado = $data2['data']['id'] ?? null;
        echo "🆔 ID del propietario creado: $propietario_id_creado\n";
        echo "🔍 Nombre completo: " . ($data2['data']['nombre_completo'] ?? 'N/A') . "\n";
        echo "🔍 Documento: " . ($data2['data']['documento'] ?? 'N/A') . "\n";
        echo "🔍 Email: " . ($data2['data']['email'] ?? 'N/A') . "\n";
    } else {
        echo "❌ ENDPOINT 2 ERROR - Status: $status2\n";
        echo "Respuesta: " . substr($response2['body'], 0, 500) . "...\n";

        if (isset($data2['errors'])) {
            echo "🔍 Errores de validación:\n";
            foreach ($data2['errors'] as $campo => $errores) {
                echo "   $campo: " . implode(', ', $errores) . "\n";
            }
        }
    }
} else {
    echo "❌ ENDPOINT 2 ERROR - No se pudo conectar\n";
}

echo "\n" . str_repeat("=", 80) . "\n\n";

// ============================================================================
// 3. GET /api/propietarios/{id} - Ver propietario específico
// ============================================================================
if ($propietario_id_creado) {
    echo "👤 ENDPOINT 3: GET /api/propietarios/{id} - Ver propietario específico\n";
    echo "URL: $base_url/propietarios/$propietario_id_creado\n";

    $response3 = makeRequest("$base_url/propietarios/$propietario_id_creado", 'GET', null, $token);
    $status3 = getStatusCode($response3['headers']);

    echo "Status Code: $status3\n";

    if ($response3['body'] !== false) {
        $data3 = json_decode($response3['body'], true);

        if ($status3 == '200') {
            echo "✅ ENDPOINT 3 FUNCIONANDO - Propietario específico obtenido\n";
            echo "🔍 ID: " . ($data3['data']['id'] ?? 'N/A') . "\n";
            echo "🔍 Nombre completo: " . ($data3['data']['nombre_completo'] ?? 'N/A') . "\n";
            echo "🔍 Documento: " . ($data3['data']['documento'] ?? 'N/A') . "\n";
            echo "🔍 Teléfono: " . ($data3['data']['telefono'] ?? 'N/A') . "\n";
            echo "🔍 Email: " . ($data3['data']['email'] ?? 'N/A') . "\n";
            echo "🔍 Dirección: " . ($data3['data']['direccion'] ?? 'N/A') . "\n";
        } else {
            echo "❌ ENDPOINT 3 ERROR - Status: $status3\n";
            echo "Respuesta: " . substr($response3['body'], 0, 200) . "...\n";
        }
    } else {
        echo "❌ ENDPOINT 3 ERROR - No se pudo conectar\n";
    }
} else {
    echo "⚠️ ENDPOINT 3 OMITIDO - No se creó propietario en paso anterior\n";
}

echo "\n" . str_repeat("=", 80) . "\n\n";

// ============================================================================
// 4. PUT /api/propietarios/{id} - Actualizar propietario
// ============================================================================
if ($propietario_id_creado) {
    echo "✏️ ENDPOINT 4: PUT /api/propietarios/{id} - Actualizar propietario\n";
    echo "URL: $base_url/propietarios/$propietario_id_creado\n";

    $datos_actualizacion = [
        'nombres' => 'Test Usuario ACTUALIZADO',
        'apellidos' => 'Verificación Sistema EDITADO',
        'telefono' => '555-UPDATED',
        'direccion' => 'Nueva dirección actualizada para verificación'
    ];

    echo "📤 Datos a actualizar:\n";
    echo json_encode($datos_actualizacion, JSON_PRETTY_PRINT) . "\n\n";

    $response4 = makeRequest("$base_url/propietarios/$propietario_id_creado", 'PUT', $datos_actualizacion, $token);
    $status4 = getStatusCode($response4['headers']);

    echo "Status Code: $status4\n";

    if ($response4['body'] !== false) {
        $data4 = json_decode($response4['body'], true);

        if ($status4 == '200') {
            echo "✅ ENDPOINT 4 FUNCIONANDO - Propietario actualizado exitosamente\n";
            echo "🔍 ID: " . ($data4['data']['id'] ?? 'N/A') . "\n";
            echo "🔍 Nombres actualizados: " . ($data4['data']['nombres'] ?? 'N/A') . "\n";
            echo "🔍 Apellidos actualizados: " . ($data4['data']['apellidos'] ?? 'N/A') . "\n";
            echo "🔍 Teléfono actualizado: " . ($data4['data']['telefono'] ?? 'N/A') . "\n";
            echo "🔍 Dirección actualizada: " . ($data4['data']['direccion'] ?? 'N/A') . "\n";
        } else {
            echo "❌ ENDPOINT 4 ERROR - Status: $status4\n";
            echo "Respuesta: " . substr($response4['body'], 0, 500) . "...\n";

            if (isset($data4['errors'])) {
                echo "🔍 Errores de validación:\n";
                foreach ($data4['errors'] as $campo => $errores) {
                    echo "   $campo: " . implode(', ', $errores) . "\n";
                }
            }
        }
    } else {
        echo "❌ ENDPOINT 4 ERROR - No se pudo conectar\n";
    }
} else {
    echo "⚠️ ENDPOINT 4 OMITIDO - No se creó propietario en paso anterior\n";
}

echo "\n" . str_repeat("=", 80) . "\n\n";

// ============================================================================
// 5. DELETE /api/propietarios/{id} - Eliminar propietario
// ============================================================================
if ($propietario_id_creado) {
    echo "🗑️ ENDPOINT 5: DELETE /api/propietarios/{id} - Eliminar propietario\n";
    echo "URL: $base_url/propietarios/$propietario_id_creado\n";

    $response5 = makeRequest("$base_url/propietarios/$propietario_id_creado", 'DELETE', null, $token);
    $status5 = getStatusCode($response5['headers']);

    echo "Status Code: $status5\n";

    if ($response5['body'] !== false) {
        $data5 = json_decode($response5['body'], true);

        if ($status5 == '200') {
            echo "✅ ENDPOINT 5 FUNCIONANDO - Propietario eliminado exitosamente\n";
            echo "🔍 Mensaje: " . ($data5['message'] ?? 'Eliminado correctamente') . "\n";

            // Verificar que realmente se eliminó
            echo "\n🔍 Verificando eliminación...\n";
            $response_verificacion = makeRequest("$base_url/propietarios/$propietario_id_creado", 'GET', null, $token);
            $status_verificacion = getStatusCode($response_verificacion['headers']);

            if ($status_verificacion == '404') {
                echo "✅ Confirmado: El propietario ya no existe (404)\n";
            } else {
                echo "⚠️ El propietario aún existe (Status: $status_verificacion)\n";
            }

        } else {
            echo "❌ ENDPOINT 5 ERROR - Status: $status5\n";
            echo "Respuesta: " . substr($response5['body'], 0, 200) . "...\n";
        }
    } else {
        echo "❌ ENDPOINT 5 ERROR - No se pudo conectar\n";
    }
} else {
    echo "⚠️ ENDPOINT 5 OMITIDO - No se creó propietario en paso anterior\n";
}

echo "\n" . str_repeat("=", 80) . "\n\n";

// ============================================================================
// RESUMEN FINAL
// ============================================================================
echo "📊 RESUMEN FINAL DE VERIFICACIÓN:\n\n";

$endpoints_status = [
    "1. GET /api/propietarios (Listar)" => ($status1 == '200') ? '✅ FUNCIONANDO' : '❌ ERROR',
    "2. POST /api/propietarios (Crear)" => ($status2 == '201') ? '✅ FUNCIONANDO' : '❌ ERROR',
    "3. GET /api/propietarios/{id} (Ver)" => (isset($status3) && $status3 == '200') ? '✅ FUNCIONANDO' : ($propietario_id_creado ? '❌ ERROR' : '⚠️ OMITIDO'),
    "4. PUT /api/propietarios/{id} (Actualizar)" => (isset($status4) && $status4 == '200') ? '✅ FUNCIONANDO' : ($propietario_id_creado ? '❌ ERROR' : '⚠️ OMITIDO'),
    "5. DELETE /api/propietarios/{id} (Eliminar)" => (isset($status5) && $status5 == '200') ? '✅ FUNCIONANDO' : ($propietario_id_creado ? '❌ ERROR' : '⚠️ OMITIDO'),
];

foreach ($endpoints_status as $endpoint => $status) {
    echo "$endpoint: $status\n";
}

$funcionando = array_reduce($endpoints_status, function($count, $status) {
    return $count + (strpos($status, '✅') !== false ? 1 : 0);
}, 0);

echo "\n🎯 RESULTADO: $funcionando/5 endpoints funcionando correctamente\n";

if ($funcionando == 5) {
    echo "\n🎉 ¡EXCELENTE! Todos los endpoints CRUD están funcionando perfectamente\n";
    echo "✅ El sistema de propietarios está completamente operativo\n";
} elseif ($funcionando >= 3) {
    echo "\n⚠️ La mayoría de endpoints funcionan, revisar los que fallan\n";
} else {
    echo "\n❌ Hay problemas significativos con los endpoints\n";
    echo "🔧 Se requiere revisión del código y configuración\n";
}

echo "\n=== FIN DE LA VERIFICACIÓN ===\n";
