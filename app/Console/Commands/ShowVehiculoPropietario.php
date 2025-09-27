<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VehiculoPropietario;

class ShowVehiculoPropietario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show:vehiculo-propietario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Muestra los primeros 10 registros de la tabla vehiculo_propietario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registros = VehiculoPropietario::limit(10)->get();
        $this->table([
            'id', 'vehiculo_id', 'propietario_id', 'fecha_inicio', 'fecha_fin', 'created_at', 'updated_at'
        ], $registros->toArray());
    }
}
