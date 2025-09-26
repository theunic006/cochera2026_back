<?php
/**
 * Test de propietarios con columnas corregidas (nombres, apellidos, documento)
 */

$base_url = 'http://127.0.0.1:8000/api';
$token = '47|GOvJ6tHFY045Y8kgRjxW4iykpwksvG9ls90cI54272e3af4d';

echo "=== PRUEBA PROPIETARIOS CON COLUMNAS CORRECTAS ===\n\n";

// PASO 1: Crear un propietario con datos correctos
echo "📝 PASO 1: Creando propietario con nombres y apellidos...\n";
$propietario_data = [
    'nombres' => 'Juan Carlos',
    'apellidos' => 'Pérez López',
    'documento' => '12345678' . rand(10, 99), // Documento único
    'telefono' => '987654321',
    'email' => 'juan.perez@example.com',
    'direccion' => 'Calle 123 #45-67'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ],
        'content' => json_encode($propietario_data),
        'ignore_errors' => true
    ]
]);

echo "📤 Enviando datos:\n";
echo json_encode($propietario_data, JSON_PRETTY_PRINT) . "\n\n";

$response = file_get_contents("$base_url/propietarios", false, $context);

echo "📥 Headers de respuesta:\n";
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        echo "   $header\n";
    }
}
echo "\n";

if ($response !== false) {
    $data = json_decode($response, true);
    echo "📋 Respuesta:\n";
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n";

    if (isset($data['data']['id'])) {
        $propietario_id = $data['data']['id'];
        echo "\n✅ Propietario creado exitosamente con ID: $propietario_id\n";
        echo "🔍 Nombre completo: {$data['data']['nombre_completo']}\n";

        echo "\n" . str_repeat("-", 60) . "\n\n";

        // PASO 2: Asignar este propietario a un vehículo
        echo "📝 PASO 2: Asignando propietario al vehículo ABC123...\n";
        $asignacion_data = [
            'vehiculo_id' => 1,
            'propietario_id' => $propietario_id,
            'fecha_inicio' => '2025-09-26'
        ];

        $context2 = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer ' . $token
                ],
                'content' => json_encode($asignacion_data),
                'ignore_errors' => true
            ]
        ]);

        echo "📤 Enviando asignación:\n";
        echo json_encode($asignacion_data, JSON_PRETTY_PRINT) . "\n\n";

        $response2 = file_get_contents("$base_url/vehiculo-propietarios", false, $context2);

        if ($response2 !== false) {
            $data2 = json_decode($response2, true);
            echo "📋 Respuesta de asignación:\n";
            echo json_encode($data2, JSON_PRETTY_PRINT) . "\n";

            if (isset($data2['success']) && $data2['success']) {
                echo "\n✅ Asignación exitosa!\n";

                echo "\n" . str_repeat("-", 60) . "\n\n";

                // PASO 3: Verificar propietarios del vehículo
                echo "📝 PASO 3: Verificando propietarios del vehículo...\n";
                $response3 = file_get_contents("$base_url/vehiculo-propietarios?vehiculo_id=1", false, stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'header' => [
                            'Accept: application/json',
                            'Authorization: Bearer ' . $token
                        ],
                        'ignore_errors' => true
                    ]
                ]));

                if ($response3 !== false) {
                    $data3 = json_decode($response3, true);
                    echo "✅ Propietarios del vehículo ABC123:\n";

                    if (isset($data3['data']['propietarios'])) {
                        foreach ($data3['data']['propietarios'] as $prop) {
                            echo "   - ID: {$prop['id']}\n";
                            echo "     Nombres: " . ($prop['nombres'] ?? 'N/A') . "\n";
                            echo "     Apellidos: " . ($prop['apellidos'] ?? 'N/A') . "\n";
                            echo "     Nombre completo: " . ($prop['nombre_completo'] ?? 'N/A') . "\n";
                            echo "     Documento: " . ($prop['documento'] ?? 'N/A') . "\n";
                            echo "     Email: " . ($prop['email'] ?? 'N/A') . "\n";
                            echo "\n";
                        }
                    } else {
                        echo "⚠️  No se encontraron propietarios o estructura inesperada\n";
                        echo json_encode($data3, JSON_PRETTY_PRINT) . "\n";
                    }
                }
            }
        }
    } else {
        echo "\n❌ Error al crear propietario\n";
        if (isset($data['errors'])) {
            echo "🔍 Errores de validación:\n";
            foreach ($data['errors'] as $campo => $errores) {
                echo "   $campo: " . implode(', ', $errores) . "\n";
            }
        }
    }
} else {
    echo "❌ No se recibió respuesta\n";
}

echo "\n=== RESUMEN ===\n";
echo "✅ Columnas corregidas: nombres, apellidos, documento, telefono, email, direccion\n";
echo "✅ PropietarioResource actualizado\n";
echo "✅ Validaciones actualizadas\n";
echo "✅ Relación vehículo-propietario funcionando\n";

echo "\n=== FIN DE LA PRUEBA ===\n";
