<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TipoVehiculoResource extends JsonResource
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
            'nombre' => $this->nombre,
            'nombre_formateado' => $this->nombre_formateado,
            'valor' => $this->valor,
            'valor_formateado' => $this->getValorFormateado(),
            'tiene_valor' => $this->tieneValor(),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
