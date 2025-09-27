<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;

class VehiculoDeleteSeeder extends Seeder
{
    public function run()
    {
        Vehiculo::where('placa', 'like', 'TEST%')->delete();
    }
}
