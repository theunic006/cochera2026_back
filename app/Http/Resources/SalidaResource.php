<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalidaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fecha_salida' => $this->fecha_salida,
            'hora_salida' => $this->hora_salida,
            'tiempo' => $this->tiempo,
            'precio' => $this->precio,
            'tipo_pago' => $this->tipo_pago,
            'id_ingreso' => $this->id_ingreso,
            'id_user' => $this->id_user,
            'id_empresa' => $this->id_empresa,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'ingreso' => $this->whenLoaded('ingreso'),
            'user' => $this->whenLoaded('user'),
            'empresa' => $this->whenLoaded('empresa'),
        ];
    }
}
