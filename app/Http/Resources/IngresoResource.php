<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngresoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    //mostrar placa(boton)->editar
    //mostrar hora ingreso
    // mostrar saldo
    // mostrar
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fecha_ingreso' => $this->fecha_ingreso,
            'hora_ingreso' => $this->hora_ingreso,
            'id_user' => $this->id_user,
            'id_empresa' => $this->id_empresa,
            'id_vehiculo' => $this->id_vehiculo,
            'vehiculo' => new VehiculoResource($this->vehiculo),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
