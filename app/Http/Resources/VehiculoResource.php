<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehiculoResource extends JsonResource
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
            'placa' => $this->placa,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'color' => $this->color,
            'anio' => $this->anio,
            'frecuencia' => $this->frecuencia,
            'tipo_vehiculo_id' => $this->tipo_vehiculo_id,

            // Información completa del tipo de vehículo
            'tipo_vehiculo' => $this->whenLoaded('tipoVehiculo', function () {
                return [
                    'id' => $this->tipoVehiculo->id,
                    'nombre' => $this->tipoVehiculo->nombre,
                    'valor' => $this->tipoVehiculo->valor,
                ];
            }),

            // Observaciones del vehículo
            'observaciones' => ObservacionResource::collection($this->whenLoaded('observaciones')),

            // Campos calculados útiles
            'descripcion_completa' => $this->placa . ' - ' . $this->marca . ' ' . $this->modelo,
            'tipo_vehiculo_nombre' => $this->tipoVehiculo?->nombre ?? 'Sin tipo asignado',
            'anio_formateado' => $this->anio ? 'Año ' . $this->anio : 'Año no especificado',

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
