<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;

class VehiculoSeeder extends Seeder
{
    public function run()
    {
        $marcas = ['Toyota','Nissan','Hyundai','Kia','Chevrolet','Mazda','Volkswagen','Ford','Honda','Renault','Suzuki','Peugeot','Fiat','Jeep','BMW','Mercedes','Audi','Mitsubishi','Subaru','Volvo'];
        $modelos = ['Sedan','Hatchback','SUV','Pickup','Coupé','Convertible','Van','Wagon','Compacto','Deportivo','Familiar','Cross','Offroad','Minivan','Utilitario','Roadster','Fastback','Crossover','Microcar','Limousine'];
        $colores = ['Blanco','Negro','Gris','Rojo','Azul','Verde','Amarillo','Plateado','Marrón','Naranja','Violeta','Dorado','Turquesa','Beige','Burdeos','Celeste','Champán','Magenta','Oliva','Púrpura'];

        for ($i = 1; $i <= 100; $i++) {
            Vehiculo::create([
                'placa' => strtoupper(substr($marcas[$i%count($marcas)],0,3)) . rand(100,999) . chr(65+($i%26)),
                'marca' => $marcas[$i%count($marcas)],
                'modelo' => $modelos[$i%count($modelos)],
                'color' => $colores[$i%count($colores)],
                'tipo_vehiculo_id' => rand(1,10),
                'anio' => rand(2000,2025),
            ]);
        }
    }
}
