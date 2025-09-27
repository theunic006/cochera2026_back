<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VehiculoPropietario;
use App\Models\Vehiculo;
use App\Models\Propietario;

class ShowEnlacesVehiculoPropietario extends Command
{
    protected $signature = 'show:enlaces-vp';
    protected $description = 'Muestra los 20 enlaces creados entre propietarios y vehículos';

    public function handle()
    {
        $enlaces = VehiculoPropietario::orderBy('id', 'desc')->limit(20)->get();
        $rows = [];
        foreach ($enlaces as $enlace) {
            $vehiculo = Vehiculo::find($enlace->vehiculo_id);
            $propietario = Propietario::find($enlace->propietario_id);
            $rows[] = [
                'enlace_id' => $enlace->id,
                'vehiculo_id' => $vehiculo ? $vehiculo->id : null,
                'placa' => $vehiculo ? $vehiculo->placa : '',
                'marca' => $vehiculo ? $vehiculo->marca : '',
                'modelo' => $vehiculo ? $vehiculo->modelo : '',
                'propietario_id' => $propietario ? $propietario->id : null,
                'nombres' => $propietario ? $propietario->nombres : '',
                'apellidos' => $propietario ? $propietario->apellidos : '',
                'fecha_inicio' => $enlace->fecha_inicio,
            ];
        }
        $this->table([
            'enlace_id', 'vehiculo_id', 'placa', 'marca', 'modelo', 'propietario_id', 'nombres', 'apellidos', 'fecha_inicio'
        ], $rows);
    }
}
