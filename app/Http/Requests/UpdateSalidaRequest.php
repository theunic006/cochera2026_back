<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_salida' => 'sometimes|date',
            'hora_salida' => 'sometimes|date_format:H:i:s',
            'tiempo' => 'nullable',
            'precio' => 'nullable|numeric',
            'id_ingreso' => 'sometimes|integer|exists:ingresos,id',
            'id_user' => 'sometimes|integer|exists:users,id',
            'id_empresa' => 'sometimes|integer|exists:companies,id',
            'id_pago' => 'nullable|integer',
        ];
    }
}
