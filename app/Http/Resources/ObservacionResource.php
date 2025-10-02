<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ObservacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo,
            'descripcion' => $this->descripcion,
            'id_vehiculo' => $this->id_vehiculo,
            'id_empresa' => $this->id_empresa,
            'id_usuario' => $this->id_usuario,
            'vehiculo' => new VehiculoResource($this->vehiculo),
            'usuario' => new UserResource($this->usuario),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
