<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateObservacionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipo' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
            'id_registro' => 'nullable|integer|exists:registros,id',
            'id_vehiculo' => 'nullable|integer|exists:vehiculos,id',
            'id_ingreso' => 'nullable|integer|exists:ingresos,id',
            'id_empresa' => 'nullable|integer|exists:companies,id',
        ];
    }
}
