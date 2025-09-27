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
        // Crear 20 propietarios ficticios y enlazarlos con vehículos
        $nombres = ['Juan', 'María', 'Carlos', 'Ana', 'Luis', 'Sofía', 'Pedro', 'Lucía', 'Miguel', 'Valeria', 'José', 'Camila', 'Andrés', 'Paula', 'Jorge', 'Gabriela', 'Ricardo', 'Fernanda', 'Manuel', 'Isabel'];
        $apellidos = ['Pérez', 'González', 'Rodríguez', 'Martínez', 'López', 'Hernández', 'García', 'Ramírez', 'Sánchez', 'Torres', 'Flores', 'Rivera', 'Castro', 'Jiménez', 'Morales', 'Ramos', 'Vargas', 'Romero', 'Molina', 'Silva'];
        $calles = ['Calle', 'Avenida', 'Carrera', 'Pasaje', 'Boulevard', 'Camino', 'Ruta', 'Sendero', 'Plaza', 'Diagonal'];
        $ciudades = ['Lima', 'Bogotá', 'Quito', 'Santiago', 'Buenos Aires', 'Montevideo', 'Caracas', 'La Paz', 'Asunción', 'Ciudad de México'];

        $vehiculos = \App\Models\Vehiculo::inRandomOrder()->limit(20)->get();
        $propietarios = [];
        for ($i = 1; $i <= 20; $i++) {
            $nombre = $nombres[array_rand($nombres)] . ' ' . $nombres[array_rand($nombres)];
            $apellido = $apellidos[array_rand($apellidos)] . ' ' . $apellidos[array_rand($apellidos)];
            $documento = str_pad($i, 8, '0', STR_PAD_LEFT);
            $telefono = '555-' . rand(1000, 9999);
            $email = strtolower(str_replace(' ', '.', $nombre)) . $i . '@test.com';
            $direccion = $calles[array_rand($calles)] . ' ' . rand(1, 999) . ' #' . rand(10,99) . '-' . rand(10,99) . ', ' . $ciudades[array_rand($ciudades)];

            $propietarios[] = \App\Models\Propietario::create([
                'nombres' => $nombre,
                'apellidos' => $apellido,
                'documento' => $documento,
                'telefono' => $telefono,
                'email' => $email,
                'direccion' => $direccion
            ]);
        }

        for ($i = 0; $i < 20; $i++) {
            \App\Models\VehiculoPropietario::create([
                'vehiculo_id' => $vehiculos[$i]->id,
                'propietario_id' => $propietarios[$i]->id,
                'fecha_inicio' => now()->subDays(rand(0, 700))->format('Y-m-d'),
                'fecha_fin' => null
            ]);
        }

        $this->command->info('✅ 20 propietarios creados y enlazados con vehículos.');
    }
}
