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
            'categoria' => $this->categoria,
            'idrol' => $this->idrol,
            'id_company' => $this->id_company,
            'role' => $this->whenLoaded('role', function () {
                return [
                    'id' => $this->role->id,
                    'descripcion' => $this->role->descripcion,
                ];
            }),
            'company' => $this->whenLoaded('company', function () {
                return [
                    'id' => $this->company->id,
                    'nombre' => $this->company->nombre,
                    'ubicacion' => $this->company->ubicacion,
                ];
            }),
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
