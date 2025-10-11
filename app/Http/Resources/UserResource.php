<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'idrol' => $this->idrol,
            'role' => new \App\Http\Resources\RoleResource($this->whenLoaded('role')),
            'id_company' => $this->id_company,
            'estado' => $this->estado,
            'company' => $this->whenLoaded('company', function () {
                return [
                    'id' => $this->company->id,
                    'nombre' => $this->company->nombre,
                    'ubicacion' => $this->company->ubicacion,
                    'logo' => $this->company->logo,
                    'descripcion' => $this->company->descripcion,
                    'estado' => $this->company->estado,
                    'capacidad' => $this->company->capacidad,
                    'imp_input' => $this->company->imp_input,
                    'imp_output' => $this->company->imp_output,

                ];
            }),
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
