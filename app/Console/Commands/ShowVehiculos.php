<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehiculo;

class ShowVehiculos extends Command
{
    protected $signature = 'show:vehiculos';
    protected $description = 'Muestra los primeros 20 registros de la tabla vehiculos';

    public function handle()
    {
        $registros = Vehiculo::limit(20)->get();
        $this->table([
            'id', 'placa', 'marca', 'modelo', 'color', 'anio', 'tipo_vehiculo_id', 'created_at', 'updated_at'
        ], $registros->toArray());
    }
}
