<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_salida' => 'required|date',
            'hora_salida' => 'required|date_format:H:i:s',
            'tiempo' => 'nullable',
            'precio' => 'nullable|numeric',
            'id_ingreso' => 'required|integer|exists:ingresos,id',
            'id_user' => 'required|integer|exists:users,id',
            'id_empresa' => 'required|integer|exists:companies,id',
            'id_pago' => 'nullable|integer',
        ];
    }
}
