<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngresoResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fecha_ingreso' => $this->fecha_ingreso,
            'hora_ingreso' => $this->hora_ingreso,
            'id_user' => $this->id_user,
            'id_empresa' => $this->id_empresa,
            'id_vehiculo' => $this->id_vehiculo,
            'user' => new UserResource($this->user),
            'vehiculo' => new VehiculoResource($this->vehiculo->loadMissing('observaciones')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
