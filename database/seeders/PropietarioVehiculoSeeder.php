<?php

namespace Database\Seeders;

use App\Models\Propietario;
use App\Models\Vehiculo;
use App\Models\TipoVehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropietarioVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear propietarios de prueba
        $propietarios = [
            [
                'nombres' => 'Juan Carlos',
                'apellidos' => 'Pérez López',
                'documento' => '12345678',
                'telefono' => '555-1234',
                'email' => 'juan.perez@test.com',
                'direccion' => 'Calle 123 #45-67'
            ],
            [
                'nombres' => 'María Elena',
                'apellidos' => 'González Rivera',
                'documento' => '87654321',
                'telefono' => '555-5678',
                'email' => 'maria.gonzalez@test.com',
                'direccion' => 'Avenida 789 #12-34'
            ],
            [
                'nombres' => 'Carlos Alberto',
                'apellidos' => 'Rodríguez Martín',
                'documento' => '11223344',
                'telefono' => '555-9012',
                'email' => 'carlos.rodriguez@test.com',
                'direccion' => 'Carrera 456 #78-90'
            ]
        ];

        foreach ($propietarios as $propietarioData) {
            Propietario::create($propietarioData);
        }

        // Obtener tipos de vehículo existentes
        $tipoAuto = TipoVehiculo::where('nombre', 'Auto')->first();
        $tipoBicicleta = TipoVehiculo::where('nombre', 'Bicicleta')->first();
        $tipoCamioneta = TipoVehiculo::where('nombre', 'Camioneta')->first();

        // Crear vehículos de prueba
        $vehiculos = [
            [
                'placa' => 'ABC123',
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'color' => 'Blanco',
                'anio' => 2020,
                'tipo_vehiculo_id' => $tipoAuto ? $tipoAuto->id : 6
            ],
            [
                'placa' => 'XYZ789',
                'marca' => 'Honda',
                'modelo' => 'Civic',
                'color' => 'Azul',
                'anio' => 2019,
                'tipo_vehiculo_id' => $tipoAuto ? $tipoAuto->id : 6
            ],
            [
                'placa' => 'BIC456',
                'marca' => 'Giant',
                'modelo' => 'ATX',
                'color' => 'Verde',
                'anio' => 2021,
                'tipo_vehiculo_id' => $tipoBicicleta ? $tipoBicicleta->id : 2
            ],
            [
                'placa' => 'CAM789',
                'marca' => 'Ford',
                'modelo' => 'Ranger',
                'color' => 'Rojo',
                'anio' => 2018,
                'tipo_vehiculo_id' => $tipoCamioneta ? $tipoCamioneta->id : 7
            ]
        ];

        foreach ($vehiculos as $vehiculoData) {
            Vehiculo::create($vehiculoData);
        }

        // Crear algunas tolerancias de prueba
        $tolerancias = [
            ['minutos' => 15, 'tipo_vehiculo_id' => 6], // Auto - 15 minutos
            ['minutos' => 10, 'tipo_vehiculo_id' => 2], // Bicicleta - 10 minutos
            ['minutos' => 20, 'tipo_vehiculo_id' => 7], // Camioneta - 20 minutos
        ];

        foreach ($tolerancias as $toleranciaData) {
            \App\Models\Tolerancia::create($toleranciaData);
        }

        // Crear algunas relaciones vehículo-propietario
        $propietarios = \App\Models\Propietario::all();
        $vehiculos = \App\Models\Vehiculo::all();

        if ($propietarios->count() >= 3 && $vehiculos->count() >= 4) {
            $relacionesVP = [
                [
                    'vehiculo_id' => $vehiculos[0]->id,
                    'propietario_id' => $propietarios[0]->id,
                    'fecha_inicio' => now()->subMonths(6)->format('Y-m-d'),
                ],
                [
                    'vehiculo_id' => $vehiculos[1]->id,
                    'propietario_id' => $propietarios[1]->id,
                    'fecha_inicio' => now()->subMonths(3)->format('Y-m-d'),
                ],
                [
                    'vehiculo_id' => $vehiculos[2]->id,
                    'propietario_id' => $propietarios[2]->id,
                    'fecha_inicio' => now()->subMonth()->format('Y-m-d'),
                ],
            ];

            foreach ($relacionesVP as $relacionData) {
                \App\Models\VehiculoPropietario::create($relacionData);
            }
        }

        $this->command->info('✅ Propietarios, vehículos, tolerancias y relaciones de prueba creados exitosamente');
    }
}
