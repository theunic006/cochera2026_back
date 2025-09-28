<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingreso;

class IngresoSeeder extends Seeder
{
    public function run(): void
    {
        Ingreso::insert([
            [
                'fecha_ingreso' => '2025-09-27',
                'hora_ingreso' => '08:15:00',
                'id_user' => 1,
                'id_empresa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fecha_ingreso' => '2025-09-27',
                'hora_ingreso' => '09:30:00',
                'id_user' => 1,
                'id_empresa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fecha_ingreso' => '2025-09-27',
                'hora_ingreso' => '10:45:00',
                'id_user' => 1,
                'id_empresa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fecha_ingreso' => '2025-09-27',
                'hora_ingreso' => '11:00:00',
                'id_user' => 1,
                'id_empresa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fecha_ingreso' => '2025-09-27',
                'hora_ingreso' => '12:20:00',
                'id_user' => 1,
                'id_empresa' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
