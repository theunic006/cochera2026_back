<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'nombre' => $this->nombre,
            'ubicacion' => $this->ubicacion,
            'logo' => $this->logo,
            'capacidad' => $this->capacidad,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'estado_info' => [
                'label' => ucfirst($this->estado),
                'is_active' => $this->isActive(),
                'is_suspended' => $this->isSuspended(),
            ],
            'users_count' => $this->users_count ?? 0,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
