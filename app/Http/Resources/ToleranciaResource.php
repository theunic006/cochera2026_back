<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToleranciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_empresa' => $this->id_empresa,
            'minutos' => $this->minutos,
            'descripcion' => $this->descripcion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Campos calculados adicionales
            'tiempo_formateado' => $this->tiempo_formateado,
            'descripcion_completa' => $this->descripcion_completa,
        ];
    }
}
