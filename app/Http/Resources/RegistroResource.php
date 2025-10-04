<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistroResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fecha_ingreso' => $this->fecha_ingreso,
            'hora_ingreso' => $this->hora_ingreso,
            'id_vehiculo' => $this->id_vehiculo,
            'id_user' => $this->id_user,
            'id_empresa' => $this->id_empresa,
            'fecha' => $this->fecha,
            'vehiculo' => $this->vehiculo ? array_merge(
                $this->vehiculo->toArray(),
                [
                    'tipo_vehiculo' => $this->vehiculo->tipoVehiculo ? $this->vehiculo->tipoVehiculo->toArray() : null
                ]
            ) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
